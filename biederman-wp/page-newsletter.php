<?php
/**
 * Template Name: Newsletter Landing
 * Description: Focused landing layout with the same newsletter signup as the front page.
 */
if (!defined('ABSPATH')) { exit; }
get_header();
?>
<main id="main">
  <?php while (have_posts()) : the_post(); ?>
    <section class="section" aria-label="<?php esc_attr_e('Newsletter', 'biederman'); ?>">
      <div class="container">
        <div class="section__head">
          <h1><?php the_title(); ?></h1>
          <div class="entry-content">
            <?php the_content(); ?>
          </div>
        </div>
        <div class="stack">
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
        </div>
      </div>
    </section>
  <?php endwhile; ?>
</main>
<?php get_footer(); ?>
