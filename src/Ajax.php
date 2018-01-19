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
					'get_default_layout', 'get_related_layout', 'generate_feature_image'];

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
		echo json_encode(\wpdfi()->layout->get_related_layout($_POST['dfi_index'], $_POST['post_type']));
		exit;
	}

	/**
	 * Get default layout of single dfi in admin
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


	public function generate_feature_image() {
		/* security check. */
		\check_ajax_referer( 'wpdfi-ajax-nonce', 'security' );
		$update_fimage = \wpdfi()->post_type->update_fimage($_POST['post_id']);
		$post_type = \get_post_type($_POST['post_id']);
		$post_type_name = \wpdfi()->post_type->get_singular_name($post_type);
		echo json_encode(['status' => $update_fimage, 'namePT' => $post_type_name, 'postId' => $_POST['post_id']]);
		exit;
	}
}