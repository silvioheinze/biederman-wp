<?php
/**
 * Admin Template Import Feature
 * Allows importing block template content from templates/front-page.html into the front page
 */

if (!defined('ABSPATH')) { exit; }

/**
 * Add admin menu page
 */
function biederman_add_template_import_menu() {
    add_theme_page(
        __('Template Import', 'biederman'),
        __('Template Import', 'biederman'),
        'manage_options',
        'biederman-template-import',
        'biederman_template_import_page'
    );
}
add_action('admin_menu', 'biederman_add_template_import_menu');

/**
 * Admin page callback
 */
function biederman_template_import_page() {
    // Check user capabilities
    if (!current_user_can('manage_options')) {
        wp_die(__('You do not have sufficient permissions to access this page.', 'biederman'));
    }

    // Handle form submissions
    if (isset($_POST['biederman_import_template']) && check_admin_referer('biederman_import_template_action')) {
        $result = biederman_import_template_content();
        $message = $result['message'];
        $success = $result['success'];
    }
    
    if (isset($_POST['biederman_create_pattern']) && check_admin_referer('biederman_import_template_action')) {
        $result = biederman_register_template_as_pattern();
        $message = $result['message'];
        $success = $result['success'];
    }
    
    if (isset($_POST['biederman_create_reusable']) && check_admin_referer('biederman_import_template_action')) {
        $result = biederman_create_reusable_block_from_template();
        $message = $result['message'];
        $success = $result['success'];
    }

    // Get current front page ID
    $front_page_id = get_option('page_on_front');
    $front_page_title = $front_page_id ? get_the_title($front_page_id) : __('Not set', 'biederman');
    $front_page_edit_link = $front_page_id ? get_edit_post_link($front_page_id) : '#';

    // Check if template file exists
    // Try backup file first, then original
    $template_file = get_template_directory() . '/templates/front-page.html.backup';
    if (!file_exists($template_file)) {
      $template_file = get_template_directory() . '/templates/front-page.html';
    }
    $template_exists = file_exists($template_file);
    $template_size = $template_exists ? filesize($template_file) : 0;
    $template_modified = $template_exists ? filemtime($template_file) : 0;
    ?>
    <div class="wrap">
        <h1><?php echo esc_html__('Template Import', 'biederman'); ?></h1>
        
        <?php if (isset($message)): ?>
            <div class="notice notice-<?php echo $success ? 'success' : 'error'; ?> is-dismissible">
                <p><?php echo esc_html($message); ?></p>
            </div>
        <?php endif; ?>

        <div class="card">
            <h2><?php echo esc_html__('Import Block Template', 'biederman'); ?></h2>
            <p><?php echo esc_html__('Import the block structure from the template file into your front page for editing in the Gutenberg editor.', 'biederman'); ?></p>

            <?php if (!$front_page_id): ?>
                <div class="notice notice-warning">
                    <p><?php echo esc_html__('No front page is set. Please set a static front page in', 'biederman'); ?> 
                    <a href="<?php echo esc_url(admin_url('options-reading.php')); ?>"><?php echo esc_html__('Settings > Reading', 'biederman'); ?></a>.</p>
                </div>
            <?php elseif (!$template_exists): ?>
                <div class="notice notice-error">
                    <p><?php echo esc_html__('Template file not found:', 'biederman'); ?> <code><?php echo esc_html($template_file); ?></code></p>
                </div>
            <?php else: ?>
                <table class="form-table">
                    <tr>
                        <th scope="row"><?php echo esc_html__('Front Page', 'biederman'); ?></th>
                        <td>
                            <strong><?php echo esc_html($front_page_title); ?></strong> 
                            (ID: <?php echo esc_html($front_page_id); ?>)
                            <br>
                            <a href="<?php echo esc_url($front_page_edit_link); ?>" target="_blank">
                                <?php echo esc_html__('Edit in Editor', 'biederman'); ?>
                            </a>
                        </td>
                    </tr>
                    <tr>
                        <th scope="row"><?php echo esc_html__('Template File', 'biederman'); ?></th>
                        <td>
                            <code><?php echo esc_html(str_replace(ABSPATH, '', $template_file)); ?></code>
                            <br>
                            <small>
                                <?php echo esc_html__('Size:', 'biederman'); ?> <?php echo esc_html(size_format($template_size)); ?> | 
                                <?php echo esc_html__('Modified:', 'biederman'); ?> <?php echo esc_html(date_i18n(get_option('date_format') . ' ' . get_option('time_format'), $template_modified)); ?>
                            </small>
                        </td>
                    </tr>
                </table>

                <form method="post" action="">
                    <?php wp_nonce_field('biederman_import_template_action'); ?>
                    <h3><?php echo esc_html__('Import Options', 'biederman'); ?></h3>
                    <table class="form-table">
                        <tr>
                            <th scope="row"><?php echo esc_html__('Import to Front Page', 'biederman'); ?></th>
                            <td>
                                <input type="submit" name="biederman_import_template" class="button button-primary" 
                                       value="<?php echo esc_attr__('Import to Front Page', 'biederman'); ?>" />
                                <p class="description">
                                    <?php echo esc_html__('Imports the template content directly into the front page for editing.', 'biederman'); ?>
                                </p>
                            </td>
                        </tr>
                        <tr>
                            <th scope="row"><?php echo esc_html__('Register as Block Pattern', 'biederman'); ?></th>
                            <td>
                                <input type="submit" name="biederman_create_pattern" class="button" 
                                       value="<?php echo esc_attr__('Register as Pattern', 'biederman'); ?>" />
                                <p class="description">
                                    <?php echo esc_html__('Registers the template as a Block Pattern. Available in the Block Inserter under Patterns > Biederman.', 'biederman'); ?>
                                </p>
                            </td>
                        </tr>
                        <tr>
                            <th scope="row"><?php echo esc_html__('Create Reusable Block', 'biederman'); ?></th>
                            <td>
                                <input type="submit" name="biederman_create_reusable" class="button" 
                                       value="<?php echo esc_attr__('Create Reusable Block', 'biederman'); ?>" />
                                <p class="description">
                                    <?php echo esc_html__('Creates a reusable block that can be inserted on any page and edited globally.', 'biederman'); ?>
                                </p>
                            </td>
                        </tr>
                    </table>
                </form>

                <div class="card" style="margin-top: 20px;">
                    <h3><?php echo esc_html__('What are the differences?', 'biederman'); ?></h3>
                    <ul style="list-style: disc; margin-left: 20px;">
                        <li><strong><?php echo esc_html__('Import to Front Page:', 'biederman'); ?></strong> 
                            <?php echo esc_html__('Replaces the current front page content with the template. Best for initial setup.', 'biederman'); ?>
                        </li>
                        <li><strong><?php echo esc_html__('Block Pattern:', 'biederman'); ?></strong> 
                            <?php echo esc_html__('Makes the template available in the Block Inserter. Can be inserted on any page, but becomes independent after insertion.', 'biederman'); ?>
                        </li>
                        <li><strong><?php echo esc_html__('Reusable Block:', 'biederman'); ?></strong> 
                            <?php echo esc_html__('Creates a reusable block that can be edited globally. Changes affect all instances where it\'s used.', 'biederman'); ?>
                        </li>
                    </ul>
                </div>
            <?php endif; ?>
        </div>
    </div>
    <?php
}

