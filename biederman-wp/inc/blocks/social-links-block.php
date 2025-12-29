<?php
/**
 * Social Links Block - Registration
 */

if (!defined('ABSPATH')) { exit; }

/**
 * Register Social Links block using block.json
 */
function biederman_register_social_links_block() {
  $block_path = get_template_directory() . '/blocks/social-links';
  
  if (!file_exists($block_path . '/block.json')) {
    return;
  }
  
  // Register block - WordPress will read block.json automatically
  // We override render_callback to use our function
  $block_args = array(
    'render_callback' => 'biederman_render_social_links_block',
  );
  
  // Add editor script/style if build files exist
  $build_path = $block_path . '/build';
  if (file_exists($build_path . '/index.js')) {
    $block_args['editor_script'] = 'biederman-social-links-block-editor';
    wp_register_script(
      'biederman-social-links-block-editor',
      get_template_directory_uri() . '/blocks/social-links/build/index.js',
      array('wp-blocks', 'wp-element', 'wp-block-editor', 'wp-components', 'wp-i18n', 'wp-server-side-render'),
      wp_get_theme()->get('Version'),
      true
    );
  }
  
  if (file_exists($build_path . '/index.css')) {
    $block_args['editor_style'] = 'biederman-social-links-block-editor-style';
    wp_register_style(
      'biederman-social-links-block-editor-style',
      get_template_directory_uri() . '/blocks/social-links/build/index.css',
      array('wp-edit-blocks'),
      wp_get_theme()->get('Version')
    );
  }
  
  if (file_exists($build_path . '/style-index.css')) {
    $block_args['style'] = 'biederman-social-links-block-style';
    wp_register_style(
      'biederman-social-links-block-style',
      get_template_directory_uri() . '/blocks/social-links/build/style-index.css',
      array(),
      wp_get_theme()->get('Version')
    );
  }
  
  register_block_type($block_path, $block_args);
}
add_action('init', 'biederman_register_social_links_block');

/**
 * Render Social Links block (server-side)
 */
function biederman_render_social_links_block($attributes, $content) {
  $block_path = get_template_directory() . '/blocks/social-links';
  if (file_exists($block_path . '/render.php')) {
    ob_start();
    include $block_path . '/render.php';
    return ob_get_clean();
  }
  return '';
}

