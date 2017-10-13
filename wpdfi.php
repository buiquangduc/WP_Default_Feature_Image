<?php
/**
 * Plugin Name: WP Default Feature Image.
 * Description: Help you choose your default thumbnail for post types, categories, tags,...
 * Version: 1.0.0
 * Author: Duc Bui Quang <ducbuiquangxd@gmail.com>
 * Author URI: https://www.ducbuiquang.com
 * License: GNUv3
 * Text Domain: wpdfi
 */

define('WPDFI_URL_BASE', plugin_dir_url(__FILE__) );
define('WPDFI_DIR_BASE', plugin_dir_path( __FILE__ ) );
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
		Admin::instance();
	}

	/**
	 * All WordPress hooks come here
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
			'term'		=> Term::instance()
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

// Kick it off
add_action('plugins_loaded', [wpdfi(), 'hooks']);