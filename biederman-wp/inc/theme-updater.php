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
    
    // Add admin notice with manual check button (fallback)
    add_action('admin_notices', 'biederman_update_check_admin_notice');
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
    
    // Compare versions (use strict comparison)
    if (version_compare($current_version, $latest_version, '<')) {
        // Verify package URL exists before adding to response
        if (!empty($latest_release['package'])) {
            $transient->response[$theme_slug] = array(
                'theme' => $theme_slug,
                'new_version' => $latest_version,
                'url' => $latest_release['url'],
                'package' => $latest_release['package'],
                'requires' => '5.0',
                'requires_php' => '7.4',
            );
        }
    } else {
        // Remove from response if versions match (prevent false update notifications)
        if (isset($transient->response[$theme_slug])) {
            unset($transient->response[$theme_slug]);
        }
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
    
    // Expected ZIP filename format: biederman-wp-theme-{version}.zip
    $expected_filename = sprintf('biederman-wp-theme-%s.zip', $version);
    
    // Find ZIP asset - prioritize exact match with expected filename
    $package_url = '';
    if (isset($data['assets']) && is_array($data['assets'])) {
        // First pass: look for exact filename match
        foreach ($data['assets'] as $asset) {
            if (isset($asset['browser_download_url']) && isset($asset['name'])) {
                if ($asset['name'] === $expected_filename) {
                    $package_url = $asset['browser_download_url'];
                    break;
                }
            }
        }
        
        // Second pass: look for files containing 'biederman-wp-theme' and version
        if (empty($package_url)) {
            foreach ($data['assets'] as $asset) {
                if (isset($asset['browser_download_url']) && isset($asset['name'])) {
                    if (strpos($asset['name'], 'biederman-wp-theme') !== false && 
                        strpos($asset['name'], $version) !== false && 
                        strpos($asset['name'], '.zip') !== false) {
                        $package_url = $asset['browser_download_url'];
                        break;
                    }
                }
            }
        }
        
        // Third pass: fallback to any ZIP file containing 'biederman-wp-theme'
        if (empty($package_url)) {
            foreach ($data['assets'] as $asset) {
                if (isset($asset['browser_download_url']) && isset($asset['name'])) {
                    if (strpos($asset['name'], 'biederman-wp-theme') !== false && 
                        strpos($asset['name'], '.zip') !== false) {
                        $package_url = $asset['browser_download_url'];
                        break;
                    }
                }
            }
        }
    }
    
    // If no asset found, construct download URL using expected format
    if (empty($package_url)) {
        $package_url = sprintf(
            'https://github.com/%s/%s/releases/download/%s/%s',
            BIEDERMAN_GITHUB_USER,
            BIEDERMAN_GITHUB_REPO,
            $data['tag_name'],
            $expected_filename
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
    
    // Redirect back to themes page (without notice parameter)
    wp_redirect(admin_url('themes.php'));
    exit;
}

/**
 * Admin notice with manual check button (fallback method)
 */
function biederman_update_check_admin_notice() {
    $screen = get_current_screen();
    if (!$screen || $screen->id !== 'themes') {
        return;
    }
    
    // Only show if we're on the themes page and user can update themes
    if (!current_user_can('update_themes')) {
        return;
    }
    
    $check_url = wp_nonce_url(
        admin_url('admin-post.php?action=biederman_check_updates'),
        'biederman_check_updates'
    );
    
    ?>
    <div class="notice notice-info is-dismissible">
        <p>
            <strong><?php esc_html_e('Biederman Theme Updates', 'biederman'); ?>:</strong>
            <a href="<?php echo esc_url($check_url); ?>" class="button" style="margin-left: 10px;">
                <?php esc_html_e('Nach Updates suchen', 'biederman'); ?>
            </a>
        </p>
    </div>
    <?php
}

/**
 * Add manual update check button to themes page
 */
function biederman_add_update_check_button() {
    $screen = get_current_screen();
    if (!$screen || $screen->id !== 'themes') {
        return;
    }
    
    $theme_slug = get_template();
    $check_url = wp_nonce_url(
        admin_url('admin-post.php?action=biederman_check_updates'),
        'biederman_check_updates'
    );
    
    ?>
    <script type="text/javascript">
    jQuery(document).ready(function($) {
        // Find the active theme card
        var activeTheme = $('.theme.active, .theme.current');
        if (activeTheme.length === 0) {
            // Try alternative selectors
            activeTheme = $('.theme[data-slug="<?php echo esc_js($theme_slug); ?>"]');
        }
        
        if (activeTheme.length > 0) {
            var actionsDiv = activeTheme.find('.theme-actions, .theme-id-container .theme-actions');
            if (actionsDiv.length > 0) {
                var checkButton = $('<a>')
                    .attr('href', '<?php echo esc_js($check_url); ?>')
                    .addClass('button')
                    .text('<?php esc_html_e('Nach Updates suchen', 'biederman'); ?>')
                    .css('margin-left', '10px');
                
                // Try to add after primary button, otherwise just append
                var primaryButton = actionsDiv.find('.button-primary');
                if (primaryButton.length > 0) {
                    primaryButton.after(checkButton);
                } else {
                    actionsDiv.append(checkButton);
                }
            }
        } else {
            // Fallback: add to all theme actions
            $('.theme-actions .button-primary').each(function() {
                if (!$(this).next('.biederman-check-updates').length) {
                    var checkButton = $('<a>')
                        .attr('href', '<?php echo esc_js($check_url); ?>')
                        .addClass('button biederman-check-updates')
                        .text('<?php esc_html_e('Nach Updates suchen', 'biederman'); ?>')
                        .css('margin-left', '10px');
                    $(this).after(checkButton);
                }
            });
        }
    });
    </script>
    <?php
}
add_action('admin_footer-themes.php', 'biederman_add_update_check_button');

/**
 * Add update check link to theme action links (alternative method)
 */
function biederman_theme_action_links($actions, $theme) {
    if ($theme->get_stylesheet() !== get_template()) {
        return $actions;
    }
    
    $check_url = wp_nonce_url(
        admin_url('admin-post.php?action=biederman_check_updates'),
        'biederman_check_updates'
    );
    
    $actions['biederman_check_updates'] = '<a href="' . esc_url($check_url) . '">' . esc_html__('Nach Updates suchen', 'biederman') . '</a>';
    
    return $actions;
}
add_filter('theme_action_links', 'biederman_theme_action_links', 10, 2);

