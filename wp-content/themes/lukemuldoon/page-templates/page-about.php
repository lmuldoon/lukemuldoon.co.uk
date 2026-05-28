<?php
/**
 * Template Name: About
 *
 * @package lukemuldoon
 */

get_header();

$hero       = get_field('hero')        ?: [];
$experience = get_field('experience')  ?: [];
$how_i_build = get_field('how_i_build') ?: [];
$design     = get_field('design')      ?: [];
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

    <!-- ===== HERO / INTRO ===== -->
    <section class="hero hero__page section bg-chalk" aria-labelledby="about-heading">
        <div class="container js-reveal">
            <?php if (!empty($hero['kicker'])) : ?><p class="kicker"><?php echo esc_html($hero['kicker']); ?></p><?php endif; ?>
            <?php if (!empty($hero_title)) : ?><h1 id="about-heading" class="h2 hero__heading"><?php echo $hero_title; ?></h1><?php endif; ?>
            <?php if (!empty($hero['text'])) : ?><div class="hero__body"><?php echo wp_kses_post($hero['text']); ?></div><?php endif; ?>
        </div>
    </section>

    <!-- ===== WORKSPACE IMAGE ===== -->
    <?php if (has_post_thumbnail()) : ?>
    <div class="about-image-break" aria-hidden="true">
        <div class="container">
            <?php the_post_thumbnail('full', [
                'class'   => 'about-image-break__img',
                'alt'     => '',
                'loading' => 'lazy',
            ]); ?>
        </div>
    </div>
    <?php endif; ?>

    <!-- ===== EXPERIENCE + HOW I BUILD ===== -->
    <section class="section bg-paper" aria-label="Experience and approach">
        <div class="container">
            <div class="image-content-list">

                <div class="image-content-item js-reveal">
                    <?php if (!empty($experience['image'])) : ?>
                        <figure class="image-content-item__media card__media ratio ratio--16-9">
                            <?php echo wp_get_attachment_image($experience['image'], 'large', false, [
                                'loading' => 'lazy',
                                'class'   => 'ratio__content',
                            ]); ?>
                        </figure>
                    <?php endif; ?>
                    <div class="image-content-item__content flow js-reveal">
                        <?php if (!empty($experience['kicker'])) : ?><p class="kicker"><?php echo esc_html($experience['kicker']); ?></p><?php endif; ?>
                        <?php if (!empty($experience['title']))  : ?><h2><?php echo esc_html($experience['title']); ?></h2><?php endif; ?>
                        <?php if (!empty($experience['text']))   : ?><?php echo wp_kses_post($experience['text']); ?><?php endif; ?>
                    </div>
                </div>

                <div class="image-content-item js-reveal">
                    <?php if (!empty($how_i_build['image'])) : ?>
                        <figure class="image-content-item__media card__media ratio ratio--16-9">
                            <?php echo wp_get_attachment_image($how_i_build['image'], 'large', false, [
                                'loading' => 'lazy',
                                'class'   => 'ratio__content',
                            ]); ?>
                        </figure>
                    <?php endif; ?>
                    <div class="image-content-item__content flow js-reveal">
                        <?php if (!empty($how_i_build['kicker'])) : ?><p class="kicker"><?php echo esc_html($how_i_build['kicker']); ?></p><?php endif; ?>
                        <?php if (!empty($how_i_build['title']))  : ?><h2><?php echo esc_html($how_i_build['title']); ?></h2><?php endif; ?>
                        <?php if (!empty($how_i_build['text']))   : ?><?php echo wp_kses_post($how_i_build['text']); ?><?php endif; ?>
                    </div>
                </div>

            </div>
        </div>
    </section>

    <!-- ===== DESIGN & BRAND ===== -->
    <section class="section bg-chalk" aria-label="Design and brand">
        <div class="container">
            <div class="two-col two-col--wide-left js-reveal">
                <div class="flow">
                    <?php if (!empty($design['kicker'])) : ?><p class="kicker"><?php echo esc_html($design['kicker']); ?></p><?php endif; ?>
                    <?php if (!empty($design['title']))  : ?><h2><?php echo esc_html($design['title']); ?></h2><?php endif; ?>
                </div>
                <div class="flow">
                    <?php if (!empty($design['text'])) : ?><?php echo wp_kses_post($design['text']); ?><?php endif; ?>
                </div>
            </div>
        </div>
    </section>

    <!-- ===== CTA ===== -->
    <?php get_template_part('template-parts/cta'); ?>

</main>

<?php get_footer(); ?>
