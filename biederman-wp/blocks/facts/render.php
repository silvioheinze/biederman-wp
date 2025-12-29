<?php
/**
 * Facts Block - Server-side rendering
 */

if (!defined('ABSPATH')) { exit; }

// Get block wrapper attributes
global $biederman_current_block;
if (isset($biederman_current_block) && function_exists('get_block_wrapper_attributes')) {
  $wrapper_attributes = get_block_wrapper_attributes();
} else {
  // Fallback if get_block_wrapper_attributes is not available
  $wrapper_attributes = 'class="wp-block-biederman-facts"';
}

$facts = isset($attributes['facts']) ? $attributes['facts'] : array();

if (empty($facts)) {
  // Default facts if none are set
  $facts = array(
    array('key' => 'Stil', 'value' => 'Comedy · Live · Pop'),
    array('key' => 'Base', 'value' => 'Wien'),
    array('key' => 'Buchung', 'value' => 'Kontakt →', 'link' => '#contact'),
  );
}
?>

<div <?php echo $wrapper_attributes; ?> class="facts" aria-label="<?php esc_attr_e('Facts', 'biederman'); ?>">
  <?php foreach ($facts as $fact): 
    $key = isset($fact['key']) ? $fact['key'] : '';
    $value = isset($fact['value']) ? $fact['value'] : '';
    $link = isset($fact['link']) ? $fact['link'] : '';
    
    if (empty($key) && empty($value)) {
      continue;
    }
  ?>
    <div class="fact">
      <div class="fact__k"><?php echo esc_html($key); ?></div>
      <div class="fact__v">
        <?php if ($link): ?>
          <a class="textlink" href="<?php echo esc_url($link); ?>"><?php echo esc_html($value); ?></a>
        <?php else: ?>
          <?php echo esc_html($value); ?>
        <?php endif; ?>
      </div>
    </div>
  <?php endforeach; ?>
</div>

