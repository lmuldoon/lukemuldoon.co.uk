<?php

/**
 * Template Name: Services
 */

$hero      = get_field('hero')      ?: [];
$core      = get_field('core')      ?: [];
$technical = get_field('technical') ?: [];
$design    = get_field('design')    ?: [];
$seo       = get_field('seo')       ?: [];
$ongoing   = get_field('ongoing')   ?: [];

$core_items = [
    $core['item_one']   ?? [],
    $core['item_two']   ?? [],
    $core['item_three'] ?? [],
    $core['item_four']  ?? [],
];

$technical_items = [
    $technical['item_one']   ?? [],
    $technical['item_two']   ?? [],
    $technical['item_three'] ?? [],
    $technical['item_four']  ?? [],
    $technical['item_five']  ?? [],
    $technical['item_six']   ?? [],
    $technical['item_seven'] ?? [],
    $technical['item_eight'] ?? [],
    $technical['item_nine']  ?? [],
];

$seo_items = [
    $seo['item_one']   ?? [],
    $seo['item_two']   ?? [],
    $seo['item_three'] ?? [],
    $seo['item_four']  ?? [],
    $seo['item_five']  ?? [],
    $seo['item_six']   ?? [],
];

$hero_title = $hero['title'] ?? '';

$hero_title = preg_replace(
    '/\*\*(.*?)\*\*/',
    '<span class="accent-underline has-inline-svg">$1'
        . '<svg class="accent-underline__svg" viewBox="0 0 300 14" preserveAspectRatio="none" aria-hidden="true">'
        . '<path d="M3,9 C50,11 90,4 150,7 C200,10 250,4 297,7" stroke="#9FE000" stroke-width="4" fill="none" stroke-linecap="round"/>'
        . '</svg></span>',
    esc_html($hero_title)
);

get_header();
?>

