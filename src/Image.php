<?php

namespace WPDFI;

/**
 * This class handle all actions related with Image
 *
 * @author Duc Bui Quang <ducbuiquangxd@gmail.com>
 * @since 1.0.0
 */

use WPDFI\Traits\Singleton;

final class Image
{
	use Singleton;

	/**
	 * @traitDoc
	 */
	public function initializes() 
	{
		//
	}

	/**
	 * Get all image size names
	 *
	 * @since 1.0.0
	 * @return array
	 */
	public function get_size_names() {

		return \get_intermediate_image_sizes();
		
	}

	/**
	 * Get image size dimensions
	 *
	 * @param string $size_name
	 * @since 1.0.0
	 * @return array
	 */
	public function get_size_dimensions($size_name) {
		global $_wp_additional_image_sizes;

		$data = [];
		// Default size
		if ( in_array( $size_name, ['thumbnail', 'medium', 'medium_large', 'large'] ) ) {
			$data['width']  = get_option( "{$size_name}_size_w" );
			$data['height'] = get_option( "{$size_name}_size_h" );
		// Additional size
		} elseif ( isset( $_wp_additional_image_sizes[ $size_name ] ) ) {
			$data['width'] = $_wp_additional_image_sizes[ $size_name ]['width'];
			$data['height'] = $_wp_additional_image_sizes[ $size_name ]['height'];
		}

		return $data;
	}

	/**
	 * Get all size names and its dimensions
	 *
	 * @since 1.0.0
	 * @return array
	 */
	public function get_size_names_and_dimensions() {
		$data = [];

		foreach($this->get_size_names() as $size_name) {
			$data[$size_name] = $this->get_size_dimensions($size_name);
		}

		return $data;
	}
}