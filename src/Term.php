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
	public function get_name($taxonomy = 'category') {
		$terms = \get_terms([
			'taxonomy' => $taxonomy,
			'hide_empty' => false
		]);

		return $names;
	}
}