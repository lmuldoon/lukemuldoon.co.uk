<?php

/**
 * Partial to show the flexible content block: Video
 */

$video_type = get_sub_field('video_type');

$spacing = get_sub_field('spacing');
$spacing_style = lm26_get_spacing_css_style($spacing);
$container_size = get_sub_field('container_size');
?>

<figure class="animated container animated extra-padding container--<?= esc_attr($container_size); ?>" data-acf-layout="<?= get_row_layout(); ?>" <?= $spacing_style; ?> <?= lm26_format_id_attr(get_sub_field('custom_id')); ?>>
	<?php if ('oembed' === $video_type) : ?>

		<?php
		$lazy_cover_type = get_sub_field('lazy_cover_type');
		$lazy_image = get_sub_field('lazy_load_cover_image');
		$lazy_video = get_sub_field('lazy_load_cover_video');
		$video = get_sub_field('video');
		?>
		<?php if ($lazy_image || $lazy_video) : ?>
			<?php $video = lm26_add_autoplay_param_iframe($video); ?>
			<div class="ratio ratio--16-9" data-lazy-frame="<?= esc_attr($video); ?>" data-lazy-frame-type="video">
				<?php if ($lazy_cover_type == 'image') : ?>
					<?php
					echo wp_get_attachment_image(
						$lazy_image['id'],
						'card-full',
						false,
						array(
							'class' => 'ratio__content',
						)
					);
					?>
				<?php else : ?>
					<?php if ($lazy_video) : ?>
						<video class="ratio__content" autoplay playsinline loop muted src="<?= esc_url($lazy_video['url']); ?>"></video>
					<?php endif; ?>
				<?php endif; ?>
				<button class="reset-button" type="button" aria-label="Play video"></button>
			</div> <!-- /.ratio ratio--16-9 -->
		<?php else : ?>
			<div class="ratio ratio--16-9">
				<?php the_sub_field('video'); ?>
			</div> <!-- /.ratio ratio--16-9 -->
		<?php endif; ?>

	<?php endif; ?>

	<?php if ('mp4' === $video_type) : ?>

		<?php
		$video = get_sub_field('mp4');
		?>

		<div class="relative">


			<div class="ratio ratio--16-9">
				<video class="ratio__content" autoplay playsinline loop muted src="<?= esc_url($video['video']['url']); ?>" poster="<?= esc_url($video['poster']['url']); ?>"></video>
			</div> <!-- /.ratio ratio--16-9 -->

		</div> <!-- /.relative -->

	<?php endif; ?>


	<?php if (get_sub_field('caption')) : ?>

		<?php if ('fullscreen' === $container_size) : ?>
			<div class="figcaption-tab">
				<div class="container">
					<div class="figcaption-tab__inner">
						<figcaption><?php the_sub_field('caption'); ?></figcaption>
					</div>
				</div>
			</div>
		<?php else : ?>
			<figcaption><?php the_sub_field('caption'); ?></figcaption>
		<?php endif; ?>

	<?php endif; ?>
</figure> <!-- /.container -->