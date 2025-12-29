<?php
/**
 * Hero Block - Server-side rendering
 */

if (!defined('ABSPATH')) { exit; }

// Get block wrapper attributes
global $biederman_current_block;
if (isset($biederman_current_block) && function_exists('get_block_wrapper_attributes')) {
  $wrapper_attributes = get_block_wrapper_attributes();
} else {
  // Fallback if get_block_wrapper_attributes is not available
  $wrapper_attributes = 'class="wp-block-biederman-hero"';
}

$tagline = isset($attributes['tagline']) ? $attributes['tagline'] : '';
$title = isset($attributes['title']) ? $attributes['title'] : '';
$lead = isset($attributes['lead']) ? $attributes['lead'] : '';
$primary_button_text = isset($attributes['primaryButtonText']) ? $attributes['primaryButtonText'] : 'Nächster Gig';
$primary_button_link = isset($attributes['primaryButtonLink']) ? $attributes['primaryButtonLink'] : '#shows';
$secondary_button_text = isset($attributes['secondaryButtonText']) ? $attributes['secondaryButtonText'] : 'Reinhören';
$secondary_button_link = isset($attributes['secondaryButtonLink']) ? $attributes['secondaryButtonLink'] : '#media';
$chips = isset($attributes['chips']) && is_array($attributes['chips']) ? $attributes['chips'] : array();
$image_id = isset($attributes['imageId']) ? intval($attributes['imageId']) : 0;
$image_url = isset($attributes['imageUrl']) ? $attributes['imageUrl'] : '';
$image_alt = isset($attributes['imageAlt']) ? $attributes['imageAlt'] : '';
$image_caption = isset($attributes['imageCaption']) ? $attributes['imageCaption'] : '';

// If no title provided, use site name
if (empty($title)) {
  $title = get_bloginfo('name', 'display');
}

// If no tagline provided, use customizer value
if (empty($tagline)) {
  $tagline = get_theme_mod('biederman_tagline', 'Die witzigste generationsübergreifende Band');
}

// If no lead provided, use customizer value
if (empty($lead)) {
  $lead = get_theme_mod('biederman_lead', 'Live-Shows, Songs und Geschichten zwischen Generationen – mit Humor, Haltung und Herz.');
}

// Get image if imageId is set
if ($image_id > 0) {
  $image = wp_get_attachment_image_src($image_id, 'full');
  if ($image) {
    $image_url = $image[0];
    if (empty($image_alt)) {
      $image_alt = get_post_meta($image_id, '_wp_attachment_image_alt', true);
    }
  }
}

?>
<div <?php echo $wrapper_attributes; ?> id="hero" class="hero" aria-label="Hero">
  <div class="container hero__grid">
    <div class="hero__copy">
      <?php if (!empty($tagline)): ?>
        <p class="kicker"><?php echo esc_html($tagline); ?></p>
      <?php endif; ?>
      
      <?php if (!empty($title)): ?>
        <h1><?php echo esc_html($title); ?></h1>
      <?php endif; ?>
      
      <?php if (!empty($lead)): ?>
        <p class="lead"><?php echo esc_html($lead); ?></p>
      <?php endif; ?>
      
      <div class="hero__actions">
        <?php if (!empty($primary_button_text)): ?>
          <a class="button primary" href="<?php echo esc_url($primary_button_link); ?>"><?php echo esc_html($primary_button_text); ?></a>
        <?php endif; ?>
        <?php if (!empty($secondary_button_text)): ?>
          <a class="button" href="<?php echo esc_url($secondary_button_link); ?>"><?php echo esc_html($secondary_button_text); ?></a>
        <?php endif; ?>
      </div>
      
      <?php if (!empty($chips)): ?>
        <ul class="chips" aria-label="Kurzinfo">
          <?php foreach ($chips as $chip): ?>
            <?php if (!empty($chip)): ?>
              <li><?php echo esc_html($chip); ?></li>
            <?php endif; ?>
          <?php endforeach; ?>
        </ul>
      <?php endif; ?>
    </div>
    
    <?php if (!empty($image_url)): ?>
      <figure class="hero__art">
        <img src="<?php echo esc_url($image_url); ?>" 
             alt="<?php echo esc_attr($image_alt); ?>" 
             loading="eager" />
        <?php if (!empty($image_caption)): ?>
          <figcaption><?php echo esc_html($image_caption); ?></figcaption>
        <?php endif; ?>
      </figure>
    <?php endif; ?>
  </div>
</div>

