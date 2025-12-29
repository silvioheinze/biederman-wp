<?php
/**
 * Press Assets Block - Server-side rendering
 */

if (!defined('ABSPATH')) { exit; }

$type = isset($attributes['type']) ? $attributes['type'] : '';
$limit = isset($attributes['limit']) ? intval($attributes['limit']) : -1;

// Get block wrapper attributes
global $biederman_current_block;
if (isset($biederman_current_block) && function_exists('get_block_wrapper_attributes')) {
  $wrapper_attributes = get_block_wrapper_attributes();
} else {
  // Fallback if get_block_wrapper_attributes is not available
  $wrapper_attributes = 'class="wp-block-biederman-press-assets"';
}

$query = biederman_get_press_assets($type);

if (!$query->have_posts()) {
  echo '<div ' . $wrapper_attributes . '><p class="muted">' . esc_html__('Keine Press Assets gefunden.', 'biederman') . '</p></div>';
  return;
}

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

