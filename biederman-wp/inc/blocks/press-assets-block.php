<?php
/**
 * Press Assets Block - Registration
 * Uses block.json for modern block registration
 */

if (!defined('ABSPATH')) { exit; }

/**
 * Register Press Assets block using block.json
 */
function biederman_register_press_assets_block() {
  $block_path = get_template_directory() . '/blocks/press-assets';
  
  // Check if block.json exists
  if (!file_exists($block_path . '/block.json')) {
    return;
  }
  
  // Register block - WordPress will read block.json automatically
  // We override render_callback to use our function
  $block_args = array(
    'render_callback' => 'biederman_render_press_assets_block',
  );
  
  // Add editor script/style if build files exist
  $build_path = $block_path . '/build';
  if (file_exists($build_path . '/index.js')) {
    $block_args['editor_script'] = 'biederman-press-assets-block-editor';
    wp_register_script(
      'biederman-press-assets-block-editor',
      get_template_directory_uri() . '/blocks/press-assets/build/index.js',
      array('wp-blocks', 'wp-element', 'wp-block-editor', 'wp-components', 'wp-i18n', 'wp-server-side-render'),
      wp_get_theme()->get('Version'),
      true
    );
  }
  
  if (file_exists($build_path . '/index.css')) {
    $block_args['editor_style'] = 'biederman-press-assets-block-editor-style';
    wp_register_style(
      'biederman-press-assets-block-editor-style',
      get_template_directory_uri() . '/blocks/press-assets/build/index.css',
      array('wp-edit-blocks'),
      wp_get_theme()->get('Version')
    );
  }
  
  if (file_exists($build_path . '/style-index.css')) {
    $block_args['style'] = 'biederman-press-assets-block-style';
    wp_register_style(
      'biederman-press-assets-block-style',
      get_template_directory_uri() . '/blocks/press-assets/build/style-index.css',
      array(),
      wp_get_theme()->get('Version')
    );
  }
  
  register_block_type($block_path, $block_args);
}
add_action('init', 'biederman_register_press_assets_block');

/**
 * Render Press Assets block (server-side)
 */
function biederman_render_press_assets_block($attributes) {
  $type = isset($attributes['type']) ? $attributes['type'] : '';
  $limit = isset($attributes['limit']) ? intval($attributes['limit']) : -1;
  
  $query = biederman_get_press_assets($type);
  
  if (!$query->have_posts()) {
    return '<div class="wp-block-biederman-press-assets"><p class="muted">' . esc_html__('Keine Press Assets gefunden.', 'biederman') . '</p></div>';
  }
  
  ob_start();
  echo '<div class="wp-block-biederman-press-assets cards">';
  $count = 0;
  while ($query->have_posts() && ($limit == -1 || $count < $limit)) {
    $query->the_post();
    get_template_part('template-parts/content', 'press-asset');
    $count++;
  }
  echo '</div>';
  wp_reset_postdata();
  
  return ob_get_clean();
}

