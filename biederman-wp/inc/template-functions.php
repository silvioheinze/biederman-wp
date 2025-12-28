<?php
/**
 * Template helper functions
 */

if (!defined('ABSPATH')) { exit; }

/**
 * Get featured show
 */
function biederman_get_featured_show() {
  $args = array(
    'post_type' => 'show',
    'posts_per_page' => 1,
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
  return $query->have_posts() ? $query->posts[0] : null;
}

/**
 * Get upcoming shows
 */
function biederman_get_upcoming_shows($limit = 5) {
  $args = array(
    'post_type' => 'show',
    'posts_per_page' => $limit,
    'meta_query' => array(
      array(
        'key' => 'show_date',
        'value' => date('Y-m-d'),
        'compare' => '>=',
      ),
    ),
    'orderby' => 'meta_value',
    'meta_key' => 'show_date',
    'order' => 'ASC',
  );

  return new WP_Query($args);
}

/**
 * Get Press Assets by type
 */
function biederman_get_press_assets($type = '') {
  $args = array(
    'post_type' => 'press_asset',
    'posts_per_page' => -1,
    'orderby' => 'date',
    'order' => 'DESC',
  );

  if ($type) {
    $args['meta_query'] = array(
      array(
        'key' => 'press_type',
        'value' => $type,
        'compare' => '=',
      ),
    );
  }

  return new WP_Query($args);
}

/**
 * Format show date
 */
function biederman_format_show_date($date_string) {
  if (empty($date_string)) {
    return '';
  }

  $timestamp = strtotime($date_string);
  if ($timestamp === false) {
    return $date_string;
  }

  return date_i18n(get_option('date_format') . ' ' . get_option('time_format'), $timestamp);
}

