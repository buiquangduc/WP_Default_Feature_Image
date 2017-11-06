<?php

namespace WPDFI;

/**
 * This class handle all actions related with post type
 *
 * @author Duc Bui Quang <ducbuiquangxd@gmail.com>
 * @since 1.0.0
 */

use WPDFI\Traits\Singleton;

final class PostType
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
	 * Get all Post Type names which support thumbnail feature
	 *
	 * @since 1.0.0
	 * @return array $names This $names variable will have format ['post' => 'post',...]
	 */
	public function get_name() {
		$names = \get_post_types();

		if(is_array($names)) {

			foreach($names as $name) {

				if(!\post_type_supports( $name, 'thumbnail' )) {

					unset($names[$name]);

				}

			}

		}

		return $names;
	}

	/**
	 * Get post type singular name
	 *
	 * @param string $post_type
	 * @since 1.0.0
	 * @return string
	 */
	public function get_singular_name($post_type) {
		return \get_post_type_object( $post_type )->labels->singular_name;
	}

	/**
	 * Get ID and text, to match with select2 default value 
	 *
	 * @since 1.0.0 
	 * @return array $data This $data variable will have format [['id' => 'post', 'text' => 'Post'],...]
	 */
	public function get_id_and_text() {
		$data = [];

		$index = 0;
		foreach($this->get_name() as $name => $value) {

			$data[$index]['id'] = $name; 
			$data[$index]['text'] = $this->get_singular_name($name);

			$index++;
		}

		return $data;

	}

}