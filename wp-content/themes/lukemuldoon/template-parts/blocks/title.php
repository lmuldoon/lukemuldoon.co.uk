<?php

/**
 * Flexible content partial: Title
 */

$spacing = get_sub_field('spacing');
$spacing_style = lm26_get_spacing_css_style($spacing);

?>

<div 
	class="" 
	data-acf-layout="<?= get_row_layout(); ?>"
	<?= $spacing_style; ?> 
	<?= lm26_format_id_attr(get_sub_field('custom_id')); ?>
>

	<div class="container flow text-<?= get_sub_field('alignment'); ?>">
		<h2><?php the_sub_field('title'); ?></h2>
	</div>
	
</div>
