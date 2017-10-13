<?php

namespace WPDFI;

/**
 * This class handle all ajax actions of this plugin
 * 
 * @author Duc Bui Quang <ducbuiquangxd@gmail.com>
 * @since 1.0.0
 */

use WPDFI\Traits\Singleton;

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
		$actions = ['get_post_types', 'get_taxonomies', 'get_terms', 'get_image_size_names_and_dimensions'];

		foreach($actions as $action) {
			\add_action('wp_ajax_wpdfi_'. $action, [$this, $action]);
			\add_action('wp_ajax_nopriv_wpdfi'. $action, [$this, $action]);
		}
	}

	/**
	 * Ajax action to get all post types which support thumbnail feature
	 *
	 * @since 1.0.0
	 * @return array
	 */
	public function get_post_types() {
		echo json_encode(\wpdfi()->post_type->get_name());
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
		echo json_encode(\wpdfi()->taxonomy->get('post'));
		exit;
	}

	/**
	 * Ajax action to get all terms by given taxonomy
	 * 
	 * @since 1.0.0
	 * @return array
	 */
	public function get_terms() {
		echo json_encode(\wpdfi()->term->get('category'));
		exit;
	}

	/**
	 * Ajax action to get all image sizes and its dimension
	 *
	 * @since 1.0.0
	 * @return array
	 */
	public function get_image_size_names_and_dimensions() {
		echo json_encode(\wpdfi()->image->get_size_names_and_dimensions());
		exit;
	}
}