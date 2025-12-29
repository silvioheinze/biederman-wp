<?php
/**
 * Meta Boxes for Shows and Press Assets
 */

if (!defined('ABSPATH')) { exit; }

/**
 * Add meta box for Show custom fields
 */
function biederman_add_show_meta_box() {
  add_meta_box(
    'biederman_show_details',
    __('Show Details', 'biederman'),
    'biederman_show_meta_box_callback',
    'show',
    'normal',
    'high',
    array(
      '__block_editor_compatible_meta_box' => true,
    )
  );
}
add_action('add_meta_boxes', 'biederman_add_show_meta_box');

/**
 * Show meta box callback
 */
function biederman_show_meta_box_callback($post) {
  wp_nonce_field('biederman_save_show_meta', 'biederman_show_meta_nonce');
  
  $show_date = get_post_meta($post->ID, 'show_date', true);
  $show_location = get_post_meta($post->ID, 'show_location', true);
  $show_venue = get_post_meta($post->ID, 'show_venue', true);
  $show_ticket_url = get_post_meta($post->ID, 'show_ticket_url', true);
  $show_is_featured = get_post_meta($post->ID, 'show_is_featured', true);
  ?>
  <table class="form-table">
    <tr>
      <th><label for="show_date"><?php _e('Show Date & Time', 'biederman'); ?></label></th>
      <td>
        <input type="datetime-local" id="show_date" name="show_date" value="<?php echo esc_attr($show_date ? date('Y-m-d\TH:i', strtotime($show_date)) : ''); ?>" class="regular-text" />
        <p class="description"><?php _e('Date and time of the show (ISO format: YYYY-MM-DDTHH:MM)', 'biederman'); ?></p>
      </td>
    </tr>
    <tr>
      <th><label for="show_venue"><?php _e('Venue Name', 'biederman'); ?></label></th>
      <td>
        <input type="text" id="show_venue" name="show_venue" value="<?php echo esc_attr($show_venue); ?>" class="regular-text" />
        <p class="description"><?php _e('Name of the venue (e.g., "Loop")', 'biederman'); ?></p>
      </td>
    </tr>
    <tr>
      <th><label for="show_location"><?php _e('Location', 'biederman'); ?></label></th>
      <td>
        <input type="text" id="show_location" name="show_location" value="<?php echo esc_attr($show_location); ?>" class="regular-text" />
        <p class="description"><?php _e('Full address of the venue', 'biederman'); ?></p>
      </td>
    </tr>
    <tr>
      <th><label for="show_ticket_url"><?php _e('Ticket URL', 'biederman'); ?></label></th>
      <td>
        <input type="url" id="show_ticket_url" name="show_ticket_url" value="<?php echo esc_url($show_ticket_url); ?>" class="regular-text" />
        <p class="description"><?php _e('URL to purchase tickets', 'biederman'); ?></p>
      </td>
    </tr>
    <tr>
      <th><label for="show_is_featured"><?php _e('Featured Show', 'biederman'); ?></label></th>
      <td>
        <label>
          <input type="checkbox" id="show_is_featured" name="show_is_featured" value="1" <?php checked($show_is_featured, '1'); ?> />
          <?php _e('Mark as featured show (next upcoming show)', 'biederman'); ?>
        </label>
      </td>
    </tr>
  </table>
  <?php
}

/**
 * Save show meta box data
 */
