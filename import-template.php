<?php
/**
 * Script to import block template content into WordPress page
 * 
 * Usage: Run this script via WP-CLI or place it in WordPress root and access via browser
 * 
 * WP-CLI: wp eval-file import-template.php
 * Browser: http://localhost:8080/import-template.php (remove after use!)
 */

// Load WordPress
require_once(__DIR__ . '/biederman-wp/wp-load.php');

// Check if user is admin (for security)
if (!current_user_can('manage_options')) {
    die('You must be logged in as an administrator to run this script.');
}

// Path to template file
$template_file = __DIR__ . '/biederman-wp/templates/front-page.html';

if (!file_exists($template_file)) {
    die("Template file not found: $template_file\n");
}

// Read template content
$template_content = file_get_contents($template_file);

// Get the front page ID
$front_page_id = get_option('page_on_front');

if (!$front_page_id) {
    die("No front page is set. Please set a static front page in Settings > Reading.\n");
}

// Update the page content
$result = wp_update_post(array(
    'ID' => $front_page_id,
    'post_content' => $template_content,
));

if ($result && !is_wp_error($result)) {
    echo "✓ Successfully imported template content into page ID: $front_page_id\n";
    echo "✓ You can now edit the page in the WordPress editor.\n";
    echo "\n";
    echo "Page URL: " . get_edit_post_link($front_page_id) . "\n";
} else {
    echo "✗ Error importing template: " . (is_wp_error($result) ? $result->get_error_message() : 'Unknown error') . "\n";
}

