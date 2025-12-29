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
      <ul class="list" style="list-style: none; padding-left: 0;">
        <?php while (have_posts()): the_post(); 
          $press_type = get_post_meta(get_the_ID(), 'press_type', true);
          $press_download_url = get_post_meta(get_the_ID(), 'press_download_url', true);
          $press_file_size = get_post_meta(get_the_ID(), 'press_file_size', true);
        ?>
          <li style="margin-bottom: 1rem; padding-bottom: 1rem; border-bottom: 1px solid var(--line);">
            <div style="display: flex; align-items: center; gap: 0.5rem; margin-bottom: 0.5rem;">
              <?php if ($press_type): ?>
                <span class="pill"><?php echo esc_html($press_type); ?></span>
              <?php endif; ?>
              <strong><?php the_title(); ?></strong>
            </div>
            
            <?php if (has_excerpt()): ?>
              <p style="margin: 0 0 0.5rem; color: rgba(243,245,247,.88);"><?php the_excerpt(); ?></p>
            <?php endif; ?>
            
            <?php if ($press_download_url): ?>
              <?php 
                $download_text = esc_html__('Download', 'biederman');
                if ($press_file_size) {
                  $download_text .= ' (' . esc_html($press_file_size) . ')';
                }
              ?>
              <a class="button" href="<?php echo esc_url($press_download_url); ?>" download style="display: inline-block;">
                <?php echo $download_text; ?>
              </a>
            <?php endif; ?>
          </li>
        <?php endwhile; ?>
      </ul>

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

