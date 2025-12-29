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
 * Register block pattern category
 */
function biederman_register_block_pattern_category() {
  if (!function_exists('register_block_pattern_category')) {
    return;
  }
  
  register_block_pattern_category('biederman', array(
    'label' => __('Biederman', 'biederman'),
  ));
}
add_action('init', 'biederman_register_block_pattern_category');

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
    'categories' => array('biederman', 'featured'),
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
    'categories' => array('biederman', 'featured'),
  ));

  // Front Page Template Pattern (static - embedded in theme code)
  // This pattern is always available, even if templates/front-page.html doesn't exist
  register_block_pattern('biederman/front-page-template', array(
    'title' => __('Front Page Template', 'biederman'),
    'description' => __('Complete front page structure with hero, shows, media, about, press, contact and newsletter sections', 'biederman'),
    'content' => '<!-- wp:biederman/hero {"lead":"Das ist ein neuer Leadtext.","imageId":41,"imageUrl":"http://localhost:8080/wp-content/uploads/2025/12/biederman-hp.png"} /-->

<!-- wp:group {"className":"section","layout":{"type":"constrained"}} -->
<div id="shows" class="wp-block-group section">
<!-- wp:group {"className":"container","layout":{"type":"constrained"}} -->
<div class="wp-block-group container">
<!-- wp:group {"className":"section__head","layout":{"type":"constrained"}} -->
<div class="wp-block-group section__head">
<!-- wp:heading -->
<h2 class="wp-block-heading">Shows</h2>
<!-- /wp:heading -->

<!-- wp:paragraph -->
<p>Tickets, Kalender-Export und alle Infos an einem Ort.</p>
<!-- /wp:paragraph -->

<!-- wp:columns -->
<div class="wp-block-columns">
<!-- wp:column {"width":"66.66%"} -->
<div class="wp-block-column" style="flex-basis:66.66%">
<!-- wp:biederman/show-featured /-->
</div>
<!-- /wp:column -->

<!-- wp:column {"width":"33.33%"} -->
<div class="wp-block-column" style="flex-basis:33.33%">
<!-- wp:biederman/show-list {"limit":3} /-->
</div>
<!-- /wp:column -->
</div>
<!-- /wp:columns -->
</div>
<!-- /wp:group -->
</div>
<!-- /wp:group -->
</div>
<!-- /wp:group -->

<!-- wp:group {"className":"section section--alt","layout":{"type":"constrained"}} -->
<div id="media" class="wp-block-group section section--alt">
<!-- wp:group {"className":"container","layout":{"type":"constrained"}} -->
<div class="wp-block-group container">
<!-- wp:group {"className":"section__head","layout":{"type":"constrained"}} -->
<div class="wp-block-group section__head">
<!-- wp:heading -->
<h2 class="wp-block-heading">Media</h2>
<!-- /wp:heading -->

<!-- wp:paragraph -->
<p>Ein Platz für euer neuestes Video, Live-Mitschnitte oder Playlist-Embeds.</p>
<!-- /wp:paragraph -->
</div>
<!-- /wp:group -->

<!-- wp:group {"className":"media","layout":{"type":"default"}} -->
<div class="wp-block-group media">
<!-- wp:columns -->
<div class="wp-block-columns">
<!-- wp:column {"width":"66.66%"} -->
<div class="wp-block-column" style="flex-basis:66.66%">
<!-- wp:embed {"url":"https://www.youtube.com/watch?v=a7TnbwUjYqo","type":"video","providerNameSlug":"youtube","responsive":true,"className":"wp-embed-aspect-16-9 wp-has-aspect-ratio"} -->
<figure class="wp-block-embed is-type-video is-provider-youtube wp-block-embed-youtube wp-embed-aspect-16-9 wp-has-aspect-ratio"><div class="wp-block-embed__wrapper">
https://www.youtube.com/watch?v=a7TnbwUjYqo
</div></figure>
<!-- /wp:embed -->
</div>
<!-- /wp:column -->

<!-- wp:column {"className":"panel","layout":{"type":"constrained","justifyContent":"right"}} -->
<div class="wp-block-column panel">
<!-- wp:heading {"level":3} -->
<h3 class="wp-block-heading">Streaming</h3>
<!-- /wp:heading -->

<!-- wp:paragraph {"className":"muted"} -->
<p class="muted">Links zu Spotify, Apple Music, Bandcamp &amp; Co.</p>
<!-- /wp:paragraph -->

<!-- wp:paragraph {"className":"links"} -->
<p class="links">
<a href="#" aria-disabled="true">Spotify</a>
<a href="#" aria-disabled="true">Apple Music</a>
<a href="#" aria-disabled="true">Bandcamp</a>
<a href="#" aria-disabled="true">YouTube</a>
</p>
<!-- /wp:paragraph -->
</div>
<!-- /wp:column -->
</div>
<!-- /wp:columns -->
</div>
<!-- /wp:group -->
</div>
<!-- /wp:group -->
</div>
<!-- /wp:group -->

<!-- wp:group {"className":"section","layout":{"type":"constrained"}} -->
<div id="about" class="wp-block-group section">
<!-- wp:group {"className":"container","layout":{"type":"constrained"}} -->
<div class="wp-block-group container">
<!-- wp:group {"className":"section__head","layout":{"type":"constrained"}} -->
<div class="wp-block-group section__head">
<!-- wp:heading -->
<h2 class="wp-block-heading">Über uns</h2>
<!-- /wp:heading -->

<!-- wp:paragraph -->
<p>Kurzer Pitch + was euch als Band ausmacht (für Fans, Presse &amp; Booker).</p>
<!-- /wp:paragraph -->
</div>
<!-- /wp:group -->

<!-- wp:columns {"className":"cols"} -->
<div class="wp-block-columns cols">
<!-- wp:column -->
<div class="wp-block-column">
<!-- wp:paragraph -->
<p><strong>Biederman</strong> verbindet Generationen auf der Bühne: Songs, Pointen und Popkultur – mit einem Blick nach vorne und einem liebevollen Augenzwinkern zurück.</p>
<!-- /wp:paragraph -->
</div>
<!-- /wp:column -->

<!-- wp:column -->
<div class="wp-block-column">
<!-- wp:biederman/facts /-->
</div>
<!-- /wp:column -->
</div>
<!-- /wp:columns -->
</div>
<!-- /wp:group -->
</div>
<!-- /wp:group -->

<!-- wp:group {"className":"section section--alt","layout":{"type":"constrained"}} -->
<div id="press" class="wp-block-group section section--alt">
<!-- wp:group {"className":"container","layout":{"type":"constrained"}} -->
<div class="wp-block-group container">
<!-- wp:group {"className":"section__head","layout":{"type":"constrained"}} -->
<div class="wp-block-group section__head">
<!-- wp:heading -->
<h2 class="wp-block-heading">Presse</h2>
<!-- /wp:heading -->

<!-- wp:paragraph -->
<p>Alles, was Medien &amp; Veranstalter schnell brauchen.</p>
<!-- /wp:paragraph -->
</div>
<!-- /wp:group -->

<!-- wp:columns -->
<div class="wp-block-columns">
<!-- wp:column {"width":"66.66%"} -->
<div class="wp-block-column" style="flex-basis:66.66%">
<!-- wp:heading {"level":3} -->
<h3 class="wp-block-heading">Pressetext (kurz)</h3>
<!-- /wp:heading -->

<!-- wp:paragraph {"className":"muted"} -->
<p class="muted">Ein Absatz, der sofort erklärt, wer ihr seid, wie es klingt/ist und warum das Publikum kommen sollte.</p>
<!-- /wp:paragraph -->
</div>
<!-- /wp:column -->

<!-- wp:column {"width":"33.33%"} -->
<div class="wp-block-column" style="flex-basis:33.33%">
<!-- wp:biederman/press-assets /-->
</div>
<!-- /wp:column -->
</div>
<!-- /wp:columns -->
</div>
<!-- /wp:group -->
</div>
<!-- /wp:group -->

<!-- wp:group {"className":"section","layout":{"type":"constrained"}} -->
<div id="contact" class="wp-block-group section">
<!-- wp:group {"className":"container","layout":{"type":"constrained","justifyContent":"left"}} -->
<div class="wp-block-group container">
<!-- wp:group {"className":"section__head","layout":{"type":"constrained"}} -->
<div class="wp-block-group section__head">
<!-- wp:heading -->
<h2 class="wp-block-heading">Booking &amp; Kontakt</h2>
<!-- /wp:heading -->
</div>
<!-- /wp:group -->

<!-- wp:columns -->
<div class="wp-block-columns">
<!-- wp:column -->
<div class="wp-block-column">
<!-- wp:biederman/contact-form /-->
</div>
<!-- /wp:column -->

<!-- wp:column -->
<div class="wp-block-column">
<!-- wp:heading {"level":3} -->
<h3 class="wp-block-heading">Social Media</h3>
<!-- /wp:heading -->

<!-- wp:biederman/social-links /-->
</div>
<!-- /wp:column -->
</div>
<!-- /wp:columns -->
</div>
<!-- /wp:group -->
</div>
<!-- /wp:group -->

<!-- wp:group {"className":"section section--alt","layout":{"type":"constrained"}} -->
<div id="newsletter" class="wp-block-group section section--alt">
<!-- wp:group {"className":"container","layout":{"type":"constrained"}} -->
<div class="wp-block-group container">
<!-- wp:group {"className":"section__head","layout":{"type":"constrained"}} -->
<div class="wp-block-group section__head">
<!-- wp:heading -->
<h2 class="wp-block-heading">Newsletter</h2>
<!-- /wp:heading -->

<!-- wp:paragraph -->
<p>Updates zu Shows, Releases und exklusiven Dingen.</p>
<!-- /wp:paragraph -->
</div>
<!-- /wp:group -->

<!-- wp:mailpoet/subscription-form-block {"formId":1} /-->
</div>
<!-- /wp:group -->
</div>
<!-- /wp:group -->',
    'categories' => array('biederman', 'featured'),
  ));
}
add_action('init', 'biederman_register_block_patterns');


