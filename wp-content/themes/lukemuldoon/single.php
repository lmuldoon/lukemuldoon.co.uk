<?php

/**
 * The Template for displaying all single posts
 *
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

	</article><!-- #post -->
	
<?php endwhile; ?>

<?php get_footer(); ?>
