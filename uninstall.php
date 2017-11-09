<?php
/* All uninstall actions of this plugin come here */


if ( ! defined( 'WP_UNINSTALL_PLUGIN' ) ) {
	exit();
}

delete_option('wpdfi-settings');