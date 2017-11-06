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
		$actions = ['get_post_types', 'get_terms', 'get_image_size_names_and_dimensions', 
					'get_default_layout', 'get_related_layout'];

		foreach($actions as $action) {
			\add_action('wp_ajax_wpdfi_'. $action, [$this, $action]);
			\add_action('wp_ajax_nopriv_wpdfi'. $action, [$this, $action]);
		}
	}

	/**
	 * Get related layout with post type value, related layout include taxonomies, image upload and image size
	 *
	 * @since 1.0.0
	 * @return void
	 */
	public function get_related_layout() {
		echo json_encode(\wpdfi()->layout->get_related_layout($_POST['section_index'], $_POST['post_type']));
		exit;
	}

	/**
	 * Get default layout of single section in admin
	 *
	 * @since 1.0.0
	 * @return void
	 */
	public function get_default_layout() {
		echo json_encode(\wpdfi()->layout->get_default_layout($_POST['index'], $_POST['include_delete']));
		exit;
	}

	/**
	 * Ajax action to get all post types which support thumbnail feature
	 *
	 * @since 1.0.0
	 * @return array
	 */
	public function get_post_types() {
		echo json_encode(\wpdfi()->post_type->get_id_and_text());
		exit;
	}

	/**
	 * Ajax action to get all terms by given taxonomy
	 * 
	 * @since 1.0.0
	 * @return array
	 */
	public function get_terms() {
		echo json_encode(\wpdfi()->term->get($_POST['taxonomy']));
		exit;
	}
}