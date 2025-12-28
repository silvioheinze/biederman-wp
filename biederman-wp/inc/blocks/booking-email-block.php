<?php
/**
 * Booking Email Block - Registration
 */

if (!defined('ABSPATH')) { exit; }

/**
 * Register Booking Email block using block.json
 */
function biederman_register_booking_email_block() {
  $block_path = get_template_directory() . '/blocks/booking-email';
  
  if (!file_exists($block_path . '/block.json')) {
    return;
  }
  
  // Register block - WordPress will read block.json automatically
  // We override render_callback to use our function
  $block_args = array(
    'render_callback' => 'biederman_render_booking_email_block',
  );
  
  // Add editor script/style if build files exist
  $build_path = $block_path . '/build';
  if (file_exists($build_path . '/index.js')) {
    $block_args['editor_script'] = 'biederman-booking-email-block-editor';
    wp_register_script(
      'biederman-booking-email-block-editor',
      get_template_directory_uri() . '/blocks/booking-email/build/index.js',
      array('wp-blocks', 'wp-element', 'wp-block-editor', 'wp-components', 'wp-i18n', 'wp-server-side-render'),
      wp_get_theme()->get('Version'),
      true
    );
  }
  
  if (file_exists($build_path . '/index.css')) {
    $block_args['editor_style'] = 'biederman-booking-email-block-editor-style';
    wp_register_style(
      'biederman-booking-email-block-editor-style',
      get_template_directory_uri() . '/blocks/booking-email/build/index.css',
      array('wp-edit-blocks'),
      wp_get_theme()->get('Version')
    );
  }
  
  register_block_type($block_path, $block_args);
}
add_action('init', 'biederman_register_booking_email_block');

/**
 * Render Booking Email block (server-side)
 */
function biederman_render_booking_email_block($attributes, $content) {
  $block_path = get_template_directory() . '/blocks/booking-email';
  if (file_exists($block_path . '/render.php')) {
    ob_start();
    include $block_path . '/render.php';
    return ob_get_clean();
  }
  return '';
}

