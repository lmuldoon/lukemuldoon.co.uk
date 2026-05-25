<?php

/**
 * Partial to output the layout blocks for a page.
 */

global $_section_open;

?>

<?php if ( have_rows('layout') ): ?>
	<div class="cms-blocks">
		
		<?php while ( have_rows('layout') ): the_row(); ?>
			<?php get_template_part('template-parts/blocks/'.get_row_layout()); ?>
		<?php endwhile; ?>
		<?php
			if ( $_section_open ) {
				echo '</div>'; // close final section
			}
		?>

	</div> <!-- /.cms-blocks -->
<?php endif; ?>
