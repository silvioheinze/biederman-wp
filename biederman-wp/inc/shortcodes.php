<?php
/**
 * Shortcodes for displaying Shows and Press Assets
 */

if (!defined('ABSPATH')) { exit; }

/**
 * Shortcode: Display shows
 * Usage: [shows limit="5" featured="true"]
 */
function biederman_shows_shortcode($atts) {
  $atts = shortcode_atts(array(
    'limit' => 5,
    'featured' => 'false',
  ), $atts);

  $args = array(
    'post_type' => 'show',
    'posts_per_page' => intval($atts['limit']),
    'orderby' => 'meta_value',
    'meta_key' => 'show_date',
    'order' => 'ASC',
  );

  if ($atts['featured'] === 'true') {
    $args['meta_query'] = array(
      array(
        'key' => 'show_is_featured',
        'value' => '1',
        'compare' => '=',
      ),
    );
  } else {
    $args['meta_query'] = array(
      array(
        'key' => 'show_date',
        'value' => date('Y-m-d'),
        'compare' => '>=',
      ),
    );
  }

  $query = new WP_Query($args);
  
  if (!$query->have_posts()) {
    return '<p class="muted">' . esc_html__('Keine Shows gefunden.', 'biederman') . '</p>';
  }

  ob_start();
  echo '<div class="cards">';
  while ($query->have_posts()) {
    $query->the_post();
    get_template_part('template-parts/content', 'show');
  }
  echo '</div>';
  wp_reset_postdata();
  
  return ob_get_clean();
}
add_shortcode('shows', 'biederman_shows_shortcode');

/**
 * Shortcode: Display Press Assets
 * Usage: [press_assets type="photo"]
 */
function biederman_press_assets_shortcode($atts) {
  $atts = shortcode_atts(array(
    'type' => '',
    'limit' => -1,
  ), $atts);

  $query = biederman_get_press_assets($atts['type']);
  
  if (!$query->have_posts()) {
    return '<p class="muted">' . esc_html__('Keine Press Assets gefunden.', 'biederman') . '</p>';
  }

  ob_start();
  echo '<div class="cards">';
  $count = 0;
  while ($query->have_posts() && ($atts['limit'] == -1 || $count < intval($atts['limit']))) {
    $query->the_post();
    get_template_part('template-parts/content', 'press-asset');
    $count++;
  }
  echo '</div>';
  wp_reset_postdata();
  
  return ob_get_clean();
}
add_shortcode('press_assets', 'biederman_press_assets_shortcode');

/**
 * Shortcode: Display customizer tagline
 * Usage: [tagline]
 */
function biederman_tagline_shortcode() {
  return esc_html(get_theme_mod('biederman_tagline', 'Die witzigste generationsübergreifende Band'));
}
add_shortcode('tagline', 'biederman_tagline_shortcode');

/**
 * Shortcode: Display customizer lead text
 * Usage: [lead]
 */
function biederman_lead_shortcode() {
  return esc_html(get_theme_mod('biederman_lead', 'Live-Shows, Songs und Geschichten zwischen Generationen – mit Humor, Haltung und Herz.'));
}
add_shortcode('lead', 'biederman_lead_shortcode');

/**
 * Shortcode: Display site name
 * Usage: [site_name]
 */
function biederman_site_name_shortcode() {
  return esc_html(get_bloginfo('name', 'display'));
}
add_shortcode('site_name', 'biederman_site_name_shortcode');

/**
 * Shortcode: Display booking email
 * Usage: [booking_email]
 */
function biederman_booking_email_shortcode() {
  $email = get_theme_mod('biederman_booking_email', 'booking@biederman.band');
  return esc_html($email);
}
add_shortcode('booking_email', 'biederman_booking_email_shortcode');

/**
 * Shortcode: Display social links
 * Usage: [social_links]
 */
function biederman_social_links_shortcode() {
  $instagram = get_theme_mod('biederman_instagram', '');
  $youtube   = get_theme_mod('biederman_youtube', '');
  $tiktok    = get_theme_mod('biederman_tiktok', '');
  $facebook  = get_theme_mod('biederman_facebook', '');
  
  ob_start();
  echo '<div class="links links--social" aria-label="Social Links">';
  if ($instagram) {
    echo '<a href="' . esc_url($instagram) . '" target="_blank" rel="noreferrer">Instagram</a>';
  }
  if ($tiktok) {
    echo '<a href="' . esc_url($tiktok) . '" target="_blank" rel="noreferrer">TikTok</a>';
  }
  if ($youtube) {
    echo '<a href="' . esc_url($youtube) . '" target="_blank" rel="noreferrer">YouTube</a>';
  }
  if ($facebook) {
    echo '<a href="' . esc_url($facebook) . '" target="_blank" rel="noreferrer">Facebook</a>';
  }
  if (!$instagram && !$tiktok && !$youtube && !$facebook) {
    echo '<a href="#" aria-disabled="true">Instagram</a>';
    echo '<a href="#" aria-disabled="true">TikTok</a>';
    echo '<a href="#" aria-disabled="true">YouTube</a>';
    echo '<a href="#" aria-disabled="true">Facebook</a>';
  }
  echo '</div>';
  return ob_get_clean();
}
add_shortcode('social_links', 'biederman_social_links_shortcode');

/**
 * Shortcode: Display booking email link
 * Usage: [booking_email_link]
 */
function biederman_booking_email_link_shortcode() {
  $email = get_theme_mod('biederman_booking_email', 'booking@biederman.band');
  $subject = rawurlencode('Booking Anfrage – Biederman');
  
  ob_start();
  echo '<div class="contact__row">';
  echo '<a class="button primary" id="booking-email" data-email="' . esc_attr($email) . '" href="' . esc_url('mailto:' . $email . '?subject=' . $subject) . '">' . esc_html($email) . '</a>';
  echo '<button class="button" id="btn-copy-mail" type="button">Mail kopieren</button>';
  echo '</div>';
  echo '<p class="small muted" id="mail-msg" role="status" aria-live="polite"></p>';
  return ob_get_clean();
}
add_shortcode('booking_email_link', 'biederman_booking_email_link_shortcode');

