<?php

/**
 * The template for displaying the footer.
 */

?>
  <?php get_template_part( 'template-parts/wrapper/end' ); ?>  

  <footer class="site-footer">
    <div class="container container--wide js-reveal" data-reveal-start="top 100%">

      <a class="site-footer__logo" href="<?php echo home_url(); ?>" aria-label="Luke Muldoon | Web Developer">
        <?php include_asset('images/logo-inverted.svg'); ?>
      </a>

      <nav class="site-footer__nav" aria-label="Footer navigation">
        <a href="mailto:luke@lukemuldoon.co.uk?subject=<?php echo rawurlencode('Luke Muldoon - Website Enquiry'); ?>" class="site-footer__link">luke@lukemuldoon.co.uk</a>
        <a href="<?php echo esc_url( get_permalink( get_page_by_path( 'privacy-policy' ) ) ); ?>" class="site-footer__link">Privacy policy</a>
      </nav>

      <p class="site-footer__copy">&copy; <?php echo date('Y'); ?> Luke Muldoon</p>

    </div>
  </footer> <!-- /.site-footer -->
  
  <?php wp_footer(); ?>
</body>
</html>