/**
 * Import template content into front page
 */
function biederman_import_template_content() {
    // Check user capabilities
    if (!current_user_can('manage_options')) {
        return array(
            'success' => false,
            'message' => __('You do not have sufficient permissions to perform this action.', 'biederman')
        );
    }

    // Get front page ID
    $front_page_id = get_option('page_on_front');
    if (!$front_page_id) {
        return array(
            'success' => false,
            'message' => __('No front page is set. Please set a static front page in Settings > Reading.', 'biederman')
        );
    }

    // Check if template file exists
    // Try backup file first, then original
    $template_file = get_template_directory() . '/templates/front-page.html.backup';
    if (!file_exists($template_file)) {
      $template_file = get_template_directory() . '/templates/front-page.html';
    }
    if (!file_exists($template_file)) {
        return array(
            'success' => false,
            'message' => sprintf(__('Template file not found: %s', 'biederman'), $template_file)
        );
    }

    // Read template content
    $template_content = file_get_contents($template_file);
    if ($template_content === false) {
        return array(
            'success' => false,
            'message' => __('Failed to read template file.', 'biederman')
        );
    }

    // Update the page content
    $result = wp_update_post(array(
        'ID' => $front_page_id,
        'post_content' => $template_content,
    ));

    if ($result && !is_wp_error($result)) {
        // Clear any caches
        clean_post_cache($front_page_id);
        
        return array(
            'success' => true,
            'message' => sprintf(
                __('Successfully imported template content into page "%s" (ID: %d). You can now edit it in the Gutenberg editor.', 'biederman'),
                get_the_title($front_page_id),
                $front_page_id
            )
        );
    } else {
        return array(
            'success' => false,
            'message' => is_wp_error($result) 
                ? $result->get_error_message() 
                : __('Failed to update page content.', 'biederman')
        );
    }
}

