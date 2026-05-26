<?php

// These functions will be used by lm26_setup_theme() in functions.php

/**
 * Get your Google API Credentials from https://console.developers.google.com/apis/
 * Make sure to set up restrictions so that the key can only be used from certain HTTP Referrers.
 */
define( 'GOOGLE_API_KEY', '' );

add_action( 'wp_head', 'lm26_preload_critical_fonts', 1 );
function lm26_preload_critical_fonts() {
	$font_url = get_theme_file_uri( 'assets/fonts/Space_Grotesk/space-grotesk-v22-latin-700.woff2' );
	echo '<link rel="preload" href="' . esc_url( $font_url ) . '" as="font" type="font/woff2" crossorigin>' . "\n";
}

/**
 * Registers and enqueues the stylesheets that the theme requires.
 */
function lm26_enqueue_styles() {	
	if( !is_admin() ) {	
		// remove Gutenberg CSS
		wp_dequeue_style('wp-block-library');
		wp_dequeue_style('wp-block-library-theme');

		/**
		 * Load default theme stylesheet.
		 * false -> No dependancies.
		 */
		wp_register_style( 'theme_css', get_theme_file_uri( 'assets/public/css/screen.min.css' ), false );
		wp_enqueue_style( 'theme_css' );

		/**
		 * Load print stylesheet.
		 * false -> No dependancies.
		 */
		wp_register_style( 'print_css', get_theme_file_uri( 'assets/public/css/print.min.css' ), false, false, 'print' );
		wp_enqueue_style( 'print_css' );
	}
}

/**
 * Registers and enqueues the scripts that the theme requires.
 */
function lm26_enqueue_scripts() {
	if ( !is_admin() ) {

		/**
		 * Load header scripts.
		 * No dependancies, in header -> default for wp_register_script().
		 */
		$theme_root = trailingslashit(get_theme_file_path());
		if ( file_exists($theme_root.'assets/public/js/header.min.js') && is_readable($theme_root.'assets/public/js/header.min.js') ) {
			wp_register_script ( 'header_js', get_theme_file_uri( 'assets/public/js/header.min.js' ) );
			wp_enqueue_script ( 'header_js' );
		}
		
		/**
		 * Load footer scripts
		 * Dependancies: jQuery
		 * false -> No version string (versions will be revisioned by Gulp.js)
		 * true  -> Load in footer
		 */
		wp_register_script ( 'footer_js', get_theme_file_uri( 'assets/public/js/footer.min.js' ), array( 'jquery' ), false, true );
		$footer_js_args = array(
			'template_directory_uri'   => trailingslashit( get_template_directory_uri() ),
			'stylesheet_directory_uri' => trailingslashit( get_stylesheet_directory_uri() ),
			'ajaxurl'                  => admin_url( 'admin-ajax.php' ),
			'scanner_nonce'            => wp_create_nonce( 'lm_scan_nonce' ),
		);
		wp_localize_script( 'footer_js', 'wp', $footer_js_args );
		wp_enqueue_script ( 'footer_js' );

	}
}

/**
 * Register and enqueue Google Maps scripts for pages that require it.
 */
function lm26_enqueue_google_scripts() {
	global $post;

	if ( 
		!is_admin() && 
		'' !== GOOGLE_API_KEY && 
		apply_filters( 'page_has_google_map', false, $post ) 
	) {

		/**
		 * Load Google Maps JavaScript API.
		 * No dependancies
		 * false -> No version string
		 * true  -> Load in footer
		 */
		wp_register_script ("google-maps-api", "https://maps.googleapis.com/maps/api/js?libraries=places&key=" . GOOGLE_API_KEY, array(), false, true);
		wp_enqueue_script ("google-maps-api");

		/**
		 * Load script to initialise all maps on page.
		 * Dependancies: jQuery
		 * false -> No version string
		 * true  -> Load in footer
		 */
		wp_register_script ("initialise-google-maps", get_theme_file_uri( 'assets/public/js/google_maps.min.js' ), array( 'jquery' ), false, true);
		wp_enqueue_script ("initialise-google-maps");

	}
}

/**
 * Add the Google API key to the Advanced Custom Fields plugin.
 * @param  array $api  The API credentials in use.
 * @return array
 */
add_filter( 'acf/fields/google_map/api', 'lm26_add_acf_api_creds' );
function lm26_add_acf_api_creds( $api ) {
	if ( '' !== GOOGLE_API_KEY ) {
		$api['key'] = GOOGLE_API_KEY;
	}

	return $api;
}

/**
 * Include an asset file into the document, only if it exists.
 * Note: fails silently if path does not exist.
 * @param  string $path The path to the asset relative to the theme directory.
 */
function include_asset( $path ) {
	$path = get_theme_file_path( $path );

	if ( $path && file_exists($path) && is_readable($path) ) {
		include $path;
	}
}
