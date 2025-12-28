<?php
/**
 * Custom Post Types: Shows and Press Assets
 */

if (!defined('ABSPATH')) { exit; }

/**
 * Register Show custom post type
 */
function biederman_register_show_post_type() {
  $labels = array(
    'name'                  => _x('Shows', 'Post type general name', 'biederman'),
    'singular_name'         => _x('Show', 'Post type singular name', 'biederman'),
    'menu_name'             => _x('Shows', 'Admin Menu text', 'biederman'),
    'name_admin_bar'        => _x('Show', 'Add New on Toolbar', 'biederman'),
    'add_new'               => __('Add New', 'biederman'),
    'add_new_item'          => __('Add New Show', 'biederman'),
    'new_item'              => __('New Show', 'biederman'),
    'edit_item'             => __('Edit Show', 'biederman'),
    'view_item'             => __('View Show', 'biederman'),
    'all_items'             => __('All Shows', 'biederman'),
    'search_items'          => __('Search Shows', 'biederman'),
    'not_found'             => __('No shows found.', 'biederman'),
    'not_found_in_trash'    => __('No shows found in Trash.', 'biederman'),
    'featured_image'        => _x('Show Image', 'Overrides the "Featured Image" phrase', 'biederman'),
    'set_featured_image'    => _x('Set show image', 'Overrides the "Set featured image" phrase', 'biederman'),
    'remove_featured_image' => _x('Remove show image', 'Overrides the "Remove featured image" phrase', 'biederman'),
    'use_featured_image'    => _x('Use as show image', 'Overrides the "Use as featured image" phrase', 'biederman'),
  );

  $args = array(
    'labels'             => $labels,
    'public'             => true,
    'publicly_queryable' => true,
    'show_ui'            => true,
    'show_in_menu'       => true,
    'query_var'          => true,
    'rewrite'            => array('slug' => 'shows'),
    'capability_type'    => 'post',
    'has_archive'        => true,
    'hierarchical'       => false,
    'menu_position'      => 5,
    'menu_icon'          => 'dashicons-calendar-alt',
    'show_in_rest'       => true, // Enable Gutenberg
    'supports'           => array('title', 'editor', 'thumbnail', 'excerpt', 'custom-fields'),
    'template_lock'      => false,
  );

  register_post_type('show', $args);
}
add_action('init', 'biederman_register_show_post_type');

/**
 * Register Press Asset custom post type
 */
function biederman_register_press_asset_post_type() {
  $labels = array(
    'name'                  => _x('Press Assets', 'Post type general name', 'biederman'),
    'singular_name'         => _x('Press Asset', 'Post type singular name', 'biederman'),
    'menu_name'             => _x('Press Assets', 'Admin Menu text', 'biederman'),
    'name_admin_bar'        => _x('Press Asset', 'Add New on Toolbar', 'biederman'),
    'add_new'               => __('Add New', 'biederman'),
    'add_new_item'          => __('Add New Press Asset', 'biederman'),
    'new_item'              => __('New Press Asset', 'biederman'),
    'edit_item'             => __('Edit Press Asset', 'biederman'),
    'view_item'             => __('View Press Asset', 'biederman'),
    'all_items'             => __('All Press Assets', 'biederman'),
    'search_items'          => __('Search Press Assets', 'biederman'),
    'not_found'             => __('No press assets found.', 'biederman'),
    'not_found_in_trash'    => __('No press assets found in Trash.', 'biederman'),
    'featured_image'        => _x('Press Asset Image', 'Overrides the "Featured Image" phrase', 'biederman'),
    'set_featured_image'    => _x('Set press asset image', 'Overrides the "Set featured image" phrase', 'biederman'),
    'remove_featured_image' => _x('Remove press asset image', 'Overrides the "Remove featured image" phrase', 'biederman'),
    'use_featured_image'    => _x('Use as press asset image', 'Overrides the "Use as featured image" phrase', 'biederman'),
  );

  $args = array(
    'labels'             => $labels,
    'public'             => true,
    'publicly_queryable' => true,
    'show_ui'            => true,
    'show_in_menu'       => true,
    'query_var'          => true,
    'rewrite'            => array('slug' => 'press'),
    'capability_type'    => 'post',
    'has_archive'        => true,
    'hierarchical'       => false,
    'menu_position'      => 6,
    'menu_icon'          => 'dashicons-media-document',
    'show_in_rest'       => true, // Enable Gutenberg
    'supports'           => array('title', 'editor', 'thumbnail', 'excerpt', 'custom-fields'),
    'template_lock'      => false,
  );

  register_post_type('press_asset', $args);
}
add_action('init', 'biederman_register_press_asset_post_type');

/**
 * Add custom meta fields for Shows
 */
