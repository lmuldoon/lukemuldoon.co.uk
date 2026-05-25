<?php
/**
 * Template Name: Legal
 *
 * Used for Privacy Policy, Terms, and similar text-heavy legal pages.
 * Content is written directly in the WordPress editor.
 *
 * @package lukemuldoon
 */

get_header();

while ( have_posts() ) :
    the_post();
?>

<article class="legal-page">

    <header class="legal-page__header">
        <div class="container">
            <p class="legal-page__label"><?php echo esc_html( get_bloginfo( 'name' ) ); ?></p>
            <h1 class="legal-page__title"><?php the_title(); ?></h1>
            <p class="legal-page__meta">Last updated <?php echo esc_html( get_the_modified_date( 'j F Y' ) ); ?></p>
        </div>
    </header>

    <div class="legal-page__body">
        <div class="container">
            <div class="legal-page__content prose">
                <?php the_content(); ?>
            </div>
        </div>
    </div>

</article>

<?php endwhile; ?>

<?php get_footer(); ?>
