<?php
/**
 * Show Featured Block - Server-side rendering
 */

if (!defined('ABSPATH')) { exit; }

// Get block wrapper attributes
global $biederman_current_block;
if (isset($biederman_current_block) && function_exists('get_block_wrapper_attributes')) {
  $wrapper_attributes = get_block_wrapper_attributes();
} else {
  // Fallback if get_block_wrapper_attributes is not available
  $wrapper_attributes = 'class="wp-block-biederman-show-featured"';
}

// First try to find show with show_is_featured = 1
$args = array(
  'post_type' => 'show',
  'posts_per_page' => 1,
  'post_status' => 'publish',
  'meta_query' => array(
    array(
      'key' => 'show_is_featured',
      'value' => '1',
      'compare' => '=',
    ),
  ),
  'orderby' => 'meta_value',
  'meta_key' => 'show_date',
  'order' => 'ASC',
);

$query = new WP_Query($args);

// If no featured show found, fall back to next upcoming show
if (!$query->have_posts()) {
  $args = array(
    'post_type' => 'show',
    'posts_per_page' => 1,
    'post_status' => 'publish',
    'orderby' => 'meta_value',
    'meta_key' => 'show_date',
    'order' => 'ASC',
    'meta_query' => array(
      array(
        'key' => 'show_date',
        'value' => date('Y-m-d H:i:s'),
        'compare' => '>=',
        'type' => 'DATETIME',
      ),
    ),
  );
  $query = new WP_Query($args);
  
  // If still no results (no upcoming shows), get the most recent show
  if (!$query->have_posts()) {
    $args = array(
      'post_type' => 'show',
      'posts_per_page' => 1,
      'post_status' => 'publish',
      'orderby' => 'date',
      'order' => 'DESC',
    );
    $query = new WP_Query($args);
  }
}

if (!$query->have_posts()) {
  echo '<div ' . $wrapper_attributes . '><p class="muted">' . esc_html__('Keine Shows gefunden.', 'biederman') . '</p></div>';
  return;
}

echo '<div ' . $wrapper_attributes . ' class="cards">';
while ($query->have_posts()) {
  $query->the_post();
  get_template_part('template-parts/content', 'show');
}
echo '</div>';
wp_reset_postdata();