/**
 * Register template as Block Pattern
 */
function biederman_register_template_as_pattern() {
    // Check user capabilities
    if (!current_user_can('manage_options')) {
        return array(
            'success' => false,
            'message' => __('You do not have sufficient permissions to perform this action.', 'biederman')
        );
    }

    // Check if register_block_pattern function exists
    if (!function_exists('register_block_pattern')) {
        return array(
            'success' => false,
            'message' => __('Block patterns are not supported in this WordPress version.', 'biederman')
        );
    }

    // Check if template file exists
    // Try backup file first, then original
    $template_file = get_template_directory() . '/templates/front-page.html.backup';
    if (!file_exists($template_file)) {
      $template_file = get_template_directory() . '/templates/front-page.html';
    }
    if (!file_exists($template_file)) {
        return array(
            'success' => false,
            'message' => sprintf(__('Template file not found: %s', 'biederman'), $template_file)
        );
    }

    // Read template content
    $template_content = file_get_contents($template_file);
    if ($template_content === false) {
        return array(
            'success' => false,
            'message' => __('Failed to read template file.', 'biederman')
        );
    }

    // Ensure pattern category is registered
    if (function_exists('register_block_pattern_category')) {
        register_block_pattern_category('biederman', array(
            'label' => __('Biederman', 'biederman'),
        ));
    }
    
    // Register the pattern (for current session)
    register_block_pattern('biederman/front-page-template', array(
        'title' => __('Front Page Template', 'biederman'),
        'description' => __('Complete front page structure from template file', 'biederman'),
        'content' => $template_content,
        'categories' => array('biederman', 'featured'),
    ));
    
    // Save pattern permanently in theme code
    $blocks_file = get_template_directory() . '/inc/blocks.php';
    if (file_exists($blocks_file) && is_writable($blocks_file)) {
        $blocks_content = file_get_contents($blocks_file);
        
        // Escape content for PHP string
        $escaped_content = addslashes($template_content);
        $escaped_content = str_replace(array("\r\n", "\r", "\n"), "\\n", $escaped_content);
        
        // Check if pattern already exists
        $pattern_exists = strpos($blocks_content, "register_block_pattern('biederman/front-page-template'") !== false;
        
        if (!$pattern_exists) {
            // Find the insertion point (before the closing brace of biederman_register_block_patterns function)
            $insertion_point = strrpos($blocks_content, '  }\nadd_action');
            if ($insertion_point === false) {
                $insertion_point = strrpos($blocks_content, '}\nadd_action');
            }
            
            if ($insertion_point !== false) {
                // Insert pattern registration before the closing brace
                $pattern_code = "\n  // Front Page Template Pattern (from template file)\n  register_block_pattern('biederman/front-page-template', array(\n    'title' => __('Front Page Template', 'biederman'),\n    'description' => __('Complete front page structure from template file', 'biederman'),\n    'content' => '" . $escaped_content . "',\n    'categories' => array('biederman', 'featured'),\n  ));\n";
                
                $new_content = substr_replace($blocks_content, $pattern_code, $insertion_point, 0);
                
                if (file_put_contents($blocks_file, $new_content) !== false) {
                    return array(
                        'success' => true,
                        'message' => __('Template successfully registered as Block Pattern and saved permanently in theme code. You can find it in the Block Inserter under Patterns > Biederman > Front Page Template.', 'biederman')
                    );
                }
            }
        } else {
            // Pattern already exists, update it
            $pattern_regex = "/register_block_pattern\('biederman\/front-page-template',\s*array\([^)]+\)\);/s";
            $pattern_code = "register_block_pattern('biederman/front-page-template', array(\n    'title' => __('Front Page Template', 'biederman'),\n    'description' => __('Complete front page structure from template file', 'biederman'),\n    'content' => '" . $escaped_content . "',\n    'categories' => array('biederman', 'featured'),\n  ));";
            
            $new_content = preg_replace($pattern_regex, $pattern_code, $blocks_content);
            
            if ($new_content !== null && file_put_contents($blocks_file, $new_content) !== false) {
                return array(
                    'success' => true,
                    'message' => __('Template pattern updated successfully in theme code.', 'biederman')
                );
            }
        }
    }

    return array(
        'success' => true,
        'message' => __('Template successfully registered as Block Pattern for this session. Note: Pattern was not saved permanently (theme file is not writable). You can find it in the Block Inserter under Patterns > Biederman > Front Page Template.', 'biederman')
    );
}

