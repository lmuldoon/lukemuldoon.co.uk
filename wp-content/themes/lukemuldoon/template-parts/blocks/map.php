<?php
if (get_sub_field('map')) :
    wp_register_script("google-maps-api", "https://maps.googleapis.com/maps/api/js?libraries=places&key=" . GOOGLE_API_KEY, array(), false, true);
    wp_enqueue_script("google-maps-api");
    wp_register_script("initialise-google-maps", get_theme_file_uri('public/js/google_maps.min.js'), array('jquery'), false, true);
    wp_enqueue_script("initialise-google-maps");
endif;

$spacing = get_sub_field('spacing');
$spacing_style = lm26_get_spacing_css_style($spacing);
$container_size = get_sub_field('container_size');
?>
<div class="" data-acf-layout="<?= get_row_layout(); ?>" <?= $spacing_style; ?> <?= lm26_format_id_attr(get_sub_field('custom_id')); ?>>
    <div class="container <?= 'fullscreen' === $container_size ? '' : 'animated'; ?> container--<?= esc_attr($container_size); ?>">
        <div class="map-image">
            <div class="map ratio ratio--16-9">
                <?php
                $location = get_sub_field('map');
                if (!empty($location)) :
                ?>
                    <div class="map__embed js-google-map " id="google-map">
                        <div class="map__marker js-google-map__marker" data-lat="<?php echo $location['lat']; ?>" data-lng="<?php echo $location['lng']; ?>"></div>
                    </div>
                <?php endif; ?>
            </div>
        </div>
    </div>
</div>