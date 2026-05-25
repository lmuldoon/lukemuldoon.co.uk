<?php

try {
	$menu = new WP_Menu_Query(array(
		'location' => 'header-menu',
	));
} catch (Exception $e) {
	return;
}

?>

<?php // using blank element for shade to easily detect clicks 
?>
<div class="menu-body-shade js-mobile-nav-shade"></div>
<div class="mobile-nav js-mobile-nav">

	<button class="mobile-nav__close js-close-menu flex gap-2 items-center" type="button" aria-label="Close menu">
		<?php include_asset('images/svgs/petal-left.svg'); ?>
		Close
	</button>

	<ul class="mobile-nav__list">

		<?php while ($menu->have_items()) : ?>

			<?php
			$item = $menu->the_item();

			$classes = $item->classes;
			$classes[] = 'mobile-nav__primary';


			if (156 === $item->object_id && is_singular(['event', 'see-and-do', 'offer', 'food-and-drink'])) {
				$classes[] = 'is-current';
			}

			if (172 === $item->object_id && is_singular(['work-space'])) {
				$classes[] = 'is-current';
			}

			if (252 === $item->object_id && is_singular(['post'])) {
				$classes[] = 'is-current';
			}


			if ($item->is_current() || $item->has_current_child()) {
				$classes[] = 'is-current';
			}

			if ($item->has_children()) {
				$classes[] = 'has-children';
			}

			if ($menu->current_item === $menu->found_items) {
				$classes[] = '_last-item';
			}
			?>
			<li class="<?= esc_attr(implode(' ', $classes)); ?>">

				<?php if ($item->has_children()) : ?>

					<button  class="mobile-nav__item js-mobile-nav__toggle" type="button">
						<?= $item->title; ?>
						<?php if ($item->has_children()) {
							include_asset('images/svgs/arrow-down.svg');
						} ?>
					</button>

				<?php else : ?>

					<a  class="mobile-nav__item" href="<?= esc_url($item->url); ?>" <?= ($item->target ? 'target="' . $item->target . '"' : ''); ?> <?php if ($item->is_current()) : ?> aria-current="page" <?php endif; ?>>
						<?= $item->title; ?>
						
					</a>

				<?php endif; ?>

				<?php
				$children = $item->get_children();
				?>
				<?php if ($children->have_items()) : ?>
					<ul class="mobile-nav__sub relative" style="<?php if ($color) : ?>--menu-color: <?php echo $color; ?>; <?php endif; ?>" >
						<li class="mobile-nav__sub-inner">

							<ul class="mobile-nav__sub-list">
							<?php $index = 0; ?>
								<?php while ($children->have_items()) : ?>
									<?php
									$child = $children->the_item();

									$classes = $child->classes;

									if ($child->is_current() || $child->has_current_child()) {
										$classes[] = 'is-current';
									}
									?>
									<li class="<?= esc_attr(implode(' ', $classes)); ?>">
										<a class="mobile-nav__sub-item" href="<?= esc_url($child->url); ?>" <?= ($child->target ? 'target="' . $child->target . '"' : ''); ?> <?php if ($child->is_current()) : ?> aria-current="page" <?php endif; ?>>
											<span><?= $child->title; ?></span>
										</a>
									</li>
									<?php $index++; ?>
								<?php endwhile; ?>

							</ul>

						</li>


					</ul> <!-- /.submenu -->
				<?php endif; ?>

			</li>

		<?php endwhile; ?>




	</ul> <!-- /.mobile-nav__list -->

</div> <!-- /.mobile-nav -->