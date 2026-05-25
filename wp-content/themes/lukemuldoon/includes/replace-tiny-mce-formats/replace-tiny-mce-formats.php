<?php

/**
 * Main plugin file.
 *
 * @package    Replace TinyMCE Header Formats
 * @license    GPL2
 * @author     Adam Taylor <AdamTaylor@core-marketing.co.uk>
 */

/**
 * Plugin Name: Replace TinyMCE Header Formats
 * Description: Replace the header styles dropdown in TinyMCE Editor with a different set of custom formats.
 * Version: 1.0.0
 * Author: Adam Taylor <AdamTaylor@core-marketing.co.uk>
 * License: GPL2
 */

/*  Copyright 2018  Core Marketing  (email : AdamTaylor@core-marketing.co.uk)

    This program is free software; you can redistribute it and/or modify
    it under the terms of the GNU General Public License, version 2, as 
    published by the Free Software Foundation.

    This program is distributed in the hope that it will be useful,
    but WITHOUT ANY WARRANTY; without even the implied warranty of
    MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
    GNU General Public License for more details.

    You should have received a copy of the GNU General Public License
    along with this program; if not, write to the Free Software
    Foundation, Inc., 51 Franklin St, Fifth Floor, Boston, MA  02110-1301  USA
*/

if ( !class_exists( 'Replace_TinyMCE_Formats' ) ) {
    class Replace_TinyMCE_Formats {
        function __construct() {
            // Set up hooks
            add_action( 'admin_init', array( $this, 'add_editor_styles' ) );

            add_filter( 'mce_buttons', array( $this, 'remove_default_format_select' ) );
            add_filter( 'mce_buttons', array( $this, 'add_new_mce_buttons' ) );
            add_filter( 'tiny_mce_before_init', array( $this, 'mce_before_init_insert_formats' ) );
        }

        public function add_editor_styles() {
            $stylesheet_uri = get_theme_file_uri('includes/replace-tiny-mce-formats/custom-editor-style.css');
            add_editor_style( $stylesheet_uri );
        }

        public function remove_default_format_select( $buttons ) {
            // Remove the format dropdown select
            $remove = array( 'formatselect' );

            return array_diff( $buttons, $remove );
        }

        public function add_new_mce_buttons( $buttons ) {
            array_unshift( $buttons, 'styleselect' );

            return $buttons;
        }

        public function mce_before_init_insert_formats( $init_array ) {
            // Define the style_formats array
            $style_formats = array(
                    array(
                        'title' => 'Medium Text',
                        'classes' => 'fz-medium',
                        'inline' => 'span',
                    ),
                    array(
                        'title' => 'Large Text',
                        'classes' => 'fz-large',
                        'inline' => 'span',
                    ),
                );
            // Insert the array, JSON ENCODED, into 'style_formats'
            $init_array['style_formats'] = json_encode( $style_formats );  

            return $init_array;  
        }
    }
}

$_tinymceFormats = new Replace_TinyMCE_Formats();