function biederman_save_show_meta($post_id) {
  // Check nonce
  if (!isset($_POST['biederman_show_meta_nonce']) || !wp_verify_nonce($_POST['biederman_show_meta_nonce'], 'biederman_save_show_meta')) {
    return;
  }
  
  // Check autosave
  if (defined('DOING_AUTOSAVE') && DOING_AUTOSAVE) {
    return;
  }
  
  // Check permissions
  if (!current_user_can('edit_post', $post_id)) {
    return;
  }
  
  // Check post type
  if (get_post_type($post_id) !== 'show') {
    return;
  }
  
  // Save meta fields
  if (isset($_POST['show_date'])) {
    $date = sanitize_text_field($_POST['show_date']);
    // Convert datetime-local to ISO format
    if ($date) {
      $date = date('Y-m-d H:i:s', strtotime($date));
    }
    update_post_meta($post_id, 'show_date', $date);
  }
  
  if (isset($_POST['show_venue'])) {
    update_post_meta($post_id, 'show_venue', sanitize_text_field($_POST['show_venue']));
  }
  
  if (isset($_POST['show_location'])) {
    update_post_meta($post_id, 'show_location', sanitize_text_field($_POST['show_location']));
  }
  
  if (isset($_POST['show_ticket_url'])) {
    update_post_meta($post_id, 'show_ticket_url', esc_url_raw($_POST['show_ticket_url']));
  }
  
  if (isset($_POST['show_is_featured'])) {
    update_post_meta($post_id, 'show_is_featured', '1');
  } else {
    update_post_meta($post_id, 'show_is_featured', '0');
  }
}
add_action('save_post', 'biederman_save_show_meta');

/**
 * Add meta box for Press Asset custom fields
 */
function biederman_add_press_asset_meta_box() {
  add_meta_box(
    'biederman_press_asset_details',
    __('Press Asset Details', 'biederman'),
    'biederman_press_asset_meta_box_callback',
    'press_asset',
    'normal',
    'high',
    array(
      '__block_editor_compatible_meta_box' => true,
    )
  );
}
add_action('add_meta_boxes', 'biederman_add_press_asset_meta_box');

/**
 * Press Asset meta box callback
 */
function biederman_press_asset_meta_box_callback($post) {
  wp_nonce_field('biederman_save_press_asset_meta', 'biederman_press_asset_meta_nonce');
  
  $press_type = get_post_meta($post->ID, 'press_type', true);
  $press_download_url = get_post_meta($post->ID, 'press_download_url', true);
  $press_file_size = get_post_meta($post->ID, 'press_file_size', true);
  
  $press_types = array(
    '' => __('Select Type', 'biederman'),
    'photo' => __('Photo', 'biederman'),
    'rider' => __('Rider', 'biederman'),
    'logo' => __('Logo', 'biederman'),
    'press' => __('Press Kit', 'biederman'),
  );
  ?>
  <table class="form-table">
    <tr>
      <th><label for="press_type"><?php _e('Asset Type', 'biederman'); ?></label></th>
      <td>
        <select id="press_type" name="press_type" class="regular-text">
          <?php foreach ($press_types as $value => $label): ?>
            <option value="<?php echo esc_attr($value); ?>" <?php selected($press_type, $value); ?>>
              <?php echo esc_html($label); ?>
            </option>
          <?php endforeach; ?>
        </select>
        <p class="description"><?php _e('Type of press asset', 'biederman'); ?></p>
      </td>
    </tr>
    <tr>
      <th><label for="press_download_url"><?php _e('Download URL', 'biederman'); ?></label></th>
      <td>
        <input type="url" id="press_download_url" name="press_download_url" value="<?php echo esc_url($press_download_url); ?>" class="regular-text" />
        <p class="description"><?php _e('URL to download the asset file', 'biederman'); ?></p>
      </td>
    </tr>
    <tr>
      <th><label for="press_file_size"><?php _e('File Size', 'biederman'); ?></label></th>
      <td>
        <input type="text" id="press_file_size" name="press_file_size" value="<?php echo esc_attr($press_file_size); ?>" class="regular-text" placeholder="e.g., 2.5 MB" />
        <p class="description"><?php _e('File size for display (optional)', 'biederman'); ?></p>
      </td>
    </tr>
  </table>
  <?php
}

/**
 * Save Press Asset meta box data
 */
