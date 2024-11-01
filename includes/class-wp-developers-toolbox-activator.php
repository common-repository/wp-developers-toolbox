<?php

/**
 * Fired during plugin activation
 *
 * @link       https://readwebtechnology.com/
 * @since      1.0.0
 *
 * @package    Wp_Developers_Toolbox
 * @subpackage Wp_Developers_Toolbox/includes
 */

/**
 * Fired during plugin activation.
 *
 * This class defines all code necessary to run during the plugin's activation.
 *
 * @since      1.0.0
 * @package    Wp_Developers_Toolbox
 * @subpackage Wp_Developers_Toolbox/includes
 * @author     James Read <james@readwebtechnology.com>
 */
class Wp_Developers_Toolbox_Activator {

	/**
	 * Add plugin directory rename script file
	 *
	 * @since    1.0.0
	 */
	public static function add_rename_file() {
    $file = plugin_dir_path( __FILE__ ) . '/rename-plugins-dir.php';
    $newFile = WP_CONTENT_DIR . '/rename-plugins-dir.php';
    copy($file, $newFile);
	}

  /**
	 * Add default options to wp_options table
	 *
	 * @since    1.0.0
	 */
  public static function add_default_options() {
    update_option( 'developer_toolbox_options_debug_mode', 'true' );
    update_option( 'developer_toolbox_options_error_log', 'false' );
    update_option( 'developer_toolbox_options_admin_bar_toggle', 'true' );
    update_option( 'developer_toolbox_options_admin_only', 'true' );
    update_option( 'developer_toolbox_options_ip_only', 'false' );
  }

}