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
			$this->_get_tabs(), $this->_get_current_tab(), $this->_get_options(), $this->_get_layout_name()
		);
	}
	
	/**
	 * Retrieve tabs content
	 *
	 * @since 1.0.0
	 * @return array
	 */
	private function _get_tabs() {
		return [ 
			'dfis'		=> 'DFIs',
			'options'		=> 'Options'
		];
	}
	
	/**
	 * Get the current Admin tab
	 *
	 * @since 1.0.0
	 * @return string
	 */
	private function _get_current_tab() {
		return (isset($_GET['tab']) and $_GET['tab']) ? $_GET['tab'] : $this->_get_default_tab();
	}
	
	/**
	 * Retrieve default tab
	 *
	 * @since 1.0.0
	 * @return string
	 */
	private function _get_default_tab() {
		return 'dfis';
	}

	/**
	 * Get main setting options
	 *
	 * @since 1.0.0
	 * @return mixed
	 */
	private function _get_options() {
		return \get_option('wpdfi-settings');
	}

	/**
	 * Get the name of current layout, it will be default or exist, depending on settings option
	 *
	 * @since 1.0.0
	 * @return string
	 */
	private function _get_layout_name() {
		return ($this->get_option($this->_get_current_tab())) ? 'exist' : 'default';
	}
	
	/**
	 * Update main setting options
	 *
	 * @param array $options
	 * @since 1.0.0
	 * @return boolean
	 */
	private function _update_options($options) {
		return \update_option('wpdfi-settings', $options);
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
                
                $options = $this->_get_options();
                foreach($_POST as $key => $value) {
                	$options[$key] = $value;
                }

                if($this->_update_options($options)) {
                	\wpdfi()->admin_notice->add('Settings Saved.', 'success');
                } else {
                	\wpdfi()->admin_notice->add('Your options are still the same.', 'warning');
                }
                
            }   
        }
	}	

	/**
	 * Get single setting option.
	 *
	 * @param string $option_key
	 * @param string $option_detail
	 * @since 1.0.0
	 * @return mixed(string/array)
	 */
	public function get_option($option_key, $option_detail = null) {
		if($option_detail) return $this->_get_options()[$option_key][$option_detail];
		return $this->_get_options()[$option_key];
	}

}