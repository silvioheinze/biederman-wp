<?php
/**
 * Theme functions.
 */

if (!defined('ABSPATH')) { exit; }

// Include custom post types
require_once get_template_directory() . '/inc/post-types.php';

// Include custom blocks
require_once get_template_directory() . '/inc/blocks.php';

// Include template functions
require_once get_template_directory() . '/inc/template-functions.php';

// Include shortcodes
require_once get_template_directory() . '/inc/shortcodes.php';

// Include meta boxes
require_once get_template_directory() . '/inc/meta-boxes.php';

// Include block template hooks
require_once get_template_directory() . '/inc/block-template-hooks.php';

/**
 * Setup theme defaults.
 */
function biederman_setup() {
  add_theme_support('title-tag');
  add_theme_support('post-thumbnails');
  add_theme_support('html5', array('search-form','comment-form','comment-list','gallery','caption','style','script'));
  add_theme_support('responsive-embeds');
  
  // Gutenberg support
  add_theme_support('wp-block-styles');
  add_theme_support('align-wide');
  add_theme_support('editor-styles');
  add_editor_style('assets/editor-styles.css');
  
  // Block templates
  add_theme_support('block-templates');
  
  // Enable custom fields in block editor
  add_post_type_support('show', 'custom-fields');
  add_post_type_support('press_asset', 'custom-fields');
  
  register_nav_menus(array(
    'primary' => __('Primary Menu', 'biederman'),
  ));
}
add_action('after_setup_theme', 'biederman_setup');

/**
 * Enqueue assets.
 */
function biederman_assets() {
  $ver = wp_get_theme()->get('Version');
  wp_enqueue_style('biederman-css', get_template_directory_uri() . '/assets/styles.css', array(), $ver);
  wp_enqueue_script('biederman-js', get_template_directory_uri() . '/assets/script.js', array(), $ver, true);

  // Pass “Next show” data into JS (used for ICS download and copy actions)
  $event = array(
    'title' => get_theme_mod('biederman_event_title', 'Biederman – ReleaseParty'),
    'startISO' => get_theme_mod('biederman_event_start_iso', '2026-02-20T20:00:00+01:00'),
    'durationMinutes' => (int) get_theme_mod('biederman_event_duration', 150),
    'location' => get_theme_mod('biederman_event_location', 'Loop, Gürtelbogen 26, 1080 Wien'),
    'description' => get_theme_mod('biederman_event_description', 'Biederman live im Loop (Wien).'),
    'url' => home_url('/#shows'),
  );
  wp_localize_script('biederman-js', 'BIEDERMAN_EVENT', $event);
}
add_action('wp_enqueue_scripts', 'biederman_assets');

/**
 * Customizer settings (simple, no plugins required).
 */
