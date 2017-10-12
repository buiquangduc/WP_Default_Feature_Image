<?php
/**
 * Plugin Name: Default Thumbnail for Post Types.
 * Description: Help you choose your default thumbnail for post types, categories, tags,...
 * Version: 1.0.0
 * Author: Duc Bui Quang <ducbuiquangxd@gmail.com>
 * Author URI: https://www.ducbuiquang.com
 * License: GNUv3
 * Text Domain: dtfpt
 */

define('DTFPT_URL_BASE', plugin_dir_url(__FILE__) );
define('DTFPT_DIR_BASE', plugin_dir_path( __FILE__ ) );
define('DTFPT_ASSETS', DTFPT_URL_BASE . '/dist/' );
define('DTFPT_TEMPLATES_PATH', DTFPT_DIR_BASE.  '/templates/');

require_once DTFPT_DIR_BASE . '/vendor/autoload.php';

use DTFPT\Traits\HasModule;
use DTFPT\Traits\Singleton;
use DTFPT\PostType;

final class DTFPT
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
		add_action('init', [$this, 'init'], 0);

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
		load_plugin_textdomain('dtfpt', false, TWEETS_CAROUSEL_DIR_BASE . '/lang/');
	}
	
	/**
	 * @traitDoc
	 */
	public function loadModules() {
		$modules = [
			'templater'	=> new VA\Templater(DTFPT_TEMPLATES_PATH, 'blade'),
			'post_type' => PostType::instance()
		];
			
		foreach($modules as $moduleName => $moduleHandle) {
			$this->module($moduleName, $moduleHandle);
		}
		
		return $this;
	}
	
}
/**
 * Return singleton of DTFPT
 *
 * @since  1.0.0
 * @return DTFPT  Singleton instance of plugin class.
 */
function dtfpt() {
    return DTFPT::instance();
}

// Kick it off
add_action('plugins_loaded', [dtfpt(), 'hooks']);