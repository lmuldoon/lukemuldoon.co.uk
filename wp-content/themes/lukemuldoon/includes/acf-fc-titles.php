<?php

// ACF Flexible Block option titles

add_filter('acf/fields/flexible_content/layout_title/name=layout', 'lm26_filter_acf_layout_title', 1000, 4);
function lm26_filter_acf_layout_title( $title, $field, $layout, $i ) {
	if ( 'acfcloneindex' === $i ) {
		// skip as not a real field
		// will be used when cloning so prepend NEW
		return 'NEW: ' . $layout['label'];
	}

	$title = '<strong>'.$title.'</strong>';

	$skip_layouts = array(
		'google_map',
	);

	$skip_subfields = array(
		'spacing',
	);

	$subfield = $layout['sub_fields'][0]['name'];

	if ( in_array($subfield, $skip_subfields) || in_array($layout['name'], $skip_layouts) ) {
		return $title;
	}


	$content = get_sub_field($subfield);

	// Check following subfields if first is blank
	$i = 1;
	while ( !$content && isset($layout['sub_fields'][$i]) ) {
		$content = get_sub_field($layout['sub_fields'][$i]['name']);
		$i++;
	}

	// If still no content, return title
	if ( !$content ) {
		return $title;
	}
	

	$content = is_string($content) ? stripslashes($content) : $content;
	$json_content = is_string($content) ? json_decode($content, true) : false;

	$content = str_replace('_', ' ', $content);

	if ( isset($content['address']) || isset($json_content['address']) ) { // Google map
		$content = $content['address'] ?? $json_content['address'];
	} else if ( isset($content['ID']) ) { // assume image ID
		
		$content = wp_get_attachment_image( 
			$content['ID'],
			'soft-thumbnail', 
			false, 
			array(
				'class' => 'acf-fc-layout-preview__image',
				'style' => 'display:block; width:auto; height:150px;',
			) 
		);

	} else if ( is_string($content) ) {

		if ( 0 === strpos($content, '#') ) { // assume colour swatch hex
			$content = '<span class="acf-fc-layout-preview__swatch" style="display:block; width:30px; height:30px; border-radius:3px; background-color:'.$content.'">&nbsp;</span>';	
		} else if ('transparent' === $content) {
			$content = 'End of previous section';
		} else if ( 0 === strpos($content, '<iframe') ) { // parse src attr for iframes
			preg_match( '/(?<=\s)src=[\'"]([^\'"]+)[\'"]/', $content, $matches );
			if ( $matches && isset( $matches[1] ) ) {
				$content = sprintf('<a class="acf-fc-layout-preview__link" href="%1$s" target="_blank" rel="noopener" onclick="event.stopPropagation()">%1$s</a>', $matches[1]);
			}
		} else {
			$content = ucfirst(wp_trim_words(str_replace(array('<br>','<br/>','<br />'), ' ', $content), 40));
		}

	} else if ( is_array($content) ) {
		$count = count($content);
		$content = sprintf( _n( '(%d item)', '(%d items)', $count ), $count );
	}

	$title .= '<span class="acf-fc-layout-preview">'.$content.'</span>';

	return $title;
}