function biederman_customize_register($wp_customize) {
  // Section: Band
  $wp_customize->add_section('biederman_band', array(
    'title' => __('Biederman: Band', 'biederman'),
    'priority' => 30,
  ));

  $wp_customize->add_setting('biederman_tagline', array(
    'default' => 'Die witzigste generationsübergreifende Band',
    'sanitize_callback' => 'sanitize_text_field',
  ));
  $wp_customize->add_control('biederman_tagline', array(
    'label' => __('Tagline', 'biederman'),
    'section' => 'biederman_band',
    'type' => 'text',
  ));

  $wp_customize->add_setting('biederman_lead', array(
    'default' => 'Live-Shows, Songs und Geschichten zwischen Generationen – mit Humor, Haltung und Herz.',
    'sanitize_callback' => 'sanitize_textarea_field',
  ));
  $wp_customize->add_control('biederman_lead', array(
    'label' => __('Intro Text', 'biederman'),
    'section' => 'biederman_band',
    'type' => 'textarea',
  ));

  // Section: Next Event
  $wp_customize->add_section('biederman_event', array(
    'title' => __('Biederman: Next Show', 'biederman'),
    'priority' => 31,
  ));

  $wp_customize->add_setting('biederman_event_title', array('default'=>'ReleaseParty','sanitize_callback'=>'sanitize_text_field'));
  $wp_customize->add_control('biederman_event_title', array('label'=>__('Event Title','biederman'),'section'=>'biederman_event','type'=>'text'));

  $wp_customize->add_setting('biederman_event_start_iso', array('default'=>'2026-02-20T20:00:00+01:00','sanitize_callback'=>'sanitize_text_field'));
  $wp_customize->add_control('biederman_event_start_iso', array(
    'label'=>__('Start (ISO 8601, incl. timezone)','biederman'),
    'section'=>'biederman_event',
    'type'=>'text',
    'description'=>__('Example: 2026-02-20T20:00:00+01:00', 'biederman')
  ));

  $wp_customize->add_setting('biederman_event_duration', array('default'=>150,'sanitize_callback'=>'absint'));
  $wp_customize->add_control('biederman_event_duration', array('label'=>__('Duration (minutes)','biederman'),'section'=>'biederman_event','type'=>'number'));

  $wp_customize->add_setting('biederman_event_location', array('default'=>'Loop, Gürtelbogen 26, 1080 Wien','sanitize_callback'=>'sanitize_text_field'));
  $wp_customize->add_control('biederman_event_location', array('label'=>__('Location (one line)','biederman'),'section'=>'biederman_event','type'=>'text'));

  $wp_customize->add_setting('biederman_event_description', array('default'=>'Biederman live im Loop (Wien).','sanitize_callback'=>'sanitize_textarea_field'));
  $wp_customize->add_control('biederman_event_description', array('label'=>__('Event description','biederman'),'section'=>'biederman_event','type'=>'textarea'));

  // Section: Links
  $wp_customize->add_section('biederman_links', array(
    'title' => __('Biederman: Links', 'biederman'),
    'priority' => 32,
  ));

  $wp_customize->add_setting('biederman_booking_email', array('default'=>'booking@biederman.band','sanitize_callback'=>'sanitize_email'));
  $wp_customize->add_control('biederman_booking_email', array('label'=>__('Booking email','biederman'),'section'=>'biederman_links','type'=>'text'));

  $wp_customize->add_setting('biederman_instagram', array('default'=>'','sanitize_callback'=>'esc_url_raw'));
  $wp_customize->add_control('biederman_instagram', array('label'=>__('Instagram URL','biederman'),'section'=>'biederman_links','type'=>'url'));

  $wp_customize->add_setting('biederman_youtube', array('default'=>'','sanitize_callback'=>'esc_url_raw'));
  $wp_customize->add_control('biederman_youtube', array('label'=>__('YouTube URL','biederman'),'section'=>'biederman_links','type'=>'url'));

  $wp_customize->add_setting('biederman_tiktok', array('default'=>'','sanitize_callback'=>'esc_url_raw'));
  $wp_customize->add_control('biederman_tiktok', array('label'=>__('TikTok URL','biederman'),'section'=>'biederman_links','type'=>'url'));

  $wp_customize->add_setting('biederman_facebook', array('default'=>'','sanitize_callback'=>'esc_url_raw'));
  $wp_customize->add_control('biederman_facebook', array('label'=>__('Facebook URL','biederman'),'section'=>'biederman_links','type'=>'url'));

  // Section: Navigation
  $wp_customize->add_section('biederman_navigation', array(
    'title' => __('Biederman: Navigation', 'biederman'),
    'priority' => 33,
  ));

  $wp_customize->add_setting('biederman_show_navigation', array(
    'default' => true,
    'sanitize_callback' => 'rest_sanitize_boolean',
  ));
  $wp_customize->add_control('biederman_show_navigation', array(
    'label' => __('Show navigation menu', 'biederman'),
    'section' => 'biederman_navigation',
    'type' => 'checkbox',
    'description' => __('Uncheck to hide the top navigation menu', 'biederman'),
  ));
}
add_action('customize_register', 'biederman_customize_register');
