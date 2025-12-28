<?php
/**
 * Show List Block - Registration
 */

if (!defined('ABSPATH')) { exit; }

/**
 * Register Show List block using block.json
 */
function biederman_register_show_list_block() {
  $block_path = get_template_directory() . '/blocks/show-list';
  
  if (!file_exists($block_path . '/block.json')) {
    return;
  }
  
  $block_args = array(
    'render_callback' => 'biederman_render_show_list_block',
  );

  $build_path = $block_path . '/build';
  if (file_exists($build_path . '/index.js')) {
    $block_args['editor_script'] = 'biederman-show-list-block-editor';
    wp_register_script(
      'biederman-show-list-block-editor',
      get_template_directory_uri() . '/blocks/show-list/build/index.js',
      array('wp-blocks', 'wp-element', 'wp-block-editor', 'wp-components', 'wp-i18n', 'wp-server-side-render'),
      wp_get_theme()->get('Version'),
      true
    );
  }
  
  if (file_exists($build_path . '/index.css')) {
    $block_args['editor_style'] = 'biederman-show-list-block-editor-style';
    wp_register_style(
      'biederman-show-list-block-editor-style',
      get_template_directory_uri() . '/blocks/show-list/build/index.css',
      array('wp-edit-blocks'),
      wp_get_theme()->get('Version')
    );
  }
  
  if (file_exists($build_path . '/style-index.css')) {
    $block_args['style'] = 'biederman-show-list-block-style';
    wp_register_style(
      'biederman-show-list-block-style',
      get_template_directory_uri() . '/blocks/show-list/build/style-index.css',
      array(),
      wp_get_theme()->get('Version')
    );
  }
  
  register_block_type($block_path, $block_args);
}
add_action('init', 'biederman_register_show_list_block', 20);

/**
 * Render Show List block (server-side)
 */
function biederman_render_show_list_block($attributes) {
  ob_start();
  include get_template_directory() . '/blocks/show-list/render.php';
  return ob_get_clean();
}

