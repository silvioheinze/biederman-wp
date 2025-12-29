<?php
/**
 * Template part for displaying show cards
 */

if (!defined('ABSPATH')) { exit; }

$show_date = get_post_meta(get_the_ID(), 'show_date', true);
$show_location = get_post_meta(get_the_ID(), 'show_location', true);
$show_venue = get_post_meta(get_the_ID(), 'show_venue', true);
$show_ticket_url = get_post_meta(get_the_ID(), 'show_ticket_url', true);
$is_featured = get_post_meta(get_the_ID(), 'show_is_featured', true);
?>

<article class="card <?php echo $is_featured ? 'card--featured' : ''; ?>" aria-label="<?php echo esc_attr(get_the_title()); ?>">
  <?php if (has_post_thumbnail()): ?>
    <div class="card__image">
      <?php the_post_thumbnail('medium_large'); ?>
    </div>
  <?php endif; ?>

  <h3><?php the_title(); ?></h3>

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

  <?php if (has_excerpt()): ?>
    <div class="card__excerpt">
      <?php the_excerpt(); ?>
    </div>
  <?php endif; ?>

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
    <?php if ($is_featured && $show_date): ?>
      <button class="button btn-ics-featured" 
              type="button" 
              data-show-title="<?php echo esc_attr(get_the_title()); ?>"
              data-show-date="<?php echo esc_attr($show_date); ?>"
              data-show-location="<?php echo esc_attr($show_venue ? $show_venue . ', ' . $show_location : $show_location); ?>"
              data-show-description="<?php echo esc_attr(get_the_excerpt() ?: get_the_title()); ?>"
              data-show-url="<?php echo esc_url($show_ticket_url ?: get_permalink()); ?>">
        <?php esc_html_e('In Kalender', 'biederman'); ?>
      </button>
    <?php endif; ?>
  </div>
  <?php if ($is_featured && $show_date): ?>
    <p class="small muted show-ics-msg" role="status" aria-live="polite"></p>
  <?php endif; ?>
</article>

