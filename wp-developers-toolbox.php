<?php

/**
 * The plugin bootstrap file
 *
 * This file is read by WordPress to generate the plugin information in the plugin
 * admin area. This file also includes all of the dependencies used by the plugin,
 * registers the activation and deactivation functions, and defines a function
 * that starts the plugin.
 *
 * @link              https://readwebtechnology.com/
 * @since             1.0.0
 * @package           Wp_Developers_Toolbox
 *
 * @wordpress-plugin
 * Plugin Name:       WP Developer's Toolbox
 * Plugin URI:        https://readwebtechnology.com/wp-developers-tool-box/
 * Description:       A handy collection of functions for use when developing with WordPress.
 * Version:           1.0.1
 * Author:            James Read
 * Author URI:        https://readwebtechnology.com/
 * License:           GPL-2.0+
 * License URI:       http://www.gnu.org/licenses/gpl-2.0.txt
 * Text Domain:       wp-developers-toolbox
 * Domain Path:       /languages
 */

// If this file is called directly, abort.
if ( ! defined( 'WPINC' ) ) {
	die;
}

/**
 * The code that runs during plugin activation.
 * This action is documented in includes/class-wp-developers-toolbox-activator.php
 */
function activate_wp_developers_toolbox() {
	require_once plugin_dir_path( __FILE__ ) . 'includes/class-wp-developers-toolbox-activator.php';
	Wp_Developers_Toolbox_Activator::add_rename_file();
  Wp_Developers_Toolbox_Activator::add_default_options();
}

/**
 * The code that runs during plugin deactivation.
 * This action is documented in includes/class-wp-developers-toolbox-deactivator.php
 */
function deactivate_wp_developers_toolbox() {
	require_once plugin_dir_path( __FILE__ ) . 'includes/class-wp-developers-toolbox-deactivator.php';
	Wp_Developers_Toolbox_Deactivator::remove_rename_file();
  Wp_Developers_Toolbox_Deactivator::remove_options();
}

register_activation_hook( __FILE__, 'activate_wp_developers_toolbox' );
register_deactivation_hook( __FILE__, 'deactivate_wp_developers_toolbox' );

/**
 * The core plugin class that is used to define internationalization,
 * admin-specific hooks, and public-facing site hooks.
 */
require plugin_dir_path( __FILE__ ) . 'includes/class-wp-developers-toolbox.php';

/**
 * Begins execution of the plugin.
 *
 * Since everything within the plugin is registered via hooks,
 * then kicking off the plugin from this point in the file does
 * not affect the page life cycle.
 *
 * @since    1.0.0
 */
function run_wp_developers_toolbox() {

	$plugin = new Wp_Developers_Toolbox();
	$plugin->run();

}
run_wp_developers_toolbox();
