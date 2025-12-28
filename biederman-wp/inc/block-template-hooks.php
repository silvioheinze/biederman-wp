<?php
/**
 * Ensure header and footer are loaded with block templates
 */

if (!defined('ABSPATH')) { exit; }

/**
 * Output header content before wp-site-blocks when using block templates
 * This only outputs the header content (not DOCTYPE/html tags)
 */
function biederman_output_header_content_for_block_templates() {
  // Only on front page
  if (!is_front_page()) {
    return;
  }
  
  // Check if we're using a block template (wp-site-blocks exists)
  // Only output if header hasn't been output via normal template
  if (!did_action('get_header')) {
    // Output just the header content, not the full header.php
    ?>
    <a class="skip" href="#main"><?php esc_html_e('Zum Inhalt springen', 'biederman'); ?></a>
    <header class="topbar" id="top">
      <div class="container topbar__inner">
        <a class="brand" href="<?php echo esc_url(home_url('/')); ?>" aria-label="<?php esc_attr_e('Startseite Biederman', 'biederman'); ?>">
          <span class="brand__dot" aria-hidden="true"></span>
          <span class="brand__name"><?php echo esc_html(get_bloginfo('name', 'display')); ?></span>
        </a>

        <button class="navbtn" type="button" aria-label="<?php esc_attr_e('Menü öffnen', 'biederman'); ?>" aria-expanded="false" aria-controls="nav">
          <span class="navbtn__bar" aria-hidden="true"></span>
          <span class="navbtn__bar" aria-hidden="true"></span>
          <span class="navbtn__bar" aria-hidden="true"></span>
        </button>

        <nav class="nav" id="nav" aria-label="<?php esc_attr_e('Hauptnavigation', 'biederman'); ?>">
          <?php
            $show_nav = get_theme_mod('biederman_show_navigation', true);
            if ($show_nav) {
              $menu_locations = get_nav_menu_locations();
              $has_menu = isset($menu_locations['primary']) && $menu_locations['primary'] > 0;
              if ($has_menu) {
                wp_nav_menu(array(
                  'theme_location' => 'primary',
                  'container' => false,
                  'items_wrap' => '%3$s',
                  'depth' => 1,
                ));
              } else {
                if (is_front_page()) {
                  echo '<a href="#shows">Shows</a>';
                  echo '<a href="#media">Media</a>';
                  echo '<a href="#about">Über uns</a>';
                  echo '<a href="#press">Presse</a>';
                  echo '<a class="cta" href="#contact">Booking</a>';
                }
              }
            }
          ?>
        </nav>
      </div>
    </header>
    <?php
  }
}
add_action('wp_body_open', 'biederman_output_header_content_for_block_templates', 1);

/**
 * Output footer content after wp-site-blocks when using block templates
 */
function biederman_output_footer_content_for_block_templates() {
  // Only on front page
  if (!is_front_page()) {
    return;
  }
  
  // Only output if footer hasn't been output via normal template
  if (!did_action('get_footer')) {
    ?>
    <footer class="footer">
      <div class="container footer__inner">
        <p class="small muted">© <?php echo esc_html(date('Y')); ?> <?php echo esc_html(get_bloginfo('name', 'display')); ?> · Wien</p>
        <a class="textlink small" href="#top"><?php esc_html_e('Nach oben ↑', 'biederman'); ?></a>
      </div>
    </footer>
    <?php
  }
}
add_action('wp_footer', 'biederman_output_footer_content_for_block_templates', 999);

