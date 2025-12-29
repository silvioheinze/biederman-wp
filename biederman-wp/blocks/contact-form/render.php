<?php
/**
 * Contact Form Block - Server-side rendering
 */

if (!defined('ABSPATH')) { exit; }

$wrapper_attributes = get_block_wrapper_attributes();
$form_id = 'biederman-contact-form-' . wp_generate_password(8, false);
?>

<div <?php echo $wrapper_attributes; ?> class="contact-form">
  <style id="biederman-contact-form-styles-<?php echo esc_attr($form_id); ?>">
    #<?php echo esc_attr($form_id); ?> {
      display: flex !important;
      flex-direction: column !important;
      gap: 1rem !important;
    }
    #<?php echo esc_attr($form_id); ?> label {
      display: flex !important;
      flex-direction: column !important;
      gap: 0.5rem !important;
      width: 100% !important;
    }
    #<?php echo esc_attr($form_id); ?> label span {
      display: block !important;
      width: 100% !important;
    }
    #<?php echo esc_attr($form_id); ?> input[type="text"],
    #<?php echo esc_attr($form_id); ?> input[type="email"] {
      width: 100% !important;
      min-width: 100% !important;
      max-width: 100% !important;
      box-sizing: border-box !important;
      display: block !important;
      padding: 0.75rem !important;
      border: 1px solid var(--line) !important;
      border-radius: var(--radius) !important;
      background: rgba(17,24,33,.85) !important;
      color: var(--text) !important;
      font-family: inherit !important;
      font-size: 1rem !important;
    }
    #<?php echo esc_attr($form_id); ?> .form-field-message {
      display: flex !important;
      flex-direction: column !important;
      gap: 0.5rem !important;
      width: 100% !important;
      margin-bottom: 0 !important;
    }
    #<?php echo esc_attr($form_id); ?> .form-field-message label {
      margin-bottom: 0 !important;
      display: block !important;
      width: 100% !important;
    }
    #<?php echo esc_attr($form_id); ?> .form-field-message label span {
      display: block !important;
      margin-bottom: 0.5rem !important;
    }
    #<?php echo esc_attr($form_id); ?> .form-field-message textarea {
      width: 100% !important;
      min-width: 100% !important;
      max-width: 100% !important;
      box-sizing: border-box !important;
      resize: vertical !important;
      display: block !important;
      padding: 0.75rem !important;
      border: 1px solid var(--line) !important;
      border-radius: var(--radius) !important;
      background: rgba(17,24,33,.85) !important;
      color: var(--text) !important;
      font-family: inherit !important;
      font-size: 1rem !important;
    }
    #<?php echo esc_attr($form_id); ?> button[type="submit"] {
      margin-top: 0 !important;
      width: auto !important;
      display: block !important;
    }
  </style>
  <form id="<?php echo esc_attr($form_id); ?>" class="form" method="post" action="<?php echo esc_url(admin_url('admin-ajax.php')); ?>">
    <?php wp_nonce_field('biederman_contact_form', 'biederman_contact_nonce'); ?>
    <input type="hidden" name="action" value="biederman_submit_contact_form">
    
    <label>
      <span><?php esc_html_e('Name', 'biederman'); ?></span>
      <input type="text" name="contact_name" required style="width: 100%; box-sizing: border-box; display: block;" />
    </label>
    
    <label>
      <span><?php esc_html_e('Email', 'biederman'); ?></span>
      <input type="email" name="contact_email" required style="width: 100%; box-sizing: border-box; display: block;" />
    </label>
    
    <div class="form-field-message">
      <label for="<?php echo esc_attr($form_id); ?>-textarea">
        <span><?php esc_html_e('Message', 'biederman'); ?></span>
      </label>
      <textarea id="<?php echo esc_attr($form_id); ?>-textarea" name="contact_message" rows="6" required style="width: 100%; min-width: 100%; max-width: 100%; box-sizing: border-box; display: block;"></textarea>
    </div>
    
    <div style="width: 100%; display: block; margin-top: 1rem;">
      <button class="button primary" type="submit" style="display: block; width: auto;">
        <?php esc_html_e('Send Message', 'biederman'); ?>
      </button>
    </div>
    
    <p class="form-message" id="<?php echo esc_attr($form_id); ?>-message" role="status" aria-live="polite"></p>
  </form>
</div>

<script>
(function() {
  const form = document.getElementById('<?php echo esc_js($form_id); ?>');
  if (!form) return;
  
  form.addEventListener('submit', function(e) {
    e.preventDefault();
    
    const formData = new FormData(form);
    const messageEl = document.getElementById('<?php echo esc_js($form_id); ?>-message');
    const submitBtn = form.querySelector('button[type="submit"]');
    
    submitBtn.disabled = true;
    submitBtn.textContent = '<?php esc_html_e('Sending...', 'biederman'); ?>';
    messageEl.textContent = '';
    messageEl.className = 'form-message';
    
    fetch('<?php echo esc_url(admin_url('admin-ajax.php')); ?>', {
      method: 'POST',
      body: formData
    })
    .then(response => response.json())
    .then(data => {
      if (data.success) {
        messageEl.textContent = '<?php esc_html_e('Thank you! Your message has been sent.', 'biederman'); ?>';
        messageEl.className = 'form-message success';
        form.reset();
      } else {
        messageEl.textContent = data.data && data.data.message ? data.data.message : '<?php esc_html_e('An error occurred. Please try again.', 'biederman'); ?>';
        messageEl.className = 'form-message error';
      }
    })
    .catch(error => {
      messageEl.textContent = '<?php esc_html_e('An error occurred. Please try again.', 'biederman'); ?>';
      messageEl.className = 'form-message error';
    })
    .finally(() => {
      submitBtn.disabled = false;
      submitBtn.textContent = '<?php esc_html_e('Send Message', 'biederman'); ?>';
    });
  });
})();
</script>

