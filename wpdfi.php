<?php
/**
 * Plugin Name: WP Default Feature Image.
 * Description: Help you to choose default feature images for any post types, categories, tags,...
 * Version: 1.0.0
 * Author: Duc Bui Quang <ducbuiquangxd@gmail.com>
 * Author URI: https://www.ducbuiquang.com
 * License: GPLv2+
 * Text Domain: wpdfi
 *

WP Default Feature Image is free software: you can redistribute it and/or modify
it under the terms of the GNU General Public License as published by
the Free Software Foundation, either version 2 of the License, or
any later version.
 
WP Default Feature Image is distributed in the hope that it will be useful,
but WITHOUT ANY WARRANTY; without even the implied warranty of
MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE. See the
GNU General Public License for more details.
 
You should have received a copy of the GNU General Public License
along with WP Default Feature Image. If not, see https://www.gnu.org/licenses/gpl-2.0.html.

*/


define('WPDFI_PLUGIN', __FILE__ );
define('WPDFI_PLUGIN_BASENAME', plugin_basename( WPDFI_PLUGIN ) );
define('WPDFI_URL_BASE', plugin_dir_url( WPDFI_PLUGIN) );
define('WPDFI_DIR_BASE', plugin_dir_path( WPDFI_PLUGIN ) );
define('WPDFI_ASSETS', WPDFI_URL_BASE . '/assets/' );
define('WPDFI_TEMPLATES_PATH', WPDFI_DIR_BASE.  '/templates/');

require_once WPDFI_DIR_BASE . '/vendor/autoload.php';

use WPDFI\Traits\HasModule;
use WPDFI\Traits\Singleton;
use WPDFI\PostType;
use WPDFI\Taxonomy;
use WPDFI\Term;
use WPDFI\Ajax;
use WPDFI\Admin;
use WPDFI\Image;
use WPDFI\Layout;
use WPDFI\Admin\Notice;

final class WPDFI
{
	use HasModule;
	use Singleton;
	
	/**
	 * @traitDoc
	 */
	public function initializes() 
	{
		$this->loadModules();

		Ajax::instance();
	}

	/**
	 * All WordPress hooks come here
	 * 
	 * @since 1.0.0
	 * @return void
	 */
	public function hooks() 
	{

		add_action( 'init', [$this, 'init']);

		/* Load all module hooks */
		$this->moduleHooks();

	}

	/**
	 * All Install and default settings stuff for this plugin come here.
	 *
	 * @since 1.0.0
	 * @return void
	 */
	public function install() {

		$options = get_option('wpdfi-settings');
		if(!$options['options']['status_for_update']) {
			$options['options']['status_for_update'] = 'publish';
			update_option('wpdfi-settings', $options);
		}

	}
	
	/**
	 * init actions
	 * 
	 * @since 1.0.0
	 * @return void
	 */
	public function init() 
	{
		load_plugin_textdomain('wpdfi', false, WPDFI_DIR_BASE . '/lang/');
	}
	
	/**
	 * @traitDoc
	 */
	public function loadModules() {
		$modules = [
			'templater'	=> new VA\Templater(WPDFI_TEMPLATES_PATH, 'blade'),
			'post_type' => PostType::instance(),
			'taxonomy'	=> Taxonomy::instance(),
			'term'		=> Term::instance(),
			'image'		=> Image::instance(),
			'layout'	=> Layout::instance(),
			'admin_notice' => Notice::instance(),
			'admin'		=> Admin::instance()
		];
			
		foreach($modules as $moduleName => $moduleHandle) {
			$this->module($moduleName, $moduleHandle);
		}
		
		return $this;
	}
	
}
/**
 * Return singleton of WPDFI
 *
 * @since  1.0.0
 * @return WPDFI  Singleton instance of plugin class.
 */
function wpdfi() {
    return WPDFI::instance();
}

add_action('plugins_loaded', [wpdfi(), 'hooks']);

register_activation_hook(WPDFI_PLUGIN, [wpdfi(), 'install']);