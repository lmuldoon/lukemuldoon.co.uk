<?php

/**
 * Define the custom image sizes used by this theme.
 * @return array The image sizes required.
 */
function lm26_get_theme_image_sizes() {
  $image_sizes = array();

  /** Example
  $image_sizes[ 'banner' ] = array(
    'width' => 1600,
    'height' => 500,
    /**
     * Whether to crop the image to the exact dimensions specified.
     * true = hard crop, will lose some of the image.
     * false = soft crop, the whole image will be visible but may not 
     *         be the exact width and height specified above.
     *         The image will be scaled to the largest of the 2 dimensions.
    * /
    'hard_crop' => true,
  );
  //*/

  /**
   * Filter the image sizes.
   * @param array $image_sizes The custom sizes currently defined.
   * @return array
   */
  return apply_filters( 'lm26_custom_image_sizes', $image_sizes );
}

/**
 * Create a set of image sizes which scale linearly from a minimum width and height. 
 * E.g. calling this function with ...('card', 320, 180, true) will produce
 * 'card-thumbnail' 320px by 180px
 * 'card-medium' 640px by 360px
 * 'card-large' 1280px by 720px
 * 'card-full' 2048px by 1152px
 * 
 * @param  string  $group      A name for the set of images.
 * @param  integer $min_width  The starting width, thumbnail size, for this set of images.
 * @param  integer $min_height The starting height, thumbnail size, for this set of images. 
 *                             Set to -1 to indicate a soft crop where the 
 *                             height is auto defined based on the image width.
 * @param  boolean $include_full_crop True to include a full viewport crop as well.
 * @return array               A 3 element array containing 'thumbnail',
 *                             'medium' & 'large' image sizes.
 */
function lm26_generate_crop_set( $group, $min_width, $min_height = -1, $include_full_crop = false ) {
  $sizes = array();
  $size_names = array('thumbnail', 'medium', 'large');

  foreach ($size_names as $key => $size_name) {
    $width = $min_width * pow(2, $key);
    $height = $min_height * pow(2, $key);

    // Constrain to max
    $width = min($width, 9999);
    $height = min($height, 9999);

    $sizes[$group . '-' . $size_name] = array(
      'width' => $width,
      'height' => -1 === $min_height ? 9999 : $height,
      'hard_crop' => -1 === $min_height ? false : true,
    );
  }

  if ( $include_full_crop ) {
    $height = floor($min_height / $min_width * 2048);
    $height = min($height, 9999);

    $sizes[$group . '-full'] = array(
      'width' => 2048,
      'height' => -1 === $min_height ? 9999 : $height,
      'hard_crop' => -1 === $min_height ? false : true,
    );
  }

  return $sizes;
}
