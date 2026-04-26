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
          <?php get_template_part('template-parts/newsletter', 'subscribe'); ?>
        </div>
      </div>
    </section>
  <?php endwhile; ?>
</main>
<?php get_footer(); ?>
