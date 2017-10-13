<?php

namespace WPDT;

/**
 * This class handle all actions related with taxonomy
 *
 * @author Duc Bui Quang <ducbuiquangxd@gmail.com>
 * @since 1.0.0
 */

use WPDT\Traits\Singleton;

final class Taxonomy
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
	 * Get all taxonomy name and label by given post type name
	 *
	 * @param string $post_type
	 * @since 1.0.0
	 * @return array
	 */
	public function get($post_type) {
		$data = [];

		if($post_type) {
			
			foreach(\get_object_taxonomies($post_type, 'objects') as $index => $taxonomy) {

				$data[$index]['name'] = $taxonomy->name;
				$data[$index]['label'] = $taxonomy->label;

			}
		
		}

		return $data;
	}
}