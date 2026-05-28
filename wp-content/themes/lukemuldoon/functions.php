<?php

/**
 * Load a separate functions file.
 * @param  string $filename The filename to load.
 */
function _load_include( $filename ) {
  require_once 'includes/' . $filename . '.php';
}

/**
 * Load included files.
 */
_load_include( 'enqueue-assets' );
_load_include( 'ajax-scanner' );
_load_include( 'ajax-contact' );
_load_include( 'dom-classes' );
_load_include( 'wordpress-menu-query/load' );
_load_include( 'image-sizes' );
_load_include( 'theme-colors' );
_load_include( 'register-shortcodes' );
_load_include( 'acf-fc-titles' );
_load_include( 'replace-tiny-mce-formats/replace-tiny-mce-formats' );

/**
 * Setup the theme.
 */
add_action( 'after_setup_theme', 'lm26_setup_theme' );
function lm26_setup_theme() {

	add_theme_support('post-thumbnails');
	load_theme_textdomain( 'lm26' );

	// Turn off the admin bar. Opinionated.
	show_admin_bar( false );

	/**
	 * Register menus
	 */
	register_nav_menus( array(
    'header-menu' => __( 'Header Menu' ),
    'footer-menu' => __( 'Footer Menu' ),
  ) );

	/**
	 * Add custom image sizes
	 */
	foreach ( lm26_get_theme_image_sizes() as $crop_name => $crop_values) {
		add_image_size( $crop_name, $crop_values['width'], $crop_values['height'], $crop_values['hard_crop'] );
	}


	/**
	 * Remove query strings from loaded resources
	 */
	add_action( 'script_loader_src', 'lm26_remove_script_version' );
	add_action( 'style_loader_src', 'lm26_remove_script_version' );

	
	/**
	 * Load our theme resources
	 */
	add_filter( 'theme_file_uri', 'lm26_revision_assets', 20, 2 );
	add_action( 'wp_enqueue_scripts', 'lm26_enqueue_styles' );
	add_action( 'wp_enqueue_scripts', 'lm26_enqueue_scripts' );
	add_action( 'wp_enqueue_scripts', 'lm26_enqueue_google_scripts' );

	/**
	 * Wrap video embeds in WordPress WYSIWYG fields in div.ratio containers
	 */
	add_filter( 'embed_oembed_html', 'lm26_wrap_oembed_in_ratio_container', 99, 4 );
	
	/**
	 * Clean up header tags
	 * @see https://crunchify.com/how-to-clean-up-wordpress-header-section-without-any-plugin/ 
	 */
	remove_action('wp_head', 'rsd_link');
	remove_action('wp_head', 'wlwmanifest_link');
	remove_action('wp_head', 'wp_shortlink_wp_head');
	remove_action('wp_head', 'rest_output_link_wp_head', 10);
	remove_action('wp_head', 'wp_oembed_add_discovery_links', 10);
	remove_action('template_redirect', 'rest_output_link_header', 11, 0);

	// Add ACF styles and scripts
	if ( class_exists('ACF') ) {
		add_action( 'admin_head', 'lm26_admin_acf_styles' );
		add_action( 'admin_footer', 'lm26_admin_acf_scripts', 999 );
		add_filter('acf/update_value/name=custom_id', 'lm26_format_acf_custom_id_value', 10, 3);
	}
}

/**
 * Remove query string from enqueued assets.
 * Opinionated, as this can cause assets to be cached more often.
 * Plugin or WP core assets will be especially susceptible to this, as they will
 * normally use this query string to force the new version.
 * A good cache invalidation strategy will be needed to ensure this works as intended.
 * @param  string $src The URI of an enqueued asset.
 * @return string      
 */
function lm26_remove_script_version( $src ) {
	if ( !is_admin() ) {
		$src = remove_query_arg( 'ver', $src );
	}
	
	return $src;
}

/**
 * Replace any revisioned assets with their new revision names.
 * @param  string $uri  The full URI to the asset.
 * @param  string $file The original file path supplied to the get_theme_file_uri function.
 * @return string
 */
function lm26_revision_assets( $uri, $file ) {
	$theme_dir = trailingslashit( get_stylesheet_directory() );
	$theme_uri = trailingslashit( get_stylesheet_directory_uri() );

	$manifest_filepath = $theme_dir . 'assets/public/manifest.json';

	if ( file_exists( $manifest_filepath ) && is_readable( $manifest_filepath ) ) {
		$manifest = json_decode( file_get_contents( $manifest_filepath ), true );

		// sanitise URI
		$clean_uri = $uri;
		$clean_uri = str_replace('.min', '', $clean_uri); // remove minified extension
		$clean_uri = basename($clean_uri); // strip folder path

		foreach ($manifest as $original_name => $revisioned_name) {
			if ( preg_match( "#" . $original_name . "$#", $clean_uri ) ) {
				$uri = $theme_uri . $revisioned_name;
				break;
			}
		}
	}

	return $uri;
}

