<?php

/**
 * Flexible content partial: Subheading + Text
 */

global $post;

$spacing = get_sub_field('spacing');
$spacing_style = lm26_get_spacing_css_style($spacing);
$larger = get_sub_field('larger');
$larger_sub = get_sub_field('larger_sub');


$text = get_sub_field('text');

?>

<div 
    class="" 
    data-acf-layout="<?= get_row_layout(); ?>"
    <?= $spacing_style; ?> 
    <?= lm26_format_id_attr(get_sub_field('custom_id')); ?>
>

    <div class="container">
        <div class="grid grid-cols-1 lg:grid-cols-2 animated justify-between lg:gap-x-20 xl:gap-x-40 gap-y-4 flow">
            
            <?php if ( get_sub_field('subheading') ): ?>
                <h2 class="<?= $larger_sub == true ? 'h2 uppercase' : 'h3'; ?>">
                    <?php the_sub_field('subheading'); ?>
                </h2>
            <?php elseif ( 'right' === $text_pos && $text ): ?>
                <div class="__for-spacing-only" role="presentation" aria-hidden="true">&nbsp;</div>
            <?php endif ?>

            <?php if ( $text ): ?>
                <div class="flow format-links max-w-2xl lg:mt-0 <?= $larger == true ? 'h3' : ''; ?>">
                    <?= $text; ?>
                </div> <!-- /.flow -->
            <?php endif; ?>

        </div> <!-- /.flex -->
    </div>

</div>
