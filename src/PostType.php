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
		$this->hooks();
	}

	/**
	 * All WordPress hooks for Post Type come here.
	 *
	 * @since 1.0.0
	 * @return void
	 */
	public function hooks() {
		// Hook after a post is updated
		\add_action('post_updated', [$this, 'add_default_feature_image'], 10, 3);

	}

	/**
	 * Add Default Feature Image after a post is updated with specific conditionals.
	 *
	 * @param integer $post_id
	 * @param WP_POST $post_after
	 * @param WP_POST $post_before
	 * @since 1.0.0
	 * @return void
	 */
	public function add_default_feature_image($post_id, $post_after, $post_before) {
		/* Get the Admin Setting for option 'status_for_update', this option will has a value like publish, pending,... */
		$status_for_update = \wpdfi()->admin->get_option('status_for_update');

		/**
		 * Check the post-after-updated (pau).
		 * If the pau does not have a feature image.
		 */
		if(!\has_post_thumbnail($post_after)) {

			/* Check if the pau status match the Admin Setting option 'status_for_update'. */
			if($status_for_update == $post_after->post_status) {

				/* Get pau information about Terms */
				$terms = $this->get_all_terms_post($post_id, $post_after->post_type);

				/* Get main Admin Setting */
				$options = \wpdfi()->admin->get_options();

				/* Loop through main Admin Setting to compare with pau information. */
				foreach($options['sections'] as $option) {

					if($option['post_type'] == $post_after->post_type) {

						/* If two terms array match, set the default feature image for the post. */
						if($terms == $option['taxonomy']) {

							\set_post_thumbnail( $post_id, $option['image_id'] );

						}

					}

				}
			}
		}
	}

	/**
	 * Get all Post Type names which support thumbnail feature.
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
	 * Get post type singular name.
	 *
	 * @param string $post_type
	 * @since 1.0.0
	 * @return string
	 */
	public function get_singular_name($post_type) {
		return \get_post_type_object( $post_type )->labels->singular_name;
	}

	/**
	 * Get ID and text, to match with select2 default value.
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

	/**
	 * Get all terms of a post.
	 * 
	 * @param integer $post_id
	 * @param string $post_type
	 * @since 1.0.0
	 * @return array
	 */
	public function get_all_terms_post($post_id, $post_type) {

		$terms = [];
		/* Get all taxonomies of a post type. */
		$taxonomies = \wpdfi()->taxonomy->get($post_type);
		/* Loop through all taxonomies of a post type. */
		foreach($taxonomies as $taxonomy_id => $taxonomy_value) {
			/* Push all the terms of the post (detected via post_id) to terms variable. */
			foreach(\wp_get_post_terms($post_id, $taxonomy_id) as $term) {

				$terms[] = $term;

			}

		}
		/* Format and return the list of terms. */
		return \wpdfi()->term->format_to_compare($terms);
	}

}