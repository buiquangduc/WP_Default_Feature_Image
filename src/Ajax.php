<?php

namespace WPDT;

/**
 * This class handle all ajax actions of this plugin
 * 
 * @author Duc Bui Quang <ducbuiquangxd@gmail.com>
 * @since 1.0.0
 */

use WPDT\Traits\Singleton;

final class Ajax
{
	use Singleton;

	/**
	 * @traitDoc
	 */
	public function initializes()
	{
		$this->hooks();
	}

	/**
	 * All ajax action of this plugin come here
	 *
	 * @since 1.0.0
	 * @return void
	 */
	public function hooks() {
		$actions = ['get_post_types', 'get_taxonomies', 'get_terms'];

		foreach($actions as $action) {
			\add_action('wp_ajax_wpdt_'. $action, [$this, $action]);
			\add_action('wp_ajax_nopriv_wpdt'. $action, [$this, $action]);
		}
	}

	/**
	 * Ajax action to get all post types which support thumbnail feature
	 *
	 * @since 1.0.0
	 * @return array
	 */
	public function get_post_types() {
		echo json_encode(\wpdt()->post_type->get_name());
		exit;
	}

	/**
	 * Ajax action to get all taxonomies by given post type
	 *
	 * @param string $post_type
	 * @since 1.0.0
	 * @return array
	 */
	public function get_taxonomies() {
		echo json_encode(\wpdt()->taxonomy->get('post'));
		exit;
	}

	/**
	 * Ajax action to get all terms by given taxonomy
	 * 
	 * @since 1.0.0
	 * @return array
	 */
	public function get_terms() {
		echo json_encode(\wpdt()->term->get('category'));
		exit;
	}
}