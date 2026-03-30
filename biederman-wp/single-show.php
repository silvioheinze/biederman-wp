<?php
/**
 * Template for single show posts
 */

if (!defined('ABSPATH')) { exit; }
get_header();

$shows_archive = get_post_type_archive_link('show');
?>

<main id="main" class="section show-single">
  <div class="container">
    <?php if ($shows_archive): ?>
      <p class="show-single__back">
        <a class="textlink" href="<?php echo esc_url($shows_archive); ?>"><?php esc_html_e('← Alle Shows', 'biederman'); ?></a>
      </p>
    <?php endif; ?>

    <?php while (have_posts()): the_post(); ?>
      <?php
        $show_date = get_post_meta(get_the_ID(), 'show_date', true);
        $show_location = get_post_meta(get_the_ID(), 'show_location', true);
        $show_venue = get_post_meta(get_the_ID(), 'show_venue', true);
        $show_ticket_url = get_post_meta(get_the_ID(), 'show_ticket_url', true);
        $permalink = get_permalink();
      ?>
      <article id="post-<?php the_ID(); ?>" <?php post_class('card show-single__article'); ?>>
        <div class="show-single__grid">
          <?php if (has_post_thumbnail()): ?>
            <div class="show-single__media">
              <?php the_post_thumbnail('large'); ?>
            </div>
          <?php endif; ?>

          <div class="show-single__body">
            <header class="show-single__header">
              <h1><?php the_title(); ?></h1>
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

            <?php if (has_excerpt()): ?>
              <div class="show-single__lead">
                <?php the_excerpt(); ?>
              </div>
            <?php endif; ?>

            <div class="card__content show-single__content">
              <?php the_content(); ?>
            </div>

            <?php if ($show_ticket_url || $show_location || $show_date): ?>
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
                <?php if ($show_date): ?>
                  <button class="button btn-ics-show"
                          type="button"
                          data-show-title="<?php echo esc_attr(get_the_title()); ?>"
                          data-show-date="<?php echo esc_attr($show_date); ?>"
                          data-show-location="<?php echo esc_attr($show_venue ? $show_venue . ', ' . $show_location : $show_location); ?>"
                          data-show-description="<?php echo esc_attr(get_the_excerpt() ?: get_the_title()); ?>"
                          data-show-url="<?php echo esc_url($show_ticket_url ?: $permalink); ?>">
                    <?php esc_html_e('In Kalender', 'biederman'); ?>
                  </button>
                <?php endif; ?>
              </div>
              <?php if ($show_date): ?>
                <p class="small muted show-ics-msg" role="status" aria-live="polite"></p>
              <?php endif; ?>
            <?php endif; ?>
          </div>
        </div>
      </article>
    <?php endwhile; ?>
  </div>
</main>

<?php get_footer(); ?>
