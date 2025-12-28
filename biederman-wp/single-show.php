<?php
/**
 * Template for single show posts
 */

if (!defined('ABSPATH')) { exit; }
get_header();
?>

<main id="main" class="section">
  <div class="container">
    <?php while (have_posts()): the_post(); ?>
      <article id="post-<?php the_ID(); ?>" <?php post_class('card'); ?>>
        <?php if (has_post_thumbnail()): ?>
          <div class="card__image">
            <?php the_post_thumbnail('large'); ?>
          </div>
        <?php endif; ?>

        <header class="card__header">
          <h1><?php the_title(); ?></h1>
          <?php
            $show_date = get_post_meta(get_the_ID(), 'show_date', true);
            $show_location = get_post_meta(get_the_ID(), 'show_location', true);
            $show_venue = get_post_meta(get_the_ID(), 'show_venue', true);
          ?>
          <?php if ($show_date || $show_location || $show_venue): ?>
            <p class="meta">
              <?php if ($show_date): ?>
                <strong><?php echo esc_html(biederman_format_show_date($show_date)); ?></strong>
              <?php endif; ?>
              <?php if ($show_location || $show_venue): ?>
                <?php if ($show_date): ?> · <?php endif; ?>
                <span><?php echo esc_html($show_venue ? $show_venue . ', ' . $show_location : $show_location); ?></span>
              <?php endif; ?>
            </p>
          <?php endif; ?>
        </header>

        <div class="card__content">
          <?php the_content(); ?>
        </div>

        <?php
          $show_ticket_url = get_post_meta(get_the_ID(), 'show_ticket_url', true);
          if ($show_ticket_url || $show_location):
        ?>
          <div class="card__actions">
            <?php if ($show_ticket_url): ?>
              <a class="button primary" href="<?php echo esc_url($show_ticket_url); ?>" target="_blank" rel="noreferrer">
                <?php esc_html_e('Tickets', 'biederman'); ?>
              </a>
            <?php endif; ?>
            <?php if ($show_location): ?>
              <a class="button" href="<?php echo esc_url('https://maps.google.com/?q=' . rawurlencode($show_location)); ?>" target="_blank" rel="noreferrer">
                <?php esc_html_e('Route', 'biederman'); ?>
              </a>
            <?php endif; ?>
          </div>
        <?php endif; ?>
      </article>
    <?php endwhile; ?>
  </div>
</main>

<?php get_footer(); ?>

