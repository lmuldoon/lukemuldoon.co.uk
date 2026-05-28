<?php
/**
 * Template Name: Insights
 */

get_header();

$hero  = get_field('hero') ?: [];
$paged = max(1, get_query_var('paged'));

$posts_query = new WP_Query([
    'post_type'      => 'post',
    'posts_per_page' => 9,
    'paged'          => $paged,
]);
$hero_title   = $hero['title'];

$hero_title = preg_replace(
    '/\*\*(.*?)\*\*/',
    '<span class="accent-underline has-inline-svg">$1'
    . '<svg class="accent-underline__svg" viewBox="0 0 300 14" preserveAspectRatio="none" aria-hidden="true">'
    . '<path d="M3,9 C50,11 90,4 150,7 C200,10 250,4 297,7" stroke="#9FE000" stroke-width="4" fill="none" stroke-linecap="round"/>'
    . '</svg></span>',
    esc_html($hero_title)
);
?>

<main id="main-content">

    <section class="hero hero hero__page section bg-chalk hero__insights" aria-labelledby="insights-heading">
        <div class="container js-reveal">
            <?php if (!empty($hero['kicker'])) : ?><p class="kicker"><?php echo esc_html($hero['kicker']); ?></p><?php endif; ?>
            <?php if (!empty($hero_title)) : ?><h1 id="about-heading" class="h2 hero__heading"><?php echo $hero_title; ?></h1><?php endif; ?>
            <?php if (!empty($hero['text']))   : ?><div class="hero__body"><?php echo wp_kses_post($hero['text']); ?></div><?php endif; ?>
        </div>
    </section>

    <section class="section bg-paper" aria-label="Insights posts">
        <div class="container">
            <?php if ($posts_query->have_posts()) : ?>

                <div class="insights-grid js-reveal">
                    <?php while ($posts_query->have_posts()) : $posts_query->the_post(); ?>
                        <article class="insight-card">
                            <?php if (has_post_thumbnail()) : ?>
                                <a href="<?php the_permalink(); ?>" class="insight-card__image-link" tabindex="-1" aria-hidden="true">
                                    <div class="insight-card__image ratio ratio--16-9">
                                        <?php the_post_thumbnail('large', ['loading' => 'lazy', 'class' => 'insight-card__img ratio__content']); ?>
                                    </div>
                                </a>
                            <?php endif; ?>
                            <div class="insight-card__body">
                                <time class="insight-card__date" datetime="<?php echo esc_attr(get_the_date('c')); ?>"><?php echo esc_html(get_the_date('j M Y')); ?></time>
                                <h2 class="h3 insight-card__title">
                                    <a href="<?php the_permalink(); ?>"><?php the_title(); ?></a>
                                </h2>
                                <?php if (has_excerpt()) : ?>
                                    <p class="insight-card__excerpt"><?php the_excerpt(); ?></p>
                                <?php endif; ?>
                                <a href="<?php the_permalink(); ?>" class="insight-card__read-more" aria-label="Read <?php the_title_attribute(); ?>">Read &#8594;</a>
                            </div>
                        </article>
                    <?php endwhile; wp_reset_postdata(); ?>
                </div>

                <?php if ($posts_query->max_num_pages > 1) : ?>
                <div class="insights-pagination">
                    <?php echo paginate_links([
                        'total'     => $posts_query->max_num_pages,
                        'current'   => $paged,
                        'prev_text' => '&#8592; Previous',
                        'next_text' => 'Next &#8594;',
                    ]); ?>
                </div>
                <?php endif; ?>

            <?php else : ?>
                <div class="insights-empty">
                    <p class="kicker">Coming soon</p>
                    <p class="insights-empty__text">Articles are on the way — check back soon.</p>
                </div>
            <?php endif; ?>
        </div>
    </section>

    <?php get_template_part('template-parts/cta'); ?>

</main>

<?php get_footer(); ?>
