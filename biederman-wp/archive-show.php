<?php
/**
 * Template for show archive
 */

if (!defined('ABSPATH')) { exit; }
get_header();
?>

<main id="main" class="section">
  <div class="container">
    <div class="section__head">
      <h1><?php esc_html_e('Shows', 'biederman'); ?></h1>
      <p><?php esc_html_e('Alle kommenden Termine und Shows.', 'biederman'); ?></p>
    </div>

    <?php if (have_posts()): ?>
      <div class="cards">
        <?php while (have_posts()): the_post(); ?>
          <?php get_template_part('template-parts/content', 'show'); ?>
        <?php endwhile; ?>
      </div>

      <?php
        the_posts_pagination(array(
          'mid_size' => 2,
          'prev_text' => __('← Zurück', 'biederman'),
          'next_text' => __('Weiter →', 'biederman'),
        ));
      ?>
    <?php else: ?>
      <p class="muted"><?php esc_html_e('Keine Shows gefunden.', 'biederman'); ?></p>
    <?php endif; ?>
  </div>
</main>

<?php get_footer(); ?>

