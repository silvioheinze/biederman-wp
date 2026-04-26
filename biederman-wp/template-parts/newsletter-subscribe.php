<?php
/**
 * Newsletter subscription panel (same markup as front-page Media section).
 */
if (!defined('ABSPATH')) { exit; }
?>
<div class="panel">
  <h3><?php esc_html_e('Newsletter', 'biederman'); ?></h3>
  <p class="muted"><?php esc_html_e('Updates zu Shows, Releases und exklusiven Dingen.', 'biederman'); ?></p>
  <form id="newsletter" class="form" autocomplete="on">
    <label>
      <span class="sr"><?php esc_html_e('E-Mail', 'biederman'); ?></span>
      <input name="email" type="email" required placeholder="deine@email.at" inputmode="email" />
    </label>
    <button class="button primary" type="submit"><?php esc_html_e('Anmelden', 'biederman'); ?></button>
    <p class="small muted">
      <?php esc_html_e('Double-Opt-In empfohlen. Kein Spam.', 'biederman'); ?>
      <a href="<?php echo esc_url(biederman_newsletter_privacy_url()); ?>" class="textlink"><?php esc_html_e('Datenschutz', 'biederman'); ?></a>.
    </p>
  </form>
  <p class="small" id="nl-msg" role="status" aria-live="polite"></p>
</div>
