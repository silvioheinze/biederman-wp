<?php
/**
 * Facts Block - Registration
 */

if (!defined('ABSPATH')) { exit; }

/**
 * Register Facts block using block.json
 */
function biederman_register_facts_block() {
  $block_path = get_template_directory() . '/blocks/facts';
  
  if (!file_exists($block_path . '/block.json')) {
    return;
  }
  
  $block_args = array(
    'render_callback' => 'biederman_render_facts_block',
  );

  $build_path = $block_path . '/build';
  if (file_exists($build_path . '/index.js')) {
    $block_args['editor_script'] = 'biederman-facts-block-editor';
    wp_register_script(
      'biederman-facts-block-editor',
      get_template_directory_uri() . '/blocks/facts/build/index.js',
      array('wp-blocks', 'wp-element', 'wp-block-editor', 'wp-components', 'wp-i18n'),
      wp_get_theme()->get('Version'),
      true
    );
  }
  
  if (file_exists($build_path . '/index.css')) {
    $block_args['editor_style'] = 'biederman-facts-block-editor-style';
    wp_register_style(
      'biederman-facts-block-editor-style',
      get_template_directory_uri() . '/blocks/facts/build/index.css',
      array('wp-edit-blocks'),
      wp_get_theme()->get('Version')
    );
  }
  
  if (file_exists($build_path . '/style-index.css')) {
    $block_args['style'] = 'biederman-facts-block-style';
    wp_register_style(
      'biederman-facts-block-style',
      get_template_directory_uri() . '/blocks/facts/build/style-index.css',
      array(),
      wp_get_theme()->get('Version')
    );
  }
  
  register_block_type($block_path, $block_args);
}
add_action('init', 'biederman_register_facts_block');

/**
 * Render Facts block (server-side)
 */
function biederman_render_facts_block($attributes) {
  ob_start();
  include get_template_directory() . '/blocks/facts/render.php';
  return ob_get_clean();
}

