<?php
/**
 * Social Links Block - Server-side rendering
 */

if (!defined('ABSPATH')) { exit; }

$wrapper_attributes = get_block_wrapper_attributes();

$instagram = get_theme_mod('biederman_instagram', '');
$youtube   = get_theme_mod('biederman_youtube', '');
$tiktok    = get_theme_mod('biederman_tiktok', '');
$facebook  = get_theme_mod('biederman_facebook', '');
?>
<div <?php echo $wrapper_attributes; ?> class="links links--social" aria-label="<?php esc_attr_e('Social Links', 'biederman'); ?>">
<?php
if ($instagram) {
  echo '<a href="' . esc_url($instagram) . '" target="_blank" rel="noreferrer">' . esc_html__('Instagram', 'biederman') . '</a>';
}
if ($tiktok) {
  echo '<a href="' . esc_url($tiktok) . '" target="_blank" rel="noreferrer">' . esc_html__('TikTok', 'biederman') . '</a>';
}
if ($youtube) {
  echo '<a href="' . esc_url($youtube) . '" target="_blank" rel="noreferrer">' . esc_html__('YouTube', 'biederman') . '</a>';
}
if ($facebook) {
  echo '<a href="' . esc_url($facebook) . '" target="_blank" rel="noreferrer">' . esc_html__('Facebook', 'biederman') . '</a>';
}

// If no social links are set, show placeholder
if (!$instagram && !$tiktok && !$youtube && !$facebook) {
  echo '<a href="#" aria-disabled="true">' . esc_html__('Instagram', 'biederman') . '</a>';
  echo '<a href="#" aria-disabled="true">' . esc_html__('TikTok', 'biederman') . '</a>';
  echo '<a href="#" aria-disabled="true">' . esc_html__('YouTube', 'biederman') . '</a>';
  echo '<a href="#" aria-disabled="true">' . esc_html__('Facebook', 'biederman') . '</a>';
}
?>
</div>

