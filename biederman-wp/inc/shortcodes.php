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
  echo '<ul class="list" style="list-style: none; padding-left: 0;">';
  $count = 0;
  while ($query->have_posts() && ($atts['limit'] == -1 || $count < intval($atts['limit']))) {
    $query->the_post();
    
    $press_type = get_post_meta(get_the_ID(), 'press_type', true);
    $press_download_url = get_post_meta(get_the_ID(), 'press_download_url', true);
    $press_file_size = get_post_meta(get_the_ID(), 'press_file_size', true);
    
    echo '<li style="margin-bottom: 1rem; padding-bottom: 1rem; border-bottom: 1px solid var(--line);">';
    
    // Title and type
    echo '<div style="display: flex; align-items: center; gap: 0.5rem; margin-bottom: 0.5rem;">';
    if ($press_type) {
      echo '<span class="pill">' . esc_html($press_type) . '</span>';
    }
    echo '<strong>' . esc_html(get_the_title()) . '</strong>';
    echo '</div>';
    
    // Description
    if (has_excerpt()) {
      echo '<p style="margin: 0 0 0.5rem; color: rgba(243,245,247,.88);">' . get_the_excerpt() . '</p>';
    }
    
    // Download link
    if ($press_download_url) {
      $download_text = esc_html__('Download', 'biederman');
      if ($press_file_size) {
        $download_text .= ' (' . esc_html($press_file_size) . ')';
      }
      echo '<a class="button" href="' . esc_url($press_download_url) . '" download style="display: inline-block;">' . $download_text . '</a>';
    }
    
    echo '</li>';
    $count++;
  }
  echo '</ul>';
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
  
  // SVG Icons for social media platforms
  $icons = array(
    'instagram' => '<svg width="20" height="20" viewBox="0 0 24 24" fill="currentColor" xmlns="http://www.w3.org/2000/svg"><path d="M12 2.163c3.204 0 3.584.012 4.85.07 3.252.148 4.771 1.691 4.919 4.919.058 1.265.069 1.645.069 4.849 0 3.205-.012 3.584-.069 4.849-.149 3.225-1.664 4.771-4.919 4.919-1.266.058-1.644.07-4.85.07-3.204 0-3.584-.012-4.849-.07-3.26-.149-4.771-1.699-4.919-4.92-.058-1.265-.07-1.644-.07-4.849 0-3.204.013-3.583.07-4.849.149-3.227 1.664-4.771 4.919-4.919 1.266-.057 1.645-.069 4.849-.069zm0-2.163c-3.259 0-3.667.014-4.947.072-4.358.2-6.78 2.618-6.98 6.98-.059 1.281-.073 1.689-.073 4.948 0 3.259.014 3.668.072 4.948.2 4.358 2.618 6.78 6.98 6.98 1.281.058 1.689.072 4.948.072 3.259 0 3.668-.014 4.948-.072 4.354-.2 6.782-2.618 6.979-6.98.059-1.28.073-1.689.073-4.948 0-3.259-.014-3.667-.072-4.947-.196-4.354-2.617-6.78-6.979-6.98-1.281-.059-1.69-.073-4.949-.073zm0 5.838c-3.403 0-6.162 2.759-6.162 6.162s2.759 6.163 6.162 6.163 6.162-2.759 6.162-6.163c0-3.403-2.759-6.162-6.162-6.162zm0 10.162c-2.209 0-4-1.79-4-4 0-2.209 1.791-4 4-4s4 1.791 4 4c0 2.21-1.791 4-4 4zm6.406-11.845c-.796 0-1.441.645-1.441 1.44s.645 1.44 1.441 1.44c.795 0 1.439-.645 1.439-1.44s-.644-1.44-1.439-1.44z"/></svg>',
    'youtube' => '<svg width="20" height="20" viewBox="0 0 24 24" fill="currentColor" xmlns="http://www.w3.org/2000/svg"><path d="M23.498 6.186a3.016 3.016 0 0 0-2.122-2.136C19.505 3.545 12 3.545 12 3.545s-7.505 0-9.377.505A3.017 3.017 0 0 0 .502 6.186C0 8.07 0 12 0 12s0 3.93.502 5.814a3.016 3.016 0 0 0 2.122 2.136c1.871.505 9.376.505 9.376.505s7.505 0 9.377-.505a3.015 3.015 0 0 0 2.122-2.136C24 15.93 24 12 24 12s0-3.93-.502-5.814zM9.545 15.568V8.432L15.818 12l-6.273 3.568z"/></svg>',
    'tiktok' => '<svg width="20" height="20" viewBox="0 0 24 24" fill="currentColor" xmlns="http://www.w3.org/2000/svg"><path d="M19.59 6.69a4.83 4.83 0 0 1-3.77-4.25V2h-3.45v13.67a2.89 2.89 0 0 1-5.2 1.74 2.89 2.89 0 0 1 2.31-4.64 2.93 2.93 0 0 1 .88.13V9.4a6.84 6.84 0 0 0-1-.05A6.33 6.33 0 0 0 5 20.1a6.34 6.34 0 0 0 10.86-4.43v-7a8.16 8.16 0 0 0 4.77 1.52v-3.4a4.85 4.85 0 0 1-1-.1z"/></svg>',
    'facebook' => '<svg width="20" height="20" viewBox="0 0 24 24" fill="currentColor" xmlns="http://www.w3.org/2000/svg"><path d="M24 12.073c0-6.627-5.373-12-12-12s-12 5.373-12 12c0 5.99 4.388 10.954 10.125 11.854v-8.385H7.078v-3.47h3.047V9.43c0-3.007 1.792-4.669 4.533-4.669 1.312 0 2.686.235 2.686.235v2.953H15.83c-1.491 0-1.956.925-1.956 1.874v2.25h3.328l-.532 3.47h-2.796v8.385C19.612 23.027 24 18.062 24 12.073z"/></svg>',
  );
  
  ob_start();
  echo '<div class="wp-block-biederman-social-links links links--social">';
  if ($instagram) {
    echo '<a href="' . esc_url($instagram) . '" target="_blank" rel="noreferrer noopener" class="social-link social-link--instagram" aria-label="' . esc_attr__('Instagram', 'biederman') . '">';
    echo '<span class="social-link__icon">' . $icons['instagram'] . '</span>';
    echo '<span class="social-link__text">' . esc_html__('Instagram', 'biederman') . '</span>';
    echo '</a>';
  }
  if ($tiktok) {
    echo '<a href="' . esc_url($tiktok) . '" target="_blank" rel="noreferrer noopener" class="social-link social-link--tiktok" aria-label="' . esc_attr__('TikTok', 'biederman') . '">';
    echo '<span class="social-link__icon">' . $icons['tiktok'] . '</span>';
    echo '<span class="social-link__text">' . esc_html__('TikTok', 'biederman') . '</span>';
    echo '</a>';
  }
  if ($youtube) {
    echo '<a href="' . esc_url($youtube) . '" target="_blank" rel="noreferrer noopener" class="social-link social-link--youtube" aria-label="' . esc_attr__('YouTube', 'biederman') . '">';
    echo '<span class="social-link__icon">' . $icons['youtube'] . '</span>';
    echo '<span class="social-link__text">' . esc_html__('YouTube', 'biederman') . '</span>';
    echo '</a>';
  }
  if ($facebook) {
    echo '<a href="' . esc_url($facebook) . '" target="_blank" rel="noreferrer noopener" class="social-link social-link--facebook" aria-label="' . esc_attr__('Facebook', 'biederman') . '">';
    echo '<span class="social-link__icon">' . $icons['facebook'] . '</span>';
    echo '<span class="social-link__text">' . esc_html__('Facebook', 'biederman') . '</span>';
    echo '</a>';
  }
  if (!$instagram && !$tiktok && !$youtube && !$facebook) {
    echo '<a href="#" aria-disabled="true" class="social-link social-link--instagram">';
    echo '<span class="social-link__icon">' . $icons['instagram'] . '</span>';
    echo '<span class="social-link__text">' . esc_html__('Instagram', 'biederman') . '</span>';
    echo '</a>';
    echo '<a href="#" aria-disabled="true" class="social-link social-link--tiktok">';
    echo '<span class="social-link__icon">' . $icons['tiktok'] . '</span>';
    echo '<span class="social-link__text">' . esc_html__('TikTok', 'biederman') . '</span>';
    echo '</a>';
    echo '<a href="#" aria-disabled="true" class="social-link social-link--youtube">';
    echo '<span class="social-link__icon">' . $icons['youtube'] . '</span>';
    echo '<span class="social-link__text">' . esc_html__('YouTube', 'biederman') . '</span>';
    echo '</a>';
    echo '<a href="#" aria-disabled="true" class="social-link social-link--facebook">';
    echo '<span class="social-link__icon">' . $icons['facebook'] . '</span>';
    echo '<span class="social-link__text">' . esc_html__('Facebook', 'biederman') . '</span>';
    echo '</a>';
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

