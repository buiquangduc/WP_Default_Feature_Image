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
		
		echo \wpdfi()->layout->get_admin_layout(
			$this->get_tabs(), $this->get_current_tab(), $this->get_options(), $this->get_layout_name()
		);
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
	 * Get the current Admin tab
	 *
	 * @since 1.0.0
	 * @return string
	 */
	public function get_current_tab() {
		return (isset($_GET['tab']) and $_GET['tab']) ? $_GET['tab'] : $this->get_default_tab();
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
	 * Get settings option
	 *
	 * @since 1.0.0
	 * @return mixed
	 */
	public function get_options() {
		return \get_option('wpdfi-settings');
	}

	/**
	 * Get the name of current layout, it will be default or exist, depending on settings option
	 *
	 * @since 1.0.0
	 * @return string
	 */
	public function get_layout_name() {
		return ($this->get_options()) ? 'exist' : 'default';
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
            	// Dont need to store wp_nonce value
                unset($_POST['_wpnonce']);
                unset($_POST['_wp_http_referer']);
                
                $settings = \get_option('wpdfi-settings');
                foreach($_POST as $key => $value) {
                	$settings[$key] = $value;
                }
                \update_option('wpdfi-settings', $settings);
                \wpdfi()->admin_notice->add('Settings Saved.', 'success');
            }   
        }
	}	

	/**
	 * Get single setting option
	 *
	 * @param string $option
	 * @since 1.0.0
	 * @return string
	 */
	public function get_option($option) {
		return (\get_option('wpdfi-settings')[$option]) ? \get_option('wpdfi-settings')[$option] : 'publish';
	}

}