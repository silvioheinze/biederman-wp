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
function biederman_render_press_assets_block($attributes, $content, $block) {
  // Make $block available to render.php
  global $biederman_current_block;
  $biederman_current_block = $block;
  
  $type = isset($attributes['type']) ? $attributes['type'] : '';
  $limit = isset($attributes['limit']) ? intval($attributes['limit']) : -1;
  
  $query = biederman_get_press_assets($type);
  
  // Get block wrapper attributes
  if (isset($biederman_current_block) && function_exists('get_block_wrapper_attributes')) {
    $wrapper_attributes = get_block_wrapper_attributes();
  } else {
    $wrapper_attributes = 'class="wp-block-biederman-press-assets"';
  }
  
  if (!$query->have_posts()) {
    return '<div ' . $wrapper_attributes . '><p class="muted">' . esc_html__('Keine Press Assets gefunden.', 'biederman') . '</p></div>';
  }
  
  ob_start();
  echo '<ul ' . $wrapper_attributes . ' class="list" style="list-style: none; padding-left: 0;">';
  $count = 0;
  while ($query->have_posts() && ($limit == -1 || $count < $limit)) {
    $query->the_post();
    
    $press_type = get_post_meta(get_the_ID(), 'press_type', true);
    $press_download_url = get_post_meta(get_the_ID(), 'press_download_url', true);
    $press_file_size = get_post_meta(get_the_ID(), 'press_file_size', true);
    
    echo '<li style="margin-bottom: 1rem; padding-bottom: 1rem; border-bottom: 1px solid var(--line);">';
    
    // Title and type
    echo '<div style="display: flex; align-items: center; gap: 0.5rem; margin-bottom: 0.5rem;">';
    if ($press_type) {
      echo '<span class="pill">' . esc_html($press_type) . '</span>';
    }
    echo '<strong>' . esc_html(get_the_title()) . '</strong>';
    echo '</div>';
    
    // Description
    if (has_excerpt()) {
      echo '<p style="margin: 0 0 0.5rem; color: rgba(243,245,247,.88);">' . get_the_excerpt() . '</p>';
    }
    
    // Download link
    if ($press_download_url) {
      $download_text = esc_html__('Download', 'biederman');
      if ($press_file_size) {
        $download_text .= ' (' . esc_html($press_file_size) . ')';
      }
      echo '<a class="button" href="' . esc_url($press_download_url) . '" download style="display: inline-block;">' . $download_text . '</a>';
    }
    
    echo '</li>';
    $count++;
  }
  echo '</ul>';
  wp_reset_postdata();
  
  $output = ob_get_clean();
  $biederman_current_block = null;
  return $output;
}

