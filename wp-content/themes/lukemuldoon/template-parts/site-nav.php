<?php
	
/**
 * Template part for the main navigation menu.
 */

try {
	$menu = new WP_Menu_Query( array(
		'location' => 'header-menu',
	) );
} catch (Exception $e) {
	return;	
}

if ( !$menu->have_items() ) {
	return;
}

?>

<nav class="site-nav js-site-nav" aria-labelledby="site-nav-label">

	<h2 id="site-nav-label" class="sr-only">Main Menu</h2>

	<button class="site-nav-toggle hamburger hamburger--slider-r js-site-nav-toggle" type="button" aria-label="Toggle menu">
		<span class="hamburger-box">
			<span class="hamburger-inner"></span>
		</span>
	</button>
	
	<ul class="site-nav__list">
		
		<?php while ( $menu->have_items() ): ?>

			<?php
				$item = $menu->the_item();
				$classes = $item->classes;

				if ( $item->is_current() || $item->has_current_child() ) {
					$classes[] = 'is-current';
				}

				if ( $item->has_children() ) {
					$classes[] = 'has-children';
				}
			?>
			<li class="<?= esc_attr( implode( ' ', $classes ) ); ?>">
				
				<a 
					class="site-nav__item" 
					href="<?= esc_url( $item->url ); ?>" 
					<?= ( $item->target ? 'target="' . esc_attr($item->target) . '"' : '' ); ?>
					<?php if ( $item->is_current() ): ?>
						aria-current="page"
					<?php endif ?>
				>
					<?= esc_html($item->title); ?>
				</a>

			</li>

		<?php endwhile; ?>
		
	</ul>

</nav>