function biederman_save_press_asset_meta($post_id) {
  // Check nonce
  if (!isset($_POST['biederman_press_asset_meta_nonce']) || !wp_verify_nonce($_POST['biederman_press_asset_meta_nonce'], 'biederman_save_press_asset_meta')) {
    return;
  }
  
  // Check autosave
  if (defined('DOING_AUTOSAVE') && DOING_AUTOSAVE) {
    return;
  }
  
  // Check permissions
  if (!current_user_can('edit_post', $post_id)) {
    return;
  }
  
  // Check post type
  if (get_post_type($post_id) !== 'press_asset') {
    return;
  }
  
  // Save meta fields
  if (isset($_POST['press_type'])) {
    update_post_meta($post_id, 'press_type', sanitize_text_field($_POST['press_type']));
  }
  
  if (isset($_POST['press_download_url'])) {
    update_post_meta($post_id, 'press_download_url', esc_url_raw($_POST['press_download_url']));
  }
  
  if (isset($_POST['press_file_size'])) {
    update_post_meta($post_id, 'press_file_size', sanitize_text_field($_POST['press_file_size']));
  }
}
add_action('save_post', 'biederman_save_press_asset_meta');

/**
 * Add meta box for Contact Submission fields
 */
function biederman_add_contact_submission_meta_box() {
  add_meta_box(
    'biederman_contact_submission_details',
    __('Contact Details', 'biederman'),
    'biederman_contact_submission_meta_box_callback',
    'contact_submission',
    'normal',
    'high'
  );
}
add_action('add_meta_boxes', 'biederman_add_contact_submission_meta_box');

/**
 * Contact Submission meta box callback
 */
function biederman_contact_submission_meta_box_callback($post) {
  $contact_name = get_post_meta($post->ID, 'contact_name', true);
  $contact_email = get_post_meta($post->ID, 'contact_email', true);
  $contact_message = get_post_meta($post->ID, 'contact_message', true);
  ?>
  <table class="form-table">
    <tr>
      <th><label for="contact_name"><?php _e('Name', 'biederman'); ?></label></th>
      <td>
        <input type="text" id="contact_name" name="contact_name" value="<?php echo esc_attr($contact_name); ?>" class="regular-text" readonly />
      </td>
    </tr>
    <tr>
      <th><label for="contact_email"><?php _e('Email', 'biederman'); ?></label></th>
      <td>
        <input type="email" id="contact_email" name="contact_email" value="<?php echo esc_attr($contact_email); ?>" class="regular-text" readonly />
        <?php if ($contact_email): ?>
          <a href="mailto:<?php echo esc_attr($contact_email); ?>" class="button" style="margin-left: 10px;"><?php _e('Send Email', 'biederman'); ?></a>
        <?php endif; ?>
      </td>
    </tr>
    <tr>
      <th><label for="contact_message"><?php _e('Message', 'biederman'); ?></label></th>
      <td>
        <textarea id="contact_message" name="contact_message" rows="10" class="large-text" readonly><?php echo esc_textarea($contact_message); ?></textarea>
      </td>
    </tr>
  </table>
  <?php
}

/**
 * Save Contact Submission meta box data
 */
function biederman_save_contact_submission_meta($post_id) {
  // Check autosave
  if (defined('DOING_AUTOSAVE') && DOING_AUTOSAVE) {
    return;
  }
  
  // Check permissions
  if (!current_user_can('edit_post', $post_id)) {
    return;
  }
  
  // Check post type
  if (get_post_type($post_id) !== 'contact_submission') {
    return;
  }
  
  // Save meta fields (only if not readonly - should not happen for submissions)
  if (isset($_POST['contact_name'])) {
    update_post_meta($post_id, 'contact_name', sanitize_text_field($_POST['contact_name']));
  }
  
  if (isset($_POST['contact_email'])) {
    update_post_meta($post_id, 'contact_email', sanitize_email($_POST['contact_email']));
  }
  
  if (isset($_POST['contact_message'])) {
    update_post_meta($post_id, 'contact_message', sanitize_textarea_field($_POST['contact_message']));
  }
}
add_action('save_post', 'biederman_save_contact_submission_meta');

