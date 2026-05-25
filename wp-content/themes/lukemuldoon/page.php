<?php

/**
 * The template for displaying all pages.
 *
 * This is the template that displays all pages by default.
 * Please note that this is the WordPress construct of pages
 * and that other 'pages' on your WordPress site will use a
 * different template.
 */

get_header();

?>

<?php while ( have_posts() ): ?>
	<?php
		the_post();
	?>

	<article id="post-<?php the_ID(); ?>" <?php post_class(); ?> >

		<div class="container">
					
			<h1><?= get_the_title(); ?></h1>

			<?php the_content(); ?>

		</div> <!-- /.container -->

		<?php //get_template_part('template-parts/layout-blocks'); ?>

	</article><!-- #post -->
	
<?php endwhile; ?>

<?php get_footer(); ?>