<main id="main-content">

    <!-- ===== HERO ===== -->
    <section class="hero hero__page section bg-chalk about-hero" aria-labelledby="services-heading">
        <div class="container js-reveal">
            <?php if (!empty($hero['kicker'])) : ?><p class="kicker"><?php echo esc_html($hero['kicker']); ?></p><?php endif; ?>
            <?php if (!empty($hero_title))     : ?><h1 id="services-heading" class="h2 hero__heading"><?php echo $hero_title; ?></h1><?php endif; ?>
            <?php if (!empty($hero['text']))   : ?><div class="hero__body"><?php echo wp_kses_post($hero['text']); ?></div><?php endif; ?>
        </div>
    </section>

    <!-- ===== CORE SERVICES ===== -->
    <section class="section bg-paper" aria-label="Core services">
        <div class="container">
            <div class="section-header flow js-reveal">
                <?php if (!empty($core['kicker'])) : ?><p class="kicker"><?php echo esc_html($core['kicker']); ?></p><?php endif; ?>
                <?php if (!empty($core['title']))  : ?><h2><?php echo esc_html($core['title']); ?></h2><?php endif; ?>
            </div>
            <ul class="feature-grid js-reveal" role="list">
                <?php foreach ($core_items as $i => $item) :
                    if (empty($item['title']) && empty($item['text'])) continue;
                ?>
                    <li class="feature-item flow">
                        <span class="feature-item__num" aria-hidden="true"><?php echo str_pad($i + 1, 2, '0', STR_PAD_LEFT); ?></span>
                        <?php if (!empty($item['title'])) : ?><h3 class="feature-item__title"><?php echo esc_html($item['title']); ?></h3><?php endif; ?>
                        <?php if (!empty($item['text']))  : ?><p class="feature-item__desc"><?php echo esc_html($item['text']); ?></p><?php endif; ?>
                    </li>
                <?php endforeach; ?>
            </ul>
        </div>
    </section>

    <!-- ===== TECHNICAL ===== -->
    <section class="section bg-chalk" aria-label="Technical">
        <div class="container">
            <div class="section-header flow js-reveal">
                <?php if (!empty($technical['kicker'])) : ?><p class="kicker"><?php echo esc_html($technical['kicker']); ?></p><?php endif; ?>
                <?php if (!empty($technical['title']))  : ?><h2><?php echo esc_html($technical['title']); ?></h2><?php endif; ?>
            </div>
            <ul class="feature-grid js-reveal" role="list">
                <?php foreach ($technical_items as $i => $item) :
                    if (empty($item['title']) && empty($item['text'])) continue;
                ?>
                    <li class="feature-item flow">
                        <span class="feature-item__num" aria-hidden="true"><?php echo str_pad($i + 1, 2, '0', STR_PAD_LEFT); ?></span>
                        <?php if (!empty($item['title'])) : ?><h3 class="feature-item__title"><?php echo esc_html($item['title']); ?></h3><?php endif; ?>
                        <?php if (!empty($item['text']))  : ?><p class="feature-item__desc"><?php echo esc_html($item['text']); ?></p><?php endif; ?>
                    </li>
                <?php endforeach; ?>
            </ul>
        </div>
    </section>

    <!-- ===== DESIGN & BRANDING ===== -->
    <?php if (!empty($design['title'])) : ?>
    <section class="section bg-paper" aria-label="Design and branding">
        <div class="container">
            <div class="two-col two-col--wide-left">
                <div class="two-col__left flow js-reveal">
                    <?php if (!empty($design['kicker'])) : ?><p class="kicker"><?php echo esc_html($design['kicker']); ?></p><?php endif; ?>
                    <h2><?php echo esc_html($design['title']); ?></h2>
                </div>
                <div class="two-col__right flow js-reveal">
                    <?php if (!empty($design['text'])) : echo wp_kses_post($design['text']); endif; ?>
                </div>
            </div>
        </div>
    </section>
    <?php endif; ?>

    <!-- ===== SEO & LAUNCH ===== -->
    <section class="section bg-chalk" aria-label="SEO and launch">
        <div class="container">
            <div class="section-header flow js-reveal">
                <?php if (!empty($seo['kicker'])) : ?><p class="kicker"><?php echo esc_html($seo['kicker']); ?></p><?php endif; ?>
                <?php if (!empty($seo['title']))  : ?><h2><?php echo esc_html($seo['title']); ?></h2><?php endif; ?>
            </div>
            <ul class="feature-grid js-reveal" role="list">
                <?php foreach ($seo_items as $i => $item) :
                    if (empty($item['title']) && empty($item['text'])) continue;
                ?>
                    <li class="feature-item flow">
                        <span class="feature-item__num" aria-hidden="true"><?php echo str_pad($i + 1, 2, '0', STR_PAD_LEFT); ?></span>
                        <?php if (!empty($item['title'])) : ?><h3 class="feature-item__title"><?php echo esc_html($item['title']); ?></h3><?php endif; ?>
                        <?php if (!empty($item['text']))  : ?><p class="feature-item__desc"><?php echo esc_html($item['text']); ?></p><?php endif; ?>
                    </li>
                <?php endforeach; ?>
            </ul>
        </div>
    </section>

    <!-- ===== ONGOING SUPPORT ===== -->
    <?php if (!empty($ongoing['title'])) : ?>
    <section class="section bg-paper" aria-label="Ongoing support">
        <div class="container">
            <div class="two-col two-col--wide-left">
                <div class="two-col__left flow js-reveal">
                    <?php if (!empty($ongoing['kicker'])) : ?><p class="kicker"><?php echo esc_html($ongoing['kicker']); ?></p><?php endif; ?>
                    <h2><?php echo esc_html($ongoing['title']); ?></h2>
                </div>
                <div class="two-col__right flow js-reveal">
                    <?php if (!empty($ongoing['text'])) : echo wp_kses_post($ongoing['text']); endif; ?>
                </div>
            </div>
        </div>
    </section>
    <?php endif; ?>

    <?php get_template_part('template-parts/cta'); ?>

</main>

<?php get_footer(); ?>
