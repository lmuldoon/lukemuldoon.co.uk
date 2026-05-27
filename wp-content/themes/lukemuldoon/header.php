<?php

/**
 * The template for displaying the header.
 */

$DOMAIN = 'lukemuldoon.co.uk';
$re = "/^(?:www\.)?" . str_replace('.', "\.", $DOMAIN) . "$/"; // escape dots
$IS_LIVE = preg_match($re, $_SERVER['SERVER_NAME'] ?? '');

?>
<!DOCTYPE html>
<html <?php html_class(); ?> <?php language_attributes(); ?>>

<head>
	<meta charset="<?php bloginfo('charset'); ?>" />
	<meta name="viewport" content="width=device-width, initial-scale=1.0" />

	<title><?php trim(wp_title('') ?? ''); ?></title>

	<!-- Start Favicons -->

	<!-- End Favicons -->

	<?php wp_head(); ?>

	<script>
		document.documentElement.className = document.documentElement.className.replace(/\bno-js\b/g, 'js');
	</script>

	<?php if ($IS_LIVE) : ?>

		<!-- Cookie Consent -->

		<link rel="preconnect" href="https://www.googletagmanager.com">
		<!-- Google Analytics -->
		<!-- Google tag (gtag.js) -->
		<script async src="https://www.googletagmanager.com/gtag/js?id=G-1WPMXV6TRZ"></script>
		<script>
			window.dataLayer = window.dataLayer || [];

			function gtag() {
				dataLayer.push(arguments);
			}
			gtag('js', new Date());

			gtag('config', 'G-1WPMXV6TRZ');
		</script>

	<?php endif; ?>
</head>

<body <?php body_class(); ?>>

	<a class="button skip-link" href="#main">
		Skip to main content
	</a>

	<?php get_template_part('template-parts/header/mobile-nav'); ?>

	<header class="site-header">
		<?php get_template_part('template-parts/announcement-bar'); ?>
		<div class="container container--wide">
			<a class="site-logo" href="<?php echo home_url(); ?>" aria-label="Luke Muldoon | Web Developer">
				<?php include_asset('images/logo.svg'); ?>
			</a>
			<?php get_template_part('template-parts/site-nav'); ?>
		</div>
	</header> <!-- /.site-header -->

	<?php get_template_part('template-parts/wrapper/start'); ?>