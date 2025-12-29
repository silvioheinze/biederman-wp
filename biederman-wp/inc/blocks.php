<?php
/**
 * Custom Gutenberg Blocks
 */

if (!defined('ABSPATH')) { exit; }

/**
 * Register custom block category early
 * Must be registered before blocks
 */
function biederman_block_categories($categories, $editor_context) {
  return array_merge(
    array(
      array(
        'slug' => 'biederman',
        'title' => __('Biederman', 'biederman'),
        'icon' => 'calendar-alt',
      ),
    ),
    $categories
  );
}
add_filter('block_categories_all', 'biederman_block_categories', 5, 2);

// Include custom block registrations
require_once get_template_directory() . '/inc/blocks/hero-block.php';
require_once get_template_directory() . '/inc/blocks/show-featured-block.php';
require_once get_template_directory() . '/inc/blocks/show-list-block.php';
require_once get_template_directory() . '/inc/blocks/press-assets-block.php';
require_once get_template_directory() . '/inc/blocks/contact-form-block.php';
require_once get_template_directory() . '/inc/blocks/facts-block.php';
require_once get_template_directory() . '/inc/blocks/social-links-block.php';

/**
 * Register custom blocks
 */
function biederman_register_blocks() {
  // Check if Gutenberg is active
  if (!function_exists('register_block_type')) {
    return;
  }

  // Register block styles
  register_block_style('core/group', array(
    'name' => 'hero',
    'label' => __('Hero', 'biederman'),
  ));

  register_block_style('core/group', array(
    'name' => 'section',
    'label' => __('Section', 'biederman'),
  ));

  register_block_style('core/group', array(
    'name' => 'section-alt',
    'label' => __('Section Alt', 'biederman'),
  ));

  register_block_style('core/columns', array(
    'name' => 'cards',
    'label' => __('Cards', 'biederman'),
  ));
}
add_action('init', 'biederman_register_blocks');

/**
 * Enqueue block editor assets
 */
function biederman_block_editor_assets() {
  wp_enqueue_script(
    'biederman-blocks',
    get_template_directory_uri() . '/assets/blocks.js',
    array('wp-blocks', 'wp-element', 'wp-editor', 'wp-components', 'wp-i18n'),
    wp_get_theme()->get('Version'),
    true
  );

  wp_enqueue_style(
    'biederman-blocks-editor',
    get_template_directory_uri() . '/assets/blocks-editor.css',
    array('wp-edit-blocks'),
    wp_get_theme()->get('Version')
  );
}
add_action('enqueue_block_editor_assets', 'biederman_block_editor_assets');

/**
 * Register block patterns
 */
function biederman_register_block_patterns() {
  if (!function_exists('register_block_pattern')) {
    return;
  }

  // Hero Pattern
  register_block_pattern('biederman/hero', array(
    'title' => __('Hero Section', 'biederman'),
    'description' => __('Hero section with tagline, title, and CTA buttons', 'biederman'),
    'content' => '<!-- wp:group {"className":"hero","layout":{"type":"constrained"}} -->
<div class="wp-block-group hero"><!-- wp:paragraph {"className":"kicker"} -->
<p class="kicker">' . esc_html__('Die witzigste generationsübergreifende Band', 'biederman') . '</p>
<!-- /wp:paragraph -->

<!-- wp:heading {"level":1} -->
<h1>' . esc_html__('Biederman', 'biederman') . '</h1>
<!-- /wp:heading -->

<!-- wp:paragraph {"className":"lead"} -->
<p class="lead">' . esc_html__('Live-Shows, Songs und Geschichten zwischen Generationen – mit Humor, Haltung und Herz.', 'biederman') . '</p>
<!-- /wp:paragraph -->

<!-- wp:buttons -->
<div class="wp-block-buttons"><!-- wp:button {"className":"is-style-primary"} -->
<div class="wp-block-button is-style-primary"><a class="wp-block-button__link wp-element-button">' . esc_html__('Nächster Gig', 'biederman') . '</a></div>
<!-- /wp:button -->

<!-- wp:button -->
<div class="wp-block-button"><a class="wp-block-button__link wp-element-button">' . esc_html__('Reinhören', 'biederman') . '</a></div>
<!-- /wp:button --></div>
<!-- /wp:buttons --></div>
<!-- /wp:group -->',
    'categories' => array('featured'),
  ));

  // Shows Section Pattern
  register_block_pattern('biederman/shows-section', array(
    'title' => __('Shows Section', 'biederman'),
    'description' => __('Section displaying shows with featured show card', 'biederman'),
    'content' => '<!-- wp:group {"className":"section","layout":{"type":"constrained"}} -->
<div class="wp-block-group section"><!-- wp:heading {"level":2} -->
<h2>' . esc_html__('Shows', 'biederman') . '</h2>
<!-- /wp:heading -->

<!-- wp:paragraph -->
<p>' . esc_html__('Tickets, Kalender-Export und alle Infos an einem Ort.', 'biederman') . '</p>
<!-- /wp:paragraph -->

<!-- wp:biederman/show-featured /-->

<!-- wp:biederman/show-list {"limit":3} /--></div>
<!-- /wp:group -->',
    'categories' => array('featured'),
  ));

  // Full Front Page Pattern (matches the block template)
  $template_file = get_template_directory() . '/templates/front-page.html.template';
  if (file_exists($template_file)) {
    $template_content = file_get_contents($template_file);
    // Remove the outer main wrapper for pattern use
    $template_content = preg_replace('/^<!-- wp:group.*?<main[^>]*>/s', '', $template_content);
    $template_content = preg_replace('/<\/main>\s*<!-- \/wp:group -->\s*$/s', '', $template_content);
    
    register_block_pattern('biederman/front-page-full', array(
      'title' => __('Full Front Page', 'biederman'),
      'description' => __('Complete front page structure matching the fallback template', 'biederman'),
      'content' => $template_content,
      'categories' => array('featured'),
    ));
  }
}
add_action('init', 'biederman_register_block_patterns');


