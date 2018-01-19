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
	 * Core action of this plugin.
	 *
	 * @param integer $post_id
	 * @param \WP_POST $post_after
	 * @param \WP_POST $post_before
	 * @since 1.0.0
	 * @return void
	 */
	public function add_default_feature_image($post_id, $post_after, $post_before) {
		/* Get the Admin Setting for option 'status_for_update', this option will has a value like publish, pending,... */
		$status_for_update = \wpdfi()->admin->get_option('options', 'status_for_update');
		if($status_for_update) $status_for_update = 'publish';
		/**
		 * Check the post-after-updated (pau).
		 * If the pau does not have a feature image.
		 */
		if(!\has_post_thumbnail($post_after)) {

			/* Check if the pau status match the Admin Setting option 'status_for_update'. */
			if($status_for_update == $post_after->post_status) {

				$this->update_fimage($post_id);

			}
		}
	}

	/**
	 * Update feature image for a specific post.
	 *
	 * @param integer $post_id
	 * @return boolean
	 */
	public function update_fimage($post_id) {
		if(!$post_id) return false;
		$post_type = \get_post_type($post_id);
		/* Get post's data about Terms */
		$terms = $this->get_all_terms_post($post_id, $post_type);
		
		/* Get main Admin Setting */
		$options = \wpdfi()->admin->get_option('dfis');

		/* Loop through main Admin Setting to compare with post's data. */
		$conditional_status = false; 
		foreach($options as $option) {

			if($option['post_type'] == $post_type) {
				$option_taxonomy_not_exist = (!$option['taxonomy']);
				$term_is_uncategorized = ($terms == ['category' => [1]]);
				$posttype_is_post = ($post_type == 'post');
				/* If two terms array match. */
				if($terms == $option['taxonomy']) {

					$conditional_status = true;

				/**
				 * If post type is 'post', category is 'Uncategorized' and option taxonomy not exist.
				 * We need to check this conditional because default post type category is 'Uncategorized'.
				 */
				} elseif ($option_taxonomy_not_exist and $term_is_uncategorized and $posttype_is_post) {

					$conditional_status = true;

				}

			}
			/* If match conditional, set the default feature image for the post. */
			if($conditional_status == true) {

				\set_post_thumbnail( $post_id, $option['image_id'] );
				return true;
			
			}
		}
		return false;
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

	/**
	 * Get all post statuses.
	 * 
	 * @since 1.0.0
	 * @return array
	 */
	public function get_all_statuses() {
		return \get_post_statuses();
	}

	/**
	 * Get post types follow settings value.
	 *
	 * @since 1.0.0
	 * @return array $pt_fl_dfis
	 */
	protected function _get_pt_fl_settings() {

		$pt_fl_settings = [];
		$dfis_values = \wpdfi()->admin->get_option('dfis');
		if($dfis_values) {
			foreach($dfis_values as $dfi_values) {
				/* Only insert new value if there current value is not exist yet. */
				if(!in_array($dfi_values['post_type'], $pt_fl_settings)) {
					$pt_fl_settings[] = $dfi_values['post_type'];
				} 
			}
		}
		return $pt_fl_settings;
	}

	/**
	 * Get the arguments to get posts which do not have feature image.
	 *
	 * @param mixed (array/string) $post_type
	 * @since 1.0.0
	 * @return array
	 */
	protected function _get_args_posts_no_fimage($post_type) {
		return [
			'post_type' => $post_type,
		    'meta_query' => [
		        [
		            'key' => '_thumbnail_id',
		            'compare' => 'NOT EXISTS'
		        ]
		    ],
		    'post_status' => ['publish', 'pending', 'draft', 'auto-draft', 'future', 'private', 'inherit', 'trash'],
		    'orderby' => 'ID',
		    'order' => 'ASC',
		    'posts_per_page' => -1
	 	];
	}

	/**
	 * Get the post types details, post types are got from settings.
	 * Detail is how many number of posts from this post type which do not have feature image.
	 *
	 * @since 1.0.0
	 * @return array $pt_details_fl_settings.
	 */
	public function get_pt_details_fl_settings() {
		$post_types = $this->_get_pt_fl_settings();
		$pt_details_fl_settings = [];
		foreach($post_types as $pt) {
			/* Only insert new value if there current value is not exist yet. */
			if(!array_key_exists($pt, $pt_details_fl_settings)) {
				$args = $this->_get_args_posts_no_fimage($pt);
				$posts_no_fimage = get_posts($args);
				$pt_details_fl_settings[$pt] = count($posts_no_fimage);
			}
		}
		return $pt_details_fl_settings;
	}

	/**
	 * Return list of post ids without feature image.
	 * 
	 * @since 1.0.0
	 * @return array $ids.
	 */
	public function get_posts_no_fimage_id() {
		$post_types = $this->_get_pt_fl_settings();
		$qr_posts_no_fimage = get_posts( $this->_get_args_posts_no_fimage($post_types) );
		$ids = [];
		foreach($qr_posts_no_fimage as $post) {
			$ids[] = $post->ID;
		}  
		return $ids;
	}
}