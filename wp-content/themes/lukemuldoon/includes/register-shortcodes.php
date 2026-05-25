<?php

/**
 * Register [button] shortcode.
 */
function add_button_shortcode( $atts, $content = null ) {
	// Attributes
	$atts = shortcode_atts(
		array(
			'url' => '',
			'align' => '',
			'open_new_tab' => 'false',
		),
		$atts,
		'button'
	);

	if ( is_admin() ) {
		$atts['url'] = 'https://example.com/';

		if ( !$content ) {
			$content = 'Find out more';
		}
	}

	if ( !$atts['url'] ) {
		return;
	}


	ob_start();

	$template = locate_template('shortcode-button.php');
	if ( $template ) {
		include $template;
	}

	return ob_get_clean();
}

/**
 * Extend Shortcode Ultimates plugin.
 * Remove shortcodes we are not implementing and add our own.
 */

if ( '' !== get_option('su_option_prefix') ) {
	update_option('su_option_prefix', '');
}

add_filter('su/data/shortcodes', 'suext_add_custom_shortcodes');
function suext_add_custom_shortcodes( $shortcodes ) {
	// Clear all existing shortcodes
	$shortcodes = array();

	$shortcodes['button'] = array(
		'name' => __( 'Button', 'lm26' ),
		// Shortcode type. Can be 'wrap' or 'single'
		// Example: [b]this is wrapped[/b], [this_is_single]
		'type' => 'wrap',
		// Shortcode group.
		// Can be 'content', 'box', 'media' or 'other'.
		// Groups can be mixed, for example 'content box'
		'group' => 'content',
		'atts' => array(
			'url' => array(
				// Attribute type.
				// Can be 'select', 'color', 'bool' or 'text'
				'type' => 'text',
				// Available values
				'values' => array(),
				// Default value
				'default' => '',
				// Attribute name
				'name' => __( 'URL', 'lm26' ),
				// Attribute description
				'desc' => __( '<strong>Required.</strong><br>The URL the button will link to. Make sure to include the http/https at the start.', 'lm26' )
			),
			'open_new_tab' => array(
				'type' => 'bool',
				'default' => 'no',
				'name' => __( 'Open New Tab', 'lm26' ),
				'desc' => __( 'Whether to open the URL in a new tab/window.', 'lm26' )
			),
		),
		// Default content for generator (for wrap-type shortcodes)
		'content' => __( 'Find out more', 'lm26' ),
		// Shortcode description for cheatsheet and generator
		'desc' => __( 'A default button', 'lm26' ),
		// Custom icon (font-awesome)
		'icon' => 'mouse-pointer',
		// An image from the plugin assets
		'image' => home_url('/wp-content/plugins/shortcodes-ultimate/admin/images/shortcodes/button.svg'),
		// Name of custom shortcode register function, normally passed to add_shortcode().
		'callback' => 'add_button_shortcode',
	);
	
	return $shortcodes;
}


if ( function_exists('shortcodes_ultimate') ) {
	/**
	 * Add insert shortcode button.
	 */
	add_action('acf/render_field/name=shortcode', 'lm26_add_insert_shortcode_button', 11, 1 );
	function lm26_add_insert_shortcode_button( $field ) {
		?>
		<button type="button" class="su-generator-button button" title="Insert shortcode" onclick="var _target = this.previousElementSibling.children[0]; _target.value = ''; SUG.App.insert( 'classic', { editorID: _target.id, shortcode: '' } );" style="margin-top: 8px;">
			<svg style="vertical-align:middle;position:relative;top:-1px;opacity:.8;width:18px;height:18px" viewBox="0 0 20 20" width="18" height="18" aria-hidden="true"><path fill="currentcolor" d="M8.48 2.75v2.5H5.25v9.5h3.23v2.5H2.75V2.75h5.73zm9.27 14.5h-5.73v-2.5h3.23v-9.5h-3.23v-2.5h5.73v14.5z"></path></svg> Insert shortcode
		</button>
		<?php
	}

	add_action('acf/render_field/type=wysiwyg', 'lm26_add_insert_wysiwyg_shortcode_button', 9, 1 );
	function lm26_add_insert_wysiwyg_shortcode_button( $field ) {
		if ( $field['media_upload'] ) {
			// Will already be handled by Shortcodes Ultimate
			return;
		}

		$exclude_fields = array(
			'other_text',
		);
		if ( in_array($field['_name'], $exclude_fields) ) {
			return;
		}

		?>
		<button type="button" class="su-generator-button button" title="Insert shortcode" onclick="var _target = this.nextElementSibling.querySelector('textarea'); SUG.App.insert( 'classic', { editorID: _target.id, shortcode: '' } );" style="margin-top: 8px;">
			<svg style="vertical-align:middle;position:relative;top:-1px;opacity:.8;width:18px;height:18px" viewBox="0 0 20 20" width="18" height="18" aria-hidden="true"><path fill="currentcolor" d="M8.48 2.75v2.5H5.25v9.5h3.23v2.5H2.75V2.75h5.73zm9.27 14.5h-5.73v-2.5h3.23v-9.5h-3.23v-2.5h5.73v14.5z"></path></svg> Insert shortcode
		</button>
		<?php
	}
}
