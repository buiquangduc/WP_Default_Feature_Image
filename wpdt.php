<?php
/**
 * Plugin Name: WP Default Thumbnail.
 * Description: Help you choose your default thumbnail for post types, categories, tags,...
 * Version: 1.0.0
 * Author: Duc Bui Quang <ducbuiquangxd@gmail.com>
 * Author URI: https://www.ducbuiquang.com
 * License: GNUv3
 * Text Domain: wpdt
 */

define('WPDT_URL_BASE', plugin_dir_url(__FILE__) );
define('WPDT_DIR_BASE', plugin_dir_path( __FILE__ ) );
define('WPDT_ASSETS', WPDT_URL_BASE . '/dist/' );
define('WPDT_TEMPLATES_PATH', WPDT_DIR_BASE.  '/templates/');

require_once WPDT_DIR_BASE . '/vendor/autoload.php';

use WPDT\Traits\HasModule;
use WPDT\Traits\Singleton;
use WPDT\PostType;
use WPDT\Taxonomy;

final class WPDT
{
	use HasModule;
	use Singleton;
	
	/**
	 * @traitDoc
	 */
	public function initializes() 
	{
		$this->loadModules();
	}

	/**
	 * hooks
	 * 
	 * @since 1.0.0
	 * @return void
	 */
	public function hooks() 
	{
		add_action( 'init', [$this, 'init'], 0);

		$this->moduleHooks();

	}
	
	/**
	 * init actions
	 * 
	 * @since 1.0.0
	 * @return void
	 */
	public function init() 
	{
		load_plugin_textdomain('wpdt', false, WPDT_DIR_BASE . '/lang/');
	}
	
	/**
	 * @traitDoc
	 */
	public function loadModules() {
		$modules = [
			'templater'	=> new VA\Templater(WPDT_TEMPLATES_PATH, 'blade'),
			'post_type' => PostType::instance(),
			'taxonomy'	=> Taxonomy::instance(),
		];
			
		foreach($modules as $moduleName => $moduleHandle) {
			$this->module($moduleName, $moduleHandle);
		}
		
		return $this;
	}
	
}
/**
 * Return singleton of WPDT
 *
 * @since  1.0.0
 * @return WPDT  Singleton instance of plugin class.
 */
function wpdt() {
    return WPDT::instance();
}

// Kick it off
add_action('plugins_loaded', [wpdt(), 'hooks']);