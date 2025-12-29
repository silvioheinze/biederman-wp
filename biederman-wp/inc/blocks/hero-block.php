<?php
/**
 * Hero Block - Registration
 */

if (!defined('ABSPATH')) { exit; }

/**
 * Register Hero block using block.json
 */
function biederman_register_hero_block() {
  $block_path = get_template_directory() . '/blocks/hero';
  
  if (!file_exists($block_path . '/block.json')) {
    return;
  }
  
  $block_args = array(
    'render_callback' => 'biederman_render_hero_block',
  );

  $build_path = $block_path . '/build';
  if (file_exists($build_path . '/index.js')) {
    $block_args['editor_script'] = 'biederman-hero-block-editor';
    wp_register_script(
      'biederman-hero-block-editor',
      get_template_directory_uri() . '/blocks/hero/build/index.js',
      array('wp-blocks', 'wp-element', 'wp-block-editor', 'wp-components', 'wp-i18n', 'wp-server-side-render'),
      wp_get_theme()->get('Version'),
      true
    );
  }
  
  if (file_exists($build_path . '/index.css')) {
    $block_args['editor_style'] = 'biederman-hero-block-editor-style';
    wp_register_style(
      'biederman-hero-block-editor-style',
      get_template_directory_uri() . '/blocks/hero/build/index.css',
      array('wp-edit-blocks'),
      wp_get_theme()->get('Version')
    );
  }
  
  if (file_exists($build_path . '/style-index.css')) {
    $block_args['style'] = 'biederman-hero-block-style';
    wp_register_style(
      'biederman-hero-block-style',
      get_template_directory_uri() . '/blocks/hero/build/style-index.css',
      array(),
      wp_get_theme()->get('Version')
    );
  }
  
  register_block_type($block_path, $block_args);
}
add_action('init', 'biederman_register_hero_block', 20);

/**
 * Render Hero block (server-side)
 */
function biederman_render_hero_block($attributes, $content, $block) {
  // Make $block available to render.php
  global $biederman_current_block;
  $biederman_current_block = $block;
  
  ob_start();
  include get_template_directory() . '/blocks/hero/render.php';
  $output = ob_get_clean();
  
  $biederman_current_block = null;
  return $output;
}

