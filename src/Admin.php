<?php
namespace WPDT;
/**
 * This class handle all admin stuffs of this plugin
 *
 * @author Duc Bui Quang <ducbuiquangxd@gmail.com>
 * @since 1.0.0
 */

use WPDT\Traits\Singleton;

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
		\add_action( 'admin_menu', [$this, 'setting_menu'] );
		\add_action( 'init', [$this, 'update_settings']);
	}

    /**
     * Add new setting menu
     *
     * @since 1.0.0
     * @return void
     */
	public function setting_menu() {
		\add_options_page( 'WPDT', 'WPDT', 'manage_options', 'wpdt-settings.php', [$this, 'render_layout']);
	}

	/**
	 * Render layout for wpdt setting page
	 *
	 * @since 1.0.0
	 * @return void
	 */
	public function render_layout() {
		global $pagenow;
		// Exit if this is not options and WP Default Thumbnail settings page
		if($pagenow != 'options-general.php' or $_GET['page'] != 'wpdt-settings.php') return;
		
		$current_tab = (isset($_GET['tab']) and $_GET['tab']) ? $_GET['tab'] : $this->get_default_tab();
		
		echo \wpdt()->templater->render('admin/head', []);

		echo \wpdt()->templater->render('admin/header', [
			'tabs' => $this->get_tabs(), 
			'current' => $current_tab
		]);
			
		echo \wpdt()->templater->render('admin/content', [
			'options'			=> \get_option('wpdt-settings'),
			'current_tab'		=> $current_tab
		]);

		echo \wpdt()->templater->render('admin/footer', []);
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
	 * Update wpdt settings option
	 *
	 * @since 1.0.0
	 * @return void
	 */
	public function update_settings() {
		
		if(isset($_GET['page']) and $_GET['page'] == 'wpdt-settings.php' and isset($_POST['_wpnonce'])) {
            $nonce = $_POST['_wpnonce'];
            if ( ! \wp_verify_nonce( $nonce, 'wpdt-settings-page' ) ) {
                // This nonce is not valid.
                return;
            } else {
            	// // Dont need to store wp_nonce value
             //    unset($_POST['_wpnonce']);
             //    unset($_POST['_wp_http_referer']);
                
             //    $settings = \get_option('wpdt-settings');
             //    foreach($_POST as $key => $value) {
             //    	$settings[$key] = stripslashes($value);
             //    }
             //    \update_option('wpdt-settings', $settings);
             //    \wpdt()->admin_notice->add('Settings Saved.', 'success');
            }   
        }
	}
}