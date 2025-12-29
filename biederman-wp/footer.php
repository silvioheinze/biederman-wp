<?php
if (!defined('ABSPATH')) { exit; }
?>
<footer class="footer">
  <div class="container footer__inner">
    <p class="small muted">© <?php echo esc_html(date('Y')); ?> <?php echo esc_html(get_bloginfo('name', 'display')); ?> · Wien</p>
        <a class="textlink small" href="#hero"><?php esc_html_e('Nach oben ↑', 'biederman'); ?></a>
  </div>
</footer>

<?php wp_footer(); ?>
</body>
</html>
