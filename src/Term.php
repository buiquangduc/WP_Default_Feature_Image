<?php

namespace WPDT;

/**
 * This class handle all actions related with term
 *
 * @author Duc Bui Quang <ducbuiquangxd@gmail.com>
 * @since 1.0.0
 */

use WPDT\Traits\Singleton;

final class Term
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
	 * Get all terms by given taxonomy
	 *
	 * @param string $taxonomy
	 * @since 1.0.0
	 * @return array
	 */
	public function get($taxonomy = 'category') {
		$names = [];

		$terms = \get_terms([
			'taxonomy' => $taxonomy,
			'hide_empty' => false
		]);

		foreach($terms as $index => $term) {

			$names[$index]['id'] = $term->term_id;
			$names[$index]['name'] = $term->name;

		}

		return $names;
	}
}