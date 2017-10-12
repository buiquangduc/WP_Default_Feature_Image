<?php

namespace DTFPT;

/**
 * This class handle all actions related with post type
 *
 * @author Duc Bui Quang <ducbuiquangxd@gmail.com>
 * @since 1.0.0
 */

use DTFPT\Traits\Singleton;

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
	 * @return void
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

}