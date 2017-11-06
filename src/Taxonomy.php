<?php

namespace WPDFI;

/**
 * This class handle all actions related with taxonomy
 *
 * @author Duc Bui Quang <ducbuiquangxd@gmail.com>
 * @since 1.0.0
 */

use WPDFI\Traits\Singleton;

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
				// Only accept taxonomies which visible to admin and reader
				if($taxonomy->show_ui and $taxonomy->show_in_menu) {

					$data[$index]['name'] = $taxonomy->name;
					$data[$index]['label'] = $taxonomy->label;

				}	

			}
		
		}

		return $data;
	}

	/**
	 * Get taxonomy label from taxonomy name
	 *
	 * @param string $taxonomy_name 
	 * @since 1.0.0
	 * @return string
	 */
	public function get_label_from_name($name) {
		return \get_taxonomy_labels(['name' => $name]);
	}
}