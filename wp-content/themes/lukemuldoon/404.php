<?php

/**
 * The template for displaying 404 pages (Not Found).
 */

get_header();

?>

<section class="error-404-page" aria-labelledby="error-heading">

    <span class="error-404__bg-numeral" aria-hidden="true">404</span>

    <div class="container">
        <div class="error-404__content">

            <p class="error-404__label">Error 404 — Not found</p>

            <h1 class="error-404__heading" id="error-heading">
                This page<br>doesn&rsquo;t exist.
            </h1>

            <p class="error-404__body">It may have been moved, deleted, or the URL could be wrong. Either way, let&rsquo;s get you back somewhere useful.</p>

            <a href="<?php echo esc_url( home_url( '/' ) ); ?>" class="error-404__back">
                Return home
            </a>

        </div>
    </div>

</section>

<?php get_footer(); ?>