/**
 * Create reusable block from template
 */
function biederman_create_reusable_block_from_template() {
    // Check user capabilities
    if (!current_user_can('manage_options')) {
        return array(
            'success' => false,
            'message' => __('You do not have sufficient permissions to perform this action.', 'biederman')
        );
    }

    // Check if template file exists
    // Try backup file first, then original
    $template_file = get_template_directory() . '/templates/front-page.html.backup';
    if (!file_exists($template_file)) {
      $template_file = get_template_directory() . '/templates/front-page.html';
    }
    if (!file_exists($template_file)) {
        return array(
            'success' => false,
            'message' => sprintf(__('Template file not found: %s', 'biederman'), $template_file)
        );
    }

    // Read template content
    $template_content = file_get_contents($template_file);
    if ($template_content === false) {
        return array(
            'success' => false,
            'message' => __('Failed to read template file.', 'biederman')
        );
    }

    // Check if reusable block already exists
    $existing_block = get_posts(array(
        'post_type' => 'wp_block',
        'post_status' => 'publish',
        'title' => 'Front Page Template',
        'posts_per_page' => 1,
    ));

    $block_title = __('Front Page Template', 'biederman');
    
    if (!empty($existing_block)) {
        // Update existing reusable block
        $block_id = $existing_block[0]->ID;
        $result = wp_update_post(array(
            'ID' => $block_id,
            'post_content' => $template_content,
        ));
        
        if ($result && !is_wp_error($result)) {
            return array(
                'success' => true,
                'message' => sprintf(
                    __('Reusable block "%s" updated successfully. You can find it in the Block Inserter under Reusable blocks.', 'biederman'),
                    $block_title
                )
            );
        } else {
            return array(
                'success' => false,
                'message' => is_wp_error($result) 
                    ? $result->get_error_message() 
                    : __('Failed to update reusable block.', 'biederman')
            );
        }
    } else {
        // Create new reusable block
        $result = wp_insert_post(array(
            'post_title' => $block_title,
            'post_content' => $template_content,
            'post_status' => 'publish',
            'post_type' => 'wp_block',
        ));
        
        if ($result && !is_wp_error($result)) {
            return array(
                'success' => true,
                'message' => sprintf(
                    __('Reusable block "%s" created successfully. You can find it in the Block Inserter under Reusable blocks.', 'biederman'),
                    $block_title
                )
            );
        } else {
            return array(
                'success' => false,
                'message' => is_wp_error($result) 
                    ? $result->get_error_message() 
                    : __('Failed to create reusable block.', 'biederman')
            );
        }
    }
}

