<?php

namespace WPDFI;

/**
 * This class handle all actions related with layout for this plugin
 *
 * @author Duc Bui Quang <ducbuiquangxd@gmail.com>
 * @since 1.0.0
 */

use WPDFI\Traits\Singleton;

final class Layout {

	use Singleton;

	/**
	 * @traitDoc
	 */
	public function initializes() 
	{
		//
	}

	/**
	 * Get admin layout
	 *
	 * @param array $tabs
	 * @param string $current_tab
	 * @param mixed $options
	 * @param string $layout_name
	 * @since 1.0.0
	 * @return \VA\Templater
	 */
	public function get_admin_layout($tabs, $current_tab, $options, $layout_name) {
		return \wpdfi()->templater->render('admin.layout',[
			'tabs' => $tabs, 'current_tab' => $current_tab,
			'options' => $options, 'layout_name' => $layout_name,
		]); 
	}

	/**
	 * Get taxonomies layout
	 *
	 * @param array $taxonomies
	 * @since 1.0.0
	 * @return \VA\Templater
	 */
	public function get_taxonomies_layout($taxonomies) {
		return \wpdfi()->templater->render('admin.blocks.taxonomy.default', ['taxonomies' => $taxonomies]);
	}

	/**
	 * Get default layout of single dfi in admin
	 *
	 * @param integer $dfi_index
	 * @param boolean $include_delete
	 * @since 1.0.0
	 * @return VA\Templater
	 */
	public function get_default_layout($dfi_index, $include_delete) {
		return \wpdfi()->templater->render('admin.blocks.tabs.dfis.default', [
			'dfi_index' => $dfi_index, 'include_delete' => $include_delete
		]);
	}

	/**
	 * Get related layout with post type value, related layout include taxonomies, image upload and image size
	 *
	 * @param string $post_type
	 * @param integer $dfi_index
	 * @since 1.0.0
	 * @return string $layout
	 */
	public function get_related_layout($dfi_index, $post_type) {
		$layout = '';
		/* Get taxonomy layout */
		$layout.= \wpdfi()->templater->render('admin.blocks.taxonomy.default', [
			'taxonomies' => \wpdfi()->taxonomy->get($post_type),
			'dfi_index' => $dfi_index
		]);
		$layout.= \wpdfi()->templater->render('admin.blocks.imageupload.default', [
			'dfi_index' => $dfi_index
		]);
		// $layout.= \wpdfi()->templater->render('admin.blocks.imagesize.default', [
		// 	'sizes' => \wpdfi()->image->get_size_names_and_dimensions()
		// ]);
		return $layout;
	}
}