<?php
/**
 * Theme Updater - Automatic updates from GitHub Releases
 * 
 * This enables automatic theme updates from GitHub Releases.
 * The update server checks GitHub Releases API for new versions.
 */

if (!defined('ABSPATH')) { exit; }

/**
 * GitHub repository information
 */
define('BIEDERMAN_GITHUB_USER', 'silvioheinze');
define('BIEDERMAN_GITHUB_REPO', 'biederman-wp');
define('BIEDERMAN_GITHUB_API_URL', 'https://api.github.com/repos/' . BIEDERMAN_GITHUB_USER . '/' . BIEDERMAN_GITHUB_REPO . '/releases/latest');

/**
 * Initialize theme updater
 */
function biederman_theme_updater_init() {
    // Only run in admin
    if (!is_admin()) {
        return;
    }
    
    // Add update check filter
    add_filter('pre_set_site_transient_update_themes', 'biederman_check_theme_update');
    
    // Add theme info filter
    add_filter('themes_api', 'biederman_theme_api', 10, 3);
    
    // Add update action
    add_action('upgrader_process_complete', 'biederman_after_theme_update', 10, 2);
    
    // Add manual update check action
    add_action('admin_post_biederman_check_updates', 'biederman_manual_update_check');
    
    // Add admin notice for manual check
    add_action('admin_notices', 'biederman_update_check_notice');
}
add_action('admin_init', 'biederman_theme_updater_init');

/**
 * Check for theme updates
 */
function biederman_check_theme_update($transient) {
    if (empty($transient->checked)) {
        return $transient;
    }
    
    $theme_slug = get_template();
    $current_version = wp_get_theme($theme_slug)->get('Version');
    
    // Get latest release from GitHub
    $latest_release = biederman_get_latest_release();
    
    if (!$latest_release || is_wp_error($latest_release)) {
        return $transient;
    }
    
    $latest_version = $latest_release['version'];
    
    // Compare versions
    if (version_compare($current_version, $latest_version, '<')) {
        $transient->response[$theme_slug] = array(
            'theme' => $theme_slug,
            'new_version' => $latest_version,
            'url' => $latest_release['url'],
            'package' => $latest_release['package'],
        );
    }
    
    return $transient;
}

/**
 * Get latest release from GitHub
 */
function biederman_get_latest_release() {
    // Cache for 1 hour
    $cache_key = 'biederman_latest_release';
    $cached = get_transient($cache_key);
    
    if ($cached !== false) {
        return $cached;
    }
    
    // Make API request
    $response = wp_remote_get(BIEDERMAN_GITHUB_API_URL, array(
        'headers' => array(
            'Accept' => 'application/vnd.github.v3+json',
            'User-Agent' => 'WordPress Theme Updater',
        ),
        'timeout' => 15,
    ));
    
    if (is_wp_error($response)) {
        return $response;
    }
    
    $body = wp_remote_retrieve_body($response);
    $data = json_decode($body, true);
    
    if (empty($data) || !isset($data['tag_name'])) {
        return new WP_Error('no_release', __('No release found', 'biederman'));
    }
    
    // Extract version from tag (remove 'v' prefix if present)
    $version = ltrim($data['tag_name'], 'v');
    
    // Find ZIP asset
    $package_url = '';
    if (isset($data['assets']) && is_array($data['assets'])) {
        foreach ($data['assets'] as $asset) {
            if (isset($asset['browser_download_url']) && strpos($asset['name'], '.zip') !== false) {
                $package_url = $asset['browser_download_url'];
                break;
            }
        }
    }
    
    // If no asset found, try to construct download URL
    if (empty($package_url)) {
        $package_url = sprintf(
            'https://github.com/%s/%s/releases/download/%s/biederman-wp-theme-%s.zip',
            BIEDERMAN_GITHUB_USER,
            BIEDERMAN_GITHUB_REPO,
            $data['tag_name'],
            $version
        );
    }
    
    $release_data = array(
        'version' => $version,
        'url' => $data['html_url'],
        'package' => $package_url,
        'changelog' => isset($data['body']) ? $data['body'] : '',
    );
    
    // Cache for 1 hour
    set_transient($cache_key, $release_data, HOUR_IN_SECONDS);
    
    return $release_data;
}

