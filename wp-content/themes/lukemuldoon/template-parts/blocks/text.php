<?php

/**
 * Flexible content partial: Text
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

	<div class="container">
		<div class="text-container flow">
			<?php the_sub_field('text'); ?>			
		</div>
	</div>
	
</div>
