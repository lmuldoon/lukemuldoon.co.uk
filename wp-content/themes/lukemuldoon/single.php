<?php get_header(); ?>

<main id="main-content">

    <?php while (have_posts()) : the_post(); ?>

    <section class="insight__header bg-paper js-reveal" aria-labelledby="post-heading">
        <div class="container">
            <p class="kicker">Insights</p>
            <h1 id="post-heading" class="h2 hero__heading"><?php the_title(); ?></h1>
            <time class="insight-post__date" datetime="<?php echo esc_attr(get_the_date('c')); ?>"><?php echo esc_html(get_the_date('j F Y')); ?></time>

        <?php if (has_post_thumbnail()) : ?>
                <div class="insight-post__featured-image ratio ratio--16-9 mt-12">
                    <?php the_post_thumbnail('full', ['loading' => 'eager', 'class' => 'insight-post__featured-img ratio__content']); ?>
                </div>
            </div>
        <?php endif; ?>
    </section>

    <section class="section bg-paper" aria-label="Post content">
        <div class="container container--text">
            <div class="insight-post__content flow">
                <?php the_content(); ?>
            </div>
            <div class="insight-post__back">
                <?php
                $insights_pages = get_pages(['meta_key' => '_wp_page_template', 'meta_value' => 'page-templates/page-insights.php']);
                $insights_url   = $insights_pages ? get_permalink($insights_pages[0]) : home_url('/insights/');
                ?>
                <a href="<?php echo esc_url($insights_url); ?>" class="insight-card__read-more">&#8592; Back to Insights</a>
            </div>
        </div>
    </section>

    <?php endwhile; ?>

    <?php get_template_part('template-parts/cta'); ?>

</main>

<?php get_footer(); ?>
