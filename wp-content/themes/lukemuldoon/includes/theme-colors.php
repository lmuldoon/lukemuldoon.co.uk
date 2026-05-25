<?php

// Theme color functions

/**
 * The threshold at which a color is considered light.
 * I.e. A background with lightness above this should use black text.
 */
define('LIGHTNESS_THRESHOLD', 0.76);

/**
 * Get the theme colors and their associated names.
 * @param  string $prop Optional, a property from the color object.
 *                      If passed the return array will only contain 
 *                      the values for that property. 
 * @return array
 */
function lm26_get_colors( $prop = null ) {
	$colors = array(
		'Black' => '#000000',
		'White' => '#FFFFFF',
		// TODO: Add theme colours here. Also should be added to Section:Background Colour field in ACF
	);

	// Add dynamic/calculated props
	foreach ($colors as $name => &$color) {
		// Split hex into parts
		$hex = substr( $color, 1 );
		$rgb_props = lm26_get_hex_props( $hex );

		$color_obj = array_merge( array(
			'key' => lm26_name2key( $name ),
			'name' => $name,
			'hex' => strtolower( $color ),
		), $rgb_props );
		
		if ( $prop && isset( $color_obj[ $prop ] ) ) {
			$color_obj = $color_obj[ $prop ];
		}

		$color = $color_obj;
		unset( $color );
	}

	return $colors;
}

/**
 * Get the RGB & lightness properties for a single color.
 * @param  string $hex  A color hex code.
 * @return array  An associative array with 'rgb', 'lightness' and 'raw_lightness' keys.
 *                'rgb' array A 3 element array [0] = red, [1] = green, [2] = blue.
 */
function lm26_get_hex_props( $hex ) {
	$rgb = hex2rgb($hex);

	return array(
		'rgb' => $rgb,
		'lightness' => call_user_func_array('perceived_lightness', $rgb), // true visual lightness
		'raw_lightness' => call_user_func_array('lightness', $rgb), // raw lightness from the color
	);
}

/**
 * Convert a hex code color into it's RGB parts.
 * @param  string $hex A hex color code. Leading hash is optional.
 * @return array       An array with 3 elements, representing the R, G, and B values of the color.
 */
function hex2rgb( $hex ) {
	if ( 0 === strpos($hex, '#') ) {
		$hex = substr( $hex, 1 );
	}

	return array(
		hexdec($hex[0].$hex[1]), // r
		hexdec($hex[2].$hex[3]), // g
		hexdec($hex[4].$hex[5]), // b
	);
}

/**
 * Convert a name (title cased) into a code usable key.
 * Spaces are converted to underscores and the string is lowercased.
 * @uses WordPress function sanitize_title_with_dashes()
 * 
 * @param  string $name The name to convert.
 * @return string
 */
function lm26_name2key( $name ) {
	$name = sanitize_title_with_dashes( $name );
	$name = str_replace( '-', '_', $name );

	return $name;
}

/**
 * Calculate lightness of an RGB color.
 * @return float A number between 0 and 1.
 */
function lightness($R = 255, $G = 255, $B = 255) {
  return (max($R, $G, $B) + min($R, $G, $B)) / 510.0; // HSL algorithm
}

/**
 * Calculate perceived lightness of an RGB color.
 * @return float A number between 0 and 1.
 */
function perceived_lightness($R = 255, $G = 255, $B = 255) {
	// sRGB Luma method
	// Luma = (red * 0.2126 + green * 0.7152 + blue * 0.0722) / 255
	$R *= 0.2126;
	$G *= 0.7152;
	$B *= 0.0722;

	return ( $R + $G + $B ) / 255;
}

/**
 * Get the theme colors as a TinyMCE textcolor_map option compatible value.
 * Format is a flat 1d array, with hex code followed by color name.
 * The hex code should have the leading hash stripped.
 * @return array
 */
function lm26_get_tinymce_textcolor_map() {
	$colors = lm26_get_colors('hex');
	$textcolor_map = array();

	foreach ($colors as $name => $hex) {
		$textcolor_map[] = substr( $hex, 1 );
		$textcolor_map[] = $name;
	}

	return json_encode( $textcolor_map );	
}

/**
 * Add the theme colors to WordPress's default wysiwyg color map.
 * @param  array $init The TinyMCE init array.
 * @return array
 */
add_filter( 'tiny_mce_before_init', 'lm26_set_tinymce_wysiwyg_colors' );
function lm26_set_tinymce_wysiwyg_colors( $init ) {
	$init['textcolor_cols'] = 3;
	$init['textcolor_map'] = lm26_get_tinymce_textcolor_map();

	return $init;
}


if ( class_exists('ACF') ) {

	/**
	 * Define theme colors as available for ACF wysiwyg use.
	 */
	add_action( 'admin_head', 'lm26_set_acf_tinymce_wysiwyg_colors', 999 );
	function lm26_set_acf_tinymce_wysiwyg_colors() {
		$textcolor_map = lm26_get_tinymce_textcolor_map();

		?>
		<script>
			if ( acf ) {
				acf.add_filter( 'wysiwyg_tinymce_settings', function(init, id, $field) {
					
					// Add theme specific colours
					init.textcolor_cols = 3;
					init.textcolor_map = <?php echo $textcolor_map; ?>;

			    return init;
				} );
			}
		</script>
		<?php
	}

}
