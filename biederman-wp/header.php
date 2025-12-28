<?php
if (!defined('ABSPATH')) { exit; }
?><!doctype html>
<html <?php language_attributes(); ?>>
<head>
  <meta charset="<?php bloginfo('charset'); ?>">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <?php wp_head(); ?>
</head>

<body <?php body_class(); ?>>
<?php wp_body_open(); ?>

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
        // Check if we should show navigation (can be controlled via theme mod)
        // Default to true if not set
        $show_nav = get_theme_mod('biederman_show_navigation');
        if ($show_nav === false) {
          $show_nav = true; // Default to true
        }
        
        if ($show_nav) {
          // Check if a menu is assigned, otherwise use fallback
          $menu_locations = get_nav_menu_locations();
          $has_menu = isset($menu_locations['primary']) && $menu_locations['primary'] > 0;
          
          if ($has_menu) {
            // Show WordPress menu if one is assigned
            wp_nav_menu(array(
              'theme_location' => 'primary',
              'container' => false,
              'items_wrap' => '%3$s',
              'depth' => 1,
            ));
          } else {
            // Fallback: show anchor links only on front page (one-page site)
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
