<?php

/**
 * Flexible content partial: Image
 */

$spacing = get_sub_field('spacing');
$spacing_style = lm26_get_spacing_css_style($spacing);
$container_size = get_sub_field('container_size');

$sizes = array(
	'small' => '800px, (max-width: 800px) 100vw',
	'default' => '1280px, (max-width: 1280px) 100vw',
	'fullscreen' => '100vw',
);

$image_field = get_sub_field('image');
	
?>

<div 
	class="" 
	data-acf-layout="<?= get_row_layout(); ?>"
	<?= $spacing_style; ?> 
	<?= lm26_format_id_attr(get_sub_field('custom_id')); ?>
>
	<div class="container container--<?= esc_attr($container_size); ?>">
		<figure class="">
			<?php echo wp_get_attachment_image( 
				$image_field['id'],
				'full', 
				false, 
				array(
					'class' => 'fluid width:100 ',
					'sizes' => $sizes[$container_size],
				) 
			); ?>

			<?php if ( get_sub_field('caption') ): ?>

				<?php if ( 'fullscreen' === $container_size ): ?>
					<div class="figcaption-tab">
						<div class="container">
							<div class="figcaption-tab__inner">
								<figcaption><?php the_sub_field('caption'); ?></figcaption>
							</div>
						</div>
					</div>
				<?php else: ?>
					<figcaption><?php the_sub_field('caption'); ?></figcaption>
				<?php endif; ?>

			<?php endif; ?>
		</figure>
	</div>
</div>
