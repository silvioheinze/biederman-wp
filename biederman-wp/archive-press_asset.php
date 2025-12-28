<?php
/**
 * Template for Press Assets archive
 */

if (!defined('ABSPATH')) { exit; }
get_header();
?>

<main id="main" class="section">
  <div class="container">
    <div class="section__head">
      <h1><?php esc_html_e('Press Assets', 'biederman'); ?></h1>
      <p><?php esc_html_e('Pressefotos, Rider, Logos und weitere Materialien zum Download.', 'biederman'); ?></p>
    </div>

    <?php if (have_posts()): ?>
      <div class="cards">
        <?php while (have_posts()): the_post(); ?>
          <?php get_template_part('template-parts/content', 'press-asset'); ?>
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
      <p class="muted"><?php esc_html_e('Keine Press Assets gefunden.', 'biederman'); ?></p>
    <?php endif; ?>
  </div>
</main>

<?php get_footer(); ?>