add_filter('mce_buttons_2', 'lm26_add_super_subscript_mce_buttons');
function lm26_add_super_subscript_mce_buttons( $buttons ) {  
    array_unshift($buttons, 'superscript', 'subscript');

    return $buttons;
}

add_filter('acf/fields/wysiwyg/toolbars', 'lm26_add_super_subscript_acf_mce_buttons');
function lm26_add_super_subscript_acf_mce_buttons( $buttons ) {  
    array_unshift($buttons['Full'][2], 'superscript', 'subscript');

    return $buttons;
}

function lm26_admin_acf_styles() {
	?><style>
		.acf-flexible-content .layout.-modal {
			max-width: 1080px;
		}

		.layout[data-layout="section"] ~ .layout:not(.-modal) {
			margin-left: 50px;
		}

		.layout.layout:not(.-modal)[data-layout="section"] {
			margin-left: 0;
		}

		.acf-fc-layout-preview {
			display: block;
			max-width: 90%;
			margin-left: 26px;
		}

		.acf-fc-layout-preview__image,
		.acf-fc-layout-preview__swatch {
			display: block;
		}

		.acf-postbox.seamless > .postbox-header {
			display: none;
		}

		.mce-menu .mce-menu-item.mce-active.mce-menu-item-normal, 
		.mce-menu .mce-menu-item.mce-active.mce-menu-item-preview, 
		.mce-menu .mce-menu-item.mce-selected, 
		.mce-menu .mce-menu-item:focus, 
		.mce-menu .mce-menu-item:hover {
			color: white !important;
		}
	</style><?php
}

function lm26_admin_acf_scripts() {
	?><script>
		// Dynamically update custom_id field matching specific pattern
		jQuery(document).on('keyup change', '[data-name="custom_id"] input[type="text"]', function(event) {
			let val = jQuery(this).val().replace(/[^A-Za-z0-9\s_-]/g, '');
			val = val.replace(/\s/g, '-');

			jQuery(this).val(val.toLowerCase());
		});
	</script><?php
}

/**
 * Wrap any Vimeo or YouTube embeds in WYSIWYG fields
 * in div.ratio containers for responsive scaling.
 * @see https://developer.wordpress.org/reference/hooks/embed_oembed_html/
 *      for parameter and return value explanations.
 */
function lm26_wrap_oembed_in_ratio_container( $cache, $url, $attr, $post_ID ) {
  // Add these classes to all embeds.
  $classes = array(
  );
  $anchor_id = '';

  if ( 
  	false !== strpos( $url, 'vimeo.com' ) || 
  	false !== strpos( $url, 'youtube.com' )
  ) {
  	// For video providers, create a 16:9 container
    $classes[] = 'ratio';
    $classes[] = 'ratio--16-9';

    // Add the video ID as an anchor ID
    $match = preg_match('/(\d+)/', $url, $matches);
    if ( $matches ) {
	    $anchor_id = $matches[1];
    }
  }

  $ret = sprintf( 
  	'<div %s class="%s">%s</div>',
  	( $anchor_id ? 'id="' . $anchor_id . '"' : '' ),
  	esc_attr( implode( ' ', $classes ) ),
  	$cache
  );

  return $ret;
}


function lm26_format_acf_custom_id_value( $value, $post_id, $field ) {
	$value = lm26_get_custom_id($value);
	return $value;
}

/**
 * Get an adjacent block in the current have_rows() loop.
 * @param  integer $direction -1, < 0 	  == previous block,
 *                            0, +1, >= 0 == next block.
 * @return array
 */
function lm26_acf_get_adjacent_layout( $direction = 1 ) {
	global $post;

	$loop = acf_get_loop('active');
	$direction = $direction >= 0 ? 1 : -1;

	$adjacent_index = $loop['i'] + $direction;

	if ( !isset($loop['value'][$adjacent_index]) ) {
		return false;
	}

	$value = acf_format_value( $loop['value'], $loop['post_id'], $loop['field'] );
	$block = acf_maybe_get( $value, $adjacent_index );

	return $block;
}

/**
 * Transform a string into an ID version of itself.
 * E.g. Who We Work With -> who-we-work-with.
 * @param  string $id_str The text to transform.
 * @return string         
 */
function lm26_get_custom_id( $id_str ) {
	if ( !$id_str ) {
		return '';
	}

	$id = preg_replace('/[^A-Za-z0-9\s_-]/', '', $id_str);
	$id = sanitize_title_with_dashes($id);

	return $id;
}

/**
 * Format arbitrary text into an ID attribute string.
 * @param  string $id_str The text to format. Pulled from database so could be false.
 * @return string
 */
function lm26_format_id_attr( $id_str ) {
	// handle empty database value
	if ( !$id_str ) {
		return '';
	}

	return sprintf('id="%s"', esc_attr(lm26_get_custom_id($id_str)));
}

