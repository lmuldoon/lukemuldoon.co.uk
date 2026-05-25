<?php

/**
 * Flexible content partial: Image / Text Block
 */

$spacing = get_sub_field('spacing');
$spacing_style = lm26_get_spacing_css_style($spacing);

$image_placement = get_sub_field('image_placement');

?>

<div 
	class="" 
	data-acf-layout="<?= get_row_layout(); ?>"
	<?= $spacing_style; ?> 
	<?= lm26_format_id_attr(get_sub_field('custom_id')); ?>
>
	<div class="container">
		
		<div class="image-text image-text--image-<?= $image_placement; ?>">
			<div class="image-text__content flow">
				
				<?php if ( get_sub_field('title') ): ?>
					<h2><?php the_sub_field('title'); ?></h2>
				<?php endif; ?>

				<div class="flow">
					<?php the_sub_field('text'); ?>

					<?php if ( $link = get_sub_field('link') ): ?>
						<a 
							class="button"
							href="<?= $link['url']; ?>" 
							<?php if ( isset($link['target']) && '' !== $link['target'] ): ?>
								target="<?= $link['target']; ?>"
							<?php endif; ?>
						>
							<?= $link['title']; ?>
						</a>
					<?php endif; ?>
				</div>

			</div>  
			<figure class="image-text__image">
				<?php
					$image_field = get_sub_field('image');
					echo wp_get_attachment_image( 
						$image_field['id'],
						'full', 
						false, 
						array(
							'class' => 'width:100 fluid',
							'sizes' => '1200px',
						) 
					); 
				?>
				<?php if ( get_sub_field('caption') ): ?>
					<figcaption><?= get_sub_field('caption'); ?></figcaption>
				<?php endif; ?>
			</figure>
		</div>

	</div>
</div>
