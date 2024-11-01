<?php

/**
 * Fired during plugin deactivation
 *
 * @link       https://readwebtechnology.com/
 * @since      1.0.0
 *
 * @package    Wp_Developers_Toolbox
 * @subpackage Wp_Developers_Toolbox/includes
 */

/**
 * Fired during plugin deactivation.
 *
 * This class defines all code necessary to run during the plugin's deactivation.
 *
 * @since      1.0.0
 * @package    Wp_Developers_Toolbox
 * @subpackage Wp_Developers_Toolbox/includes
 * @author     James Read <james@readwebtechnology.com>
 */
class Wp_Developers_Toolbox_Deactivator {

	/**
   * Remove plugin directory rename script file
	 * @since    1.0.0
	 */
	public static function remove_rename_file() {
    $newFile = WP_CONTENT_DIR . '/rename-plugins-dir.php';
    unlink($newFile);
	}

  /**
   * Remove options
	 * @since    1.0.0
	 */
	public static function remove_options() {
    delete_option( 'developer_toolbox_options_debug_mode' );
    delete_option( 'developer_toolbox_options_error_log' );
    delete_option( 'developer_toolbox_options_admin_bar_toggle' );
    delete_option( 'developer_toolbox_options_admin_only' );
    delete_option( 'developer_toolbox_options_ip_only' );
  }

}