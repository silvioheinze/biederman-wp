<?php
/**
 * Fallback template (if no static front page is set).
 */
if (!defined('ABSPATH')) { exit; }
get_header(); ?>
<main id="main" class="section">
  <div class="container">
    <div class="section__head">
      <h2><?php esc_html_e('Beiträge', 'biederman'); ?></h2>
      <p class="muted"><?php esc_html_e('Setze in WordPress „Startseite“ auf eine statische Seite, um die One-Page zu verwenden.', 'biederman'); ?></p>
    </div>
    <?php if (have_posts()): ?>
      <?php while (have_posts()): the_post(); ?>
        <article class="card" style="margin-bottom:1rem;">
          <h3><a class="textlink" href="<?php the_permalink(); ?>"><?php the_title(); ?></a></h3>
          <div class="muted"><?php the_excerpt(); ?></div>
        </article>
      <?php endwhile; ?>
    <?php else: ?>
      <p class="muted"><?php esc_html_e('Keine Inhalte gefunden.', 'biederman'); ?></p>
    <?php endif; ?>
  </div>
</main>
<?php get_footer(); ?>
