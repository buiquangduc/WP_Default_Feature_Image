<?php
namespace WPDFI;
/**
 * This class handle all admin stuffs of this plugin
 *
 * @author Duc Bui Quang <ducbuiquangxd@gmail.com>
 * @since 1.0.0
 */

use WPDFI\Traits\Singleton;

final class Admin
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
	 * All WordPress hooks come here
	 *
	 * @since 1.0.0
	 * @return void
	 */
	public function hooks() {
		\add_action( 'admin_enqueue_scripts', [$this, 'wpdfi_enqueue_scripts']);
		\add_action( 'admin_menu', [$this, 'setting_menu'] );
		\add_action( 'init', [$this, 'update_settings']);	
	}

	/**
	 * Enqueue styles and scripts
	 *
	 * @since 1.0.0
	 * @return void
	 */
	public function wpdfi_enqueue_scripts() {
		\wp_enqueue_media();
	}

    /**
     * Add new setting menu
     *
     * @since 1.0.0
     * @return void
     */
	public function setting_menu() {
		\add_options_page( 'WPDFI', 'WPDFI', 'manage_options', 'wpdfi-settings.php', [$this, 'render_layout']);
	}

	/**
	 * Render layout for wpdfi setting page
	 *
	 * @since 1.0.0
	 * @return void
	 */
	public function render_layout() {
		global $pagenow;
		// Exit if this is not options and WP Default Thumbnail settings page
		if($pagenow != 'options-general.php' or $_GET['page'] != 'wpdfi-settings.php') return;
		
		$current_tab = (isset($_GET['tab']) and $_GET['tab']) ? $_GET['tab'] : $this->get_default_tab();
		
		echo \wpdfi()->templater->render('admin/head', []);

		echo \wpdfi()->templater->render('admin/header', [
			'tabs' => $this->get_tabs(), 
			'current' => $current_tab
		]);
			
		echo \wpdfi()->templater->render('admin/content', [
			'options'			=> \get_option('wpdfi-settings'),
			'current_tab'		=> $current_tab
		]);

		echo \wpdfi()->templater->render('admin/footer', []);
	}
	
	/**
	 * Retrieve tabs content
	 *
	 * @since 1.0.0
	 * @return array
	 */
	public function get_tabs() {
		return [ 
			'general'		=> 'General',
		];
	}
	
	
	/**
	 * Retrieve default tab
	 *
	 * @since 1.0.0
	 * @return string
	 */
	public function get_default_tab() {
		return 'general';
	}
	
	
	/**
	 * Update wpdfi settings option
	 *
	 * @since 1.0.0
	 * @return void
	 */
	public function update_settings() {
		
		if(isset($_GET['page']) and $_GET['page'] == 'wpdfi-settings.php' and isset($_POST['_wpnonce'])) {
            $nonce = $_POST['_wpnonce'];
            if ( ! \wp_verify_nonce( $nonce, 'wpdfi-settings-page' ) ) {
                // This nonce is not valid.
                return;
            } else {
            	// // Dont need to store wp_nonce value
             //    unset($_POST['_wpnonce']);
             //    unset($_POST['_wp_http_referer']);
                
             //    $settings = \get_option('wpdfi-settings');
             //    foreach($_POST as $key => $value) {
             //    	$settings[$key] = stripslashes($value);
             //    }
             //    \update_option('wpdfi-settings', $settings);
             //    \wpdfi()->admin_notice->add('Settings Saved.', 'success');
            }   
        }
	}
}