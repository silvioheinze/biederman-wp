<?php
/**
 * Template part for displaying Press Assets
 */

if (!defined('ABSPATH')) { exit; }

$press_type = get_post_meta(get_the_ID(), 'press_type', true);
$press_download_url = get_post_meta(get_the_ID(), 'press_download_url', true);
$press_file_size = get_post_meta(get_the_ID(), 'press_file_size', true);
?>

<article class="card" aria-label="<?php echo esc_attr(get_the_title()); ?>">
  <?php if (has_post_thumbnail()): ?>
    <div class="card__image">
      <?php the_post_thumbnail('medium'); ?>
    </div>
  <?php endif; ?>

  <h3><?php the_title(); ?></h3>

  <?php if ($press_type): ?>
    <p class="meta">
      <span class="pill"><?php echo esc_html($press_type); ?></span>
    </p>
  <?php endif; ?>

  <?php if (has_excerpt()): ?>
    <div class="card__excerpt">
      <?php the_excerpt(); ?>
    </div>
  <?php endif; ?>

  <?php if ($press_download_url): ?>
    <div class="card__actions">
      <a class="button" href="<?php echo esc_url($press_download_url); ?>" download>
        <?php esc_html_e('Download', 'biederman'); ?>
        <?php if ($press_file_size): ?>
          <span class="small">(<?php echo esc_html($press_file_size); ?>)</span>
        <?php endif; ?>
      </a>
    </div>
  <?php endif; ?>
</article>