/**
 * Theme API filter
 */
function biederman_theme_api($result, $action, $args) {
    if ($action !== 'theme_information' || !isset($args->slug) || $args->slug !== get_template()) {
        return $result;
    }
    
    $latest_release = biederman_get_latest_release();
    
    if (!$latest_release || is_wp_error($latest_release)) {
        return $result;
    }
    
    $theme = wp_get_theme();
    
    return (object) array(
        'name' => $theme->get('Name'),
        'slug' => get_template(),
        'version' => $latest_release['version'],
        'author' => $theme->get('Author'),
        'author_profile' => '',
        'requires' => '5.0',
        'tested' => get_bloginfo('version'),
        'requires_php' => '7.4',
        'downloaded' => 0,
        'last_updated' => date('Y-m-d'),
        'sections' => array(
            'description' => $theme->get('Description'),
            'changelog' => !empty($latest_release['changelog']) ? wp_kses_post($latest_release['changelog']) : __('No changelog available.', 'biederman'),
        ),
        'download_link' => $latest_release['package'],
        'homepage' => $theme->get('ThemeURI'),
    );
}

/**
 * After theme update hook
 */
function biederman_after_theme_update($upgrader, $hook_extra) {
    if ($hook_extra['action'] !== 'update' || $hook_extra['type'] !== 'theme') {
        return;
    }
    
    if (!isset($hook_extra['themes']) || !in_array(get_template(), $hook_extra['themes'])) {
        return;
    }
    
    // Clear update cache
    delete_transient('biederman_latest_release');
    
    // Clear WordPress update cache
    delete_site_transient('update_themes');
}

/**
 * Manual update check
 */
function biederman_manual_update_check() {
    // Check nonce
    if (!isset($_GET['_wpnonce']) || !wp_verify_nonce($_GET['_wpnonce'], 'biederman_check_updates')) {
        wp_die(__('Security check failed', 'biederman'));
    }
    
    // Check user capabilities
    if (!current_user_can('update_themes')) {
        wp_die(__('You do not have permission to check for updates', 'biederman'));
    }
    
    // Clear caches
    delete_transient('biederman_latest_release');
    delete_site_transient('update_themes');
    
    // Force update check
    wp_update_themes();
    
    // Redirect back to themes page
    wp_redirect(admin_url('themes.php?biederman_update_checked=1'));
    exit;
}

/**
 * Admin notice for update check
 */
function biederman_update_check_notice() {
    if (!isset($_GET['biederman_update_checked'])) {
        return;
    }
    
    $screen = get_current_screen();
    if ($screen && $screen->id === 'themes') {
        ?>
        <div class="notice notice-success is-dismissible">
            <p><?php esc_html_e('Update check completed. Please refresh the page to see if updates are available.', 'biederman'); ?></p>
        </div>
        <?php
    }
}

/**
 * Add manual update check button to themes page
 */
function biederman_add_update_check_button($theme) {
    if ($theme->get_stylesheet() !== get_template()) {
        return;
    }
    
    $check_url = wp_nonce_url(
        admin_url('admin-post.php?action=biederman_check_updates'),
        'biederman_check_updates'
    );
    
    ?>
    <script type="text/javascript">
    jQuery(document).ready(function($) {
        var checkButton = $('<a>')
            .attr('href', '<?php echo esc_js($check_url); ?>')
            .addClass('button')
            .text('<?php esc_html_e('Nach Updates suchen', 'biederman'); ?>')
            .css('margin-left', '10px');
        
        $('.theme-actions .button-primary').after(checkButton);
    });
    </script>
    <?php
}
add_action('admin_footer-themes.php', 'biederman_add_update_check_button');