function biederman_register_show_meta() {
  register_post_meta('show', 'show_date', array(
    'show_in_rest' => true,
    'single' => true,
    'type' => 'string',
    'sanitize_callback' => 'sanitize_text_field',
  ));

  register_post_meta('show', 'show_location', array(
    'show_in_rest' => true,
    'single' => true,
    'type' => 'string',
    'sanitize_callback' => 'sanitize_text_field',
  ));

  register_post_meta('show', 'show_venue', array(
    'show_in_rest' => true,
    'single' => true,
    'type' => 'string',
    'sanitize_callback' => 'sanitize_text_field',
  ));

  register_post_meta('show', 'show_ticket_url', array(
    'show_in_rest' => true,
    'single' => true,
    'type' => 'string',
    'sanitize_callback' => 'esc_url_raw',
  ));

  register_post_meta('show', 'show_is_featured', array(
    'show_in_rest' => true,
    'single' => true,
    'type' => 'boolean',
    'default' => false,
  ));
}
add_action('init', 'biederman_register_show_meta');

/**
 * Add custom meta fields for Press Assets
 */
function biederman_register_press_asset_meta() {
  register_post_meta('press_asset', 'press_type', array(
    'show_in_rest' => true,
    'single' => true,
    'type' => 'string',
    'sanitize_callback' => 'sanitize_text_field',
  ));

  register_post_meta('press_asset', 'press_download_url', array(
    'show_in_rest' => true,
    'single' => true,
    'type' => 'string',
    'sanitize_callback' => 'esc_url_raw',
  ));

  register_post_meta('press_asset', 'press_file_size', array(
    'show_in_rest' => true,
    'single' => true,
    'type' => 'string',
    'sanitize_callback' => 'sanitize_text_field',
  ));
}
add_action('init', 'biederman_register_press_asset_meta');

/**
 * Add custom columns to Shows admin list
 */
function biederman_add_show_admin_columns($columns) {
  // Insert "Show Date" column after "Title"
  $new_columns = array();
  foreach ($columns as $key => $value) {
    $new_columns[$key] = $value;
    if ($key === 'title') {
      $new_columns['show_date'] = __('Show Date & Time', 'biederman');
      $new_columns['show_venue'] = __('Venue', 'biederman');
      $new_columns['show_is_featured'] = __('Featured', 'biederman');
    }
  }
  return $new_columns;
}
add_filter('manage_show_posts_columns', 'biederman_add_show_admin_columns');

/**
 * Populate custom columns in Shows admin list
 */
function biederman_populate_show_admin_columns($column, $post_id) {
  switch ($column) {
    case 'show_date':
      $show_date = get_post_meta($post_id, 'show_date', true);
      if ($show_date) {
        // Format the date according to WordPress settings
        $date_format = get_option('date_format');
        $time_format = get_option('time_format');
        $formatted_date = date_i18n($date_format . ' ' . $time_format, strtotime($show_date));
        echo esc_html($formatted_date);
      } else {
        echo '<span style="color: #999;">—</span>';
      }
      break;
    
    case 'show_venue':
      $venue = get_post_meta($post_id, 'show_venue', true);
      if ($venue) {
        echo esc_html($venue);
      } else {
        echo '<span style="color: #999;">—</span>';
      }
      break;
    
    case 'show_is_featured':
      $is_featured = get_post_meta($post_id, 'show_is_featured', true);
      if ($is_featured === '1') {
        echo '<span style="color: #2271b1; font-weight: 600;">★ ' . esc_html__('Featured', 'biederman') . '</span>';
      } else {
        echo '<span style="color: #999;">—</span>';
      }
      break;
  }
}
add_action('manage_show_posts_custom_column', 'biederman_populate_show_admin_columns', 10, 2);

/**
 * Make Show Date column sortable
 */
function biederman_make_show_columns_sortable($columns) {
  $columns['show_date'] = 'show_date';
  $columns['show_is_featured'] = 'show_is_featured';
  return $columns;
}
add_filter('manage_edit-show_sortable_columns', 'biederman_make_show_columns_sortable');

/**
 * Handle sorting by show_date
 */
function biederman_sort_shows_by_date($query) {
  if (!is_admin() || !$query->is_main_query()) {
    return;
  }
  
  if ($query->get('post_type') !== 'show') {
    return;
  }
  
  $orderby = $query->get('orderby');
  
  if ($orderby === 'show_date') {
    $query->set('meta_key', 'show_date');
    $query->set('orderby', 'meta_value');
    $query->set('meta_type', 'DATETIME');
  } elseif ($orderby === 'show_is_featured') {
    $query->set('meta_key', 'show_is_featured');
    $query->set('orderby', 'meta_value');
  }
}
add_action('pre_get_posts', 'biederman_sort_shows_by_date');

