<?php
/**
 * Template Name: Area
 */

// Fields and schema must be registered before get_header() calls wp_head()
$hero     = get_field('hero')             ?: [];
$location = get_field('location')         ?: [];
$about    = get_field('about_section')    ?: [];
$services = get_field('services_section') ?: [];

$city   = sanitize_text_field($location['city']   ?? '');
$county = sanitize_text_field($location['county'] ?? '');

$hero_title = preg_replace(
    '/\*\*(.*?)\*\*/',
    '<span class="accent-underline has-inline-svg">$1'
    . '<svg class="accent-underline__svg" viewBox="0 0 300 14" preserveAspectRatio="none" aria-hidden="true">'
    . '<path d="M3,9 C50,11 90,4 150,7 C200,10 250,4 297,7" stroke="#9FE000" stroke-width="4" fill="none" stroke-linecap="round"/>'
    . '</svg></span>',
    esc_html($hero['title'] ?? '')
);

if ($city) {
    add_action('wp_head', function() use ($city, $county) {
        $schema = [
            '@context'    => 'https://schema.org',
            '@type'       => 'ProfessionalService',
            'name'        => 'Luke Muldoon - Web Developer',
            'url'         => 'https://lukemuldoon.co.uk',
            'areaServed'  => array_filter([
                '@type'            => 'City',
                'name'             => $city,
                'containedInPlace' => $county ? ['@type' => 'AdministrativeArea', 'name' => $county] : null,
            ]),
            'serviceType' => 'Web Design and Development',
            'provider'    => ['@type' => 'Person', 'name' => 'Luke Muldoon'],
        ];
        echo '<script type="application/ld+json">' . wp_json_encode($schema) . '</script>' . "\n";
    });
}

get_header();
?>

<main id="main-content">

    <!-- ===== HERO ===== -->
    <section class="hero hero__page section bg-chalk about-hero" aria-labelledby="area-heading">
        <div class="container js-reveal">
            <?php if (!empty($hero['kicker'])) : ?><p class="kicker"><?php echo esc_html($hero['kicker']); ?></p><?php endif; ?>
            <?php if (!empty($hero_title))     : ?><h1 id="area-heading" class="h2 hero__heading"><?php echo $hero_title; ?></h1><?php endif; ?>
            <?php if (!empty($hero['text']))   : ?><div class="hero__body"><?php echo wp_kses_post($hero['text']); ?></div><?php endif; ?>
        </div>
    </section>

    <!-- ===== WHY WORK WITH ME ===== -->
    <?php if (!empty($about['title'])) : ?>
    <section class="section bg-paper" aria-label="<?php echo esc_attr($city ? "Why choose a local web designer for {$city}" : 'About'); ?>">
        <div class="container">
            <div class="two-col two-col--wide-left">
                <div class="flow js-reveal">
                    <?php if (!empty($about['kicker'])) : ?><p class="kicker"><?php echo esc_html($about['kicker']); ?></p><?php endif; ?>
                    <h2><?php echo esc_html($about['title']); ?></h2>
                </div>
                <div class="flow js-reveal">
                    <?php echo wp_kses_post($about['text']); ?>
                </div>
            </div>
        </div>
    </section>
    <?php endif; ?>

    <!-- ===== SERVICES ===== -->
    <?php if (!empty($services['title'])) : ?>
    <section class="section bg-chalk" aria-label="Services">
        <div class="container">
            <div class="two-col two-col--wide-left">
                <div class="flow js-reveal">
                    <?php if (!empty($services['kicker'])) : ?><p class="kicker"><?php echo esc_html($services['kicker']); ?></p><?php endif; ?>
                    <h2><?php echo esc_html($services['title']); ?></h2>
                </div>
                <div class="flow js-reveal">
                    <?php echo wp_kses_post($services['text']); ?>
                </div>
            </div>
        </div>
    </section>
    <?php endif; ?>

    <!-- ===== CTA ===== -->
    <?php get_template_part('template-parts/cta'); ?>

</main>

<?php get_footer(); ?>
