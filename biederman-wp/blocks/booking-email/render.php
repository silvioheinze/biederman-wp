<?php
/**
 * Booking Email Block - Server-side rendering
 */

if (!defined('ABSPATH')) { exit; }

$wrapper_attributes = get_block_wrapper_attributes();

$email = get_theme_mod('biederman_booking_email', 'booking@biederman.band');
$subject = rawurlencode('Booking Anfrage – Biederman');
?>
<div <?php echo $wrapper_attributes; ?> class="contact__row">
  <a class="button primary" id="booking-email" data-email="<?php echo esc_attr($email); ?>" href="<?php echo esc_url('mailto:' . $email . '?subject=' . $subject); ?>"><?php echo esc_html($email); ?></a>
  <button class="button" id="btn-copy-mail" type="button"><?php esc_html_e('Mail kopieren', 'biederman'); ?></button>
</div>
<p class="small muted" id="mail-msg" role="status" aria-live="polite"></p>

