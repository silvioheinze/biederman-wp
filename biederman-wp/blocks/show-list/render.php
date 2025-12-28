<?php
/**
 * Show List Block - Server-side rendering
 */

if (!defined('ABSPATH')) { exit; }

$limit = isset($attributes['limit']) ? intval($attributes['limit']) : 5;
$wrapper_attributes = get_block_wrapper_attributes();

// First, check if there's a featured show
$featured_args = array(
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
);

$featured_query = new WP_Query($featured_args);
$exclude_ids = array();

// If a featured show exists, exclude it from the list
if ($featured_query->have_posts()) {
  while ($featured_query->have_posts()) {
    $featured_query->the_post();
    $exclude_ids[] = get_the_ID();
  }
  wp_reset_postdata();
} else {
  // If no featured show, check for next upcoming show (same logic as show-featured block)
  $upcoming_args = array(
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
  $upcoming_query = new WP_Query($upcoming_args);
  
  if ($upcoming_query->have_posts()) {
    while ($upcoming_query->have_posts()) {
      $upcoming_query->the_post();
      $exclude_ids[] = get_the_ID();
    }
    wp_reset_postdata();
  } else {
    // If no upcoming show, exclude the most recent show (fallback in show-featured block)
    $recent_args = array(
      'post_type' => 'show',
      'posts_per_page' => 1,
      'post_status' => 'publish',
      'orderby' => 'date',
      'order' => 'DESC',
    );
    $recent_query = new WP_Query($recent_args);
    
    if ($recent_query->have_posts()) {
      while ($recent_query->have_posts()) {
        $recent_query->the_post();
        $exclude_ids[] = get_the_ID();
      }
      wp_reset_postdata();
    }
  }
}

// Regular shows: show all shows, excluding featured show
// Order by show_date if available, otherwise by post date
$args = array(
  'post_type' => 'show',
  'posts_per_page' => $limit,
  'post_status' => 'publish',
  'orderby' => 'meta_value',
  'meta_key' => 'show_date',
  'order' => 'ASC',
);

// Exclude featured show if it exists
if (!empty($exclude_ids)) {
  $args['post__not_in'] = $exclude_ids;
}

$query = new WP_Query($args);

// Render as a single panel with list format (matching original design)
echo '<article ' . $wrapper_attributes . ' class="panel" aria-label="' . esc_attr__('Weitere Termine', 'biederman') . '">';
echo '<h3>' . esc_html__('Weitere Termine', 'biederman') . '</h3>';

if (!$query->have_posts()) {
  echo '<p class="muted">' . esc_html__('Hier kommen die nächsten Dates rein.', 'biederman') . '</p>';
  echo '</article>';
  return;
}

echo '<ul class="list">';

while ($query->have_posts()) {
  $query->the_post();
  
  $show_date = get_post_meta(get_the_ID(), 'show_date', true);
  $show_location = get_post_meta(get_the_ID(), 'show_location', true);
  $show_venue = get_post_meta(get_the_ID(), 'show_venue', true);
  
  // Format the date (simpler format for list)
  $date_text = '';
  if ($show_date) {
    $timestamp = strtotime($show_date);
    if ($timestamp !== false) {
      // Format as "Fr., 20.02.2026" (German short date format)
      $date_text = '<strong>' . esc_html(date_i18n('D, d.m.Y', $timestamp)) . '</strong>';
    } else {
      $date_text = '<strong>' . esc_html(get_the_title()) . '</strong>';
    }
  } else {
    $date_text = '<strong>' . esc_html(get_the_title()) . '</strong>';
  }
  
  // Build venue text (only venue name, not address)
  $venue_text = '';
  if ($show_venue) {
    $venue_text = ' · ' . esc_html($show_venue);
  }
  
  // Determine pill text (TBA if no date)
  $pill_text = $show_date ? '' : 'TBA';
  
  echo '<li>';
  if ($pill_text) {
    echo '<span class="pill">' . esc_html($pill_text) . '</span> ';
  }
  echo $date_text . $venue_text;
  echo '</li>';
}

echo '</ul>';
echo '</article>';
wp_reset_postdata();

