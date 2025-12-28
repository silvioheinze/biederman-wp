<?php
/**
 * Press Assets Block - Server-side rendering
 */

if (!defined('ABSPATH')) { exit; }

$type = isset($attributes['type']) ? $attributes['type'] : '';
$limit = isset($attributes['limit']) ? intval($attributes['limit']) : -1;
$wrapper_attributes = get_block_wrapper_attributes();

$query = biederman_get_press_assets($type);

if (!$query->have_posts()) {
  echo '<div ' . $wrapper_attributes . '><p class="muted">' . esc_html__('Keine Press Assets gefunden.', 'biederman') . '</p></div>';
  return;
}

echo '<div ' . $wrapper_attributes . ' class="cards">';
$count = 0;
while ($query->have_posts() && ($limit == -1 || $count < $limit)) {
  $query->the_post();
  get_template_part('template-parts/content', 'press-asset');
  $count++;
}
echo '</div>';
wp_reset_postdata();

