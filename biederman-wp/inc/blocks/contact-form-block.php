<?php
/**
 * Contact Form Block - Registration
 */

if (!defined('ABSPATH')) { exit; }

/**
 * Register Contact Form block using block.json
 */
function biederman_register_contact_form_block() {
  $block_path = get_template_directory() . '/blocks/contact-form';
  
  if (!file_exists($block_path . '/block.json')) {
    return;
  }
  
  // Use block.json render file, but we can still add custom callback if needed
  $block_args = array();

  $build_path = $block_path . '/build';
  if (file_exists($build_path . '/index.js')) {
    $block_args['editor_script'] = 'biederman-contact-form-block-editor';
    wp_register_script(
      'biederman-contact-form-block-editor',
      get_template_directory_uri() . '/blocks/contact-form/build/index.js',
      array('wp-blocks', 'wp-element', 'wp-block-editor', 'wp-components', 'wp-i18n'),
      wp_get_theme()->get('Version'),
      true
    );
  }
  
  if (file_exists($build_path . '/index.css')) {
    $block_args['editor_style'] = 'biederman-contact-form-block-editor-style';
    wp_register_style(
      'biederman-contact-form-block-editor-style',
      get_template_directory_uri() . '/blocks/contact-form/build/index.css',
      array('wp-edit-blocks'),
      wp_get_theme()->get('Version')
    );
  }
  
  if (file_exists($build_path . '/style-index.css')) {
    $block_args['style'] = 'biederman-contact-form-block-style';
    wp_register_style(
      'biederman-contact-form-block-style',
      get_template_directory_uri() . '/blocks/contact-form/build/style-index.css',
      array(),
      wp_get_theme()->get('Version')
    );
  }
  
  register_block_type($block_path, $block_args);
}
add_action('init', 'biederman_register_contact_form_block');

/**
 * Render Contact Form block (server-side)
 */
function biederman_render_contact_form_block($attributes, $content, $block) {
  // Make $block available to render.php
  global $biederman_current_block;
  $biederman_current_block = $block;
  
  // Clear any potential caching
  clearstatcache();
  
  $render_file = get_template_directory() . '/blocks/contact-form/render.php';
  
  if (!file_exists($render_file)) {
    $biederman_current_block = null;
    return '<p>' . esc_html__('Contact form template not found.', 'biederman') . '</p>';
  }
  
  ob_start();
  include $render_file;
  $output = ob_get_clean();
  $biederman_current_block = null;
  return $output;
}

/**
 * Handle AJAX form submission
 */
function biederman_handle_contact_form_submission() {
  // Verify nonce
  if (!isset($_POST['biederman_contact_nonce']) || !wp_verify_nonce($_POST['biederman_contact_nonce'], 'biederman_contact_form')) {
    wp_send_json_error(array('message' => __('Security check failed.', 'biederman')));
    return;
  }
  
  // Get and sanitize form data
  $name = isset($_POST['contact_name']) ? sanitize_text_field($_POST['contact_name']) : '';
  $email = isset($_POST['contact_email']) ? sanitize_email($_POST['contact_email']) : '';
  $message = isset($_POST['contact_message']) ? sanitize_textarea_field($_POST['contact_message']) : '';
  
  // Validate required fields
  if (empty($name) || empty($email) || empty($message)) {
    wp_send_json_error(array('message' => __('Please fill in all required fields.', 'biederman')));
    return;
  }
  
  // Validate email
  if (!is_email($email)) {
    wp_send_json_error(array('message' => __('Please enter a valid email address.', 'biederman')));
    return;
  }
  
  // Create contact submission post
  $post_data = array(
    'post_title'   => sprintf(__('Contact from %s', 'biederman'), $name),
    'post_content' => $message,
    'post_status'  => 'publish',
    'post_type'    => 'contact_submission',
  );
  
  $post_id = wp_insert_post($post_data);
  
  if (is_wp_error($post_id)) {
    wp_send_json_error(array('message' => __('Failed to save submission.', 'biederman')));
    return;
  }
  
  // Save meta fields
  update_post_meta($post_id, 'contact_name', $name);
  update_post_meta($post_id, 'contact_email', $email);
  update_post_meta($post_id, 'contact_message', $message);
  
  wp_send_json_success(array('message' => __('Thank you! Your message has been sent.', 'biederman')));
}
add_action('wp_ajax_biederman_submit_contact_form', 'biederman_handle_contact_form_submission');
add_action('wp_ajax_nopriv_biederman_submit_contact_form', 'biederman_handle_contact_form_submission');

