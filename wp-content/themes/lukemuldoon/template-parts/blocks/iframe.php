<?php

/**
 * Partial to show the flexible content block: IFrame
 */

$lazy_image = get_sub_field('lazy_load_cover_image');
$iframe = get_sub_field('iframe');

$spacing = get_sub_field('spacing');
$spacing_style = lm26_get_spacing_css_style($spacing);
$container_size = get_sub_field('container_size');

?>

<figure 
	class="container animated container--<?= esc_attr($container_size); ?> _acffc-<?= get_row_layout(); ?>" <?= $spacing_style; ?>
	<?= lm26_format_id_attr(get_sub_field('custom_id')); ?>
>
	<?php if ( $lazy_image ): ?>
		<div class="ratio ratio--16-9" data-lazy-frame="<?= esc_attr($iframe); ?>" data-lazy-frame-type="iframe">
			<?php
				echo wp_get_attachment_image( 
					$lazy_image['id'],
					'listing-large',
					false, 
					array(
						'class' => 'ratio__content',
					) 
				); 
			?>
			<button type="button" aria-label="View content"></button>
		</div> <!-- /.ratio ratio--16:9 -->
	<?php else: ?>
		<div class="ratio ratio--16-9">
			<?= $iframe; ?>
		</div> <!-- /.ratio ratio--16:9 -->
	<?php endif; ?>

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
</figure> <!-- /.container -->