function lm26_get_spacing_css_style( $spacing ) {
	$above = $spacing['above'];
	$below = $spacing['below'];

	if ( '' === $above && '' === $below ) {
		return '';
	}

	// above must be padding to prevent margin collapse
	$above_style = '';
	if ( '' !== $above ) {
		$above_style = sprintf('--block-margin-top: %dvw;', $above);
	}

	$below_style = '';
	if ( '' !== $below ) {
		$below_style = sprintf('--block-margin-bottom: %dvw;', $below);
	}

	return sprintf(
		'style="%s %s"',
		$above_style,
		$below_style
	);
}

/**
 * Add the autoplay attribute to an iframe HTML.
 * @param  string $iframe The entire '<iframe>...</iframe>' HTML output.
 * @return string         
 */
function lm26_add_autoplay_param_iframe( $iframe ) {
	$matches = array();
	preg_match('/src=\"([^\"]+)\"/', $iframe, $matches);

	$new_src = add_query_arg('autoplay', '1', $matches[1]);

	$iframe = str_replace($matches[1], $new_src, $iframe);

	return $iframe;
}

function lm26_is_external_url( $url ) {
	$wp_url = WP_HOME;
	$wp_domain = parse_url($wp_url, PHP_URL_HOST);

	$url_domain = parse_url($url, PHP_URL_HOST);

	return $wp_domain !== $url_domain;
}

/**
 * Convert a human readble phone number into a URL tel: format.
 * @param  string $phone 			The phone number to convert.
 * @param  string $intl_code	An international dialing code, e.g. +44 for UK.
 * @return string
 */
function lm26_phone_number_2_href($phone, $intl_code = '+44')
{
	// strip all spaces
	$phone = str_replace(' ', '', $phone);

	// strip all non digit characters
	$phone = preg_replace('/[^+0-9]/', '', $phone);

	// add international country code
	if ($intl_code && 0 === strpos($phone, '0')) {
		$phone = $intl_code . substr($phone, 1);
	}

	return $phone;
}


function lm26_get_social_urls()
{
	/**
	 * Associative array of social urls.
	 * 		Social Name => URL
	 * 		Social Name must be capitalised, e.g. LinkedIn, as it will be displayed to the user.
	 * @var array
	 */
	$social_urls = array();

	if ($linkedin = get_field('linkedin_url', 'options')) {
		$social_urls['LinkedIn'] = array(
			'url' => $linkedin,
			'icon' => 'akar-icons:linkedin-fill'
		);
	}

	if ($instagram = get_field('instagram_url', 'options')) {
		$social_urls['Instagram'] = array(
			'url' => $instagram,
			'icon' => 'fa-brands:instagram'
		);
	}


	if ($facebook = get_field('facebook_url', 'options')) {
		$social_urls['Facebook'] = array(
			'url' => $facebook,
			'icon' => 'gg:facebook'
		);
	}

	if ($twitter = get_field('twitter_url', 'options')) {
		$social_urls['Twitter'] = array(
			'url' => $twitter,
			'icon' => 'ri:twitter-x-fill'
		);
	}

	

	if ($youtube = get_field('youtube_url', 'options')) {
		$social_urls['YouTube'] = array(
			'url' => $youtube,
			'icon' => 'fa-brands:youtube'
		);
	}

	return $social_urls;
}


function slv_format_dates($start_date, $end_date, $time = '')
{
	// handle blank/falsey vars
	if (!$start_date && $end_date) {
		$start_date = $end_date;
	}

	if (!$end_date && $start_date) {
		$end_date = $start_date;
	}

	// wrong order, swap vars
	if ($start_date > $end_date) {
		$tmp = $start_date;
		$start_date = $end_date;
		$end_date = $tmp;
	}

	$formatted = '';

	$default_date_format = 'j M Y';
	$tz = new DateTimeZone('Europe/London');

	$start_date_obj = new DateTime($start_date, $tz);
	$end_date_obj = new DateTime($end_date, $tz);

	if ($start_date === $end_date) {
		$formatted = $start_date_obj->format($default_date_format);
	} else if ($start_date_obj->format('Ym') === $end_date_obj->format('Ym')) {
		$formatted = sprintf('%s - %s', $start_date_obj->format('j'), $end_date_obj->format($default_date_format));
	} else if ($start_date_obj->format('Y') === $end_date_obj->format('Y')) {
		$formatted = sprintf('%s - %s', $start_date_obj->format('j M'), $end_date_obj->format($default_date_format));
	} else {
		$formatted = sprintf('%s - %s', $start_date_obj->format($default_date_format), $end_date_obj->format($default_date_format));
	}

	if($time){
		$formatted = $formatted  . ', ' . $time;
	}

	return $formatted;
}


//Exlude Uncategorised category from taxonomy list.
add_filter('acf/fields/taxonomy/wp_list_categories', 'my_taxonomy_args', 10, 2);

function my_taxonomy_args($args, $field)
{
	$args['exclude'] = array(1); //the IDs of the excluded terms
	return $args;
}



// Disable automatic escaping of ACF the_field & the_sub_field
add_filter( 'acf/the_field/allow_unsafe_html', function() { return true; }, 10, 2);