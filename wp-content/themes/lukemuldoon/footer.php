<?php

/**
 * The template for displaying the footer.
 */

?>
  <?php get_template_part( 'template-parts/wrapper/end' ); ?>

  <footer class="site-footer">
    <div class="container container--wide js-reveal" data-reveal-start="top 100%">

      <div class="site-footer__inner">

        <!-- Brand -->
        <div class="site-footer__brand">
          <a class="site-footer__logo" href="<?php echo home_url(); ?>" aria-label="Luke Muldoon | Web Developer">
            <?php include_asset('images/logo-inverted.svg'); ?>
          </a>
          <p class="site-footer__tagline">Building fast, clean websites.</p>
        </div>

        <!-- Navigation -->
        <?php
        $footer_menu_locations = get_nav_menu_locations();
        $footer_menu_items     = !empty($footer_menu_locations['footer-menu'])
            ? wp_get_nav_menu_items($footer_menu_locations['footer-menu'])
            : [];
        if ($footer_menu_items) : ?>
        <div class="site-footer__col">
          <span class="site-footer__col-label">Navigation</span>
          <div class="site-footer__links">
            <?php foreach ($footer_menu_items as $item) : ?>
              <a href="<?php echo esc_url($item->url); ?>" class="site-footer__link"><?php echo esc_html($item->title); ?></a>
            <?php endforeach; ?>
          </div>
        </div>
        <?php endif; ?>

        <!-- Areas -->
        <?php
        $area_pages = get_pages([
            'meta_key'    => '_wp_page_template',
            'meta_value'  => 'page-templates/page-area.php',
            'sort_column' => 'post_title',
            'sort_order'  => 'ASC',
        ]);
        if ($area_pages) : ?>
        <div class="site-footer__col">
          <span class="site-footer__col-label">Areas</span>
          <div class="site-footer__links">
            <?php foreach ($area_pages as $area_page) : ?>
              <a class="site-footer__link" href="<?php echo esc_url(get_permalink($area_page)); ?>"><?php echo esc_html($area_page->post_title); ?></a>
            <?php endforeach; ?>
          </div>
        </div>
        <?php endif; ?>

      </div>

      <!-- Bottom bar -->
      <div class="site-footer__bottom">
        <p class="site-footer__copy">&copy; <?php echo date('Y'); ?> Luke Muldoon</p>
        <a href="<?php echo esc_url(get_permalink(get_page_by_path('privacy-policy'))); ?>" class="site-footer__link site-footer__copy">Privacy policy</a>
      </div>

    </div>
  </footer> <!-- /.site-footer -->

  <?php wp_footer(); ?>
</body>
</html>
