<?php

/**
 * Flexible content partial: Shortcode
 */

$spacing = get_sub_field('spacing');
$spacing_style = lm26_get_spacing_css_style($spacing);

?>

<div 
    class="container" 
    data-acf-layout="<?= get_row_layout(); ?>"
    <?= $spacing_style; ?> 
    <?= lm26_format_id_attr(get_sub_field('custom_id')); ?>
>
	<?php echo do_shortcode(get_sub_field('shortcode')); ?>
</div> <!-- /.container -->
