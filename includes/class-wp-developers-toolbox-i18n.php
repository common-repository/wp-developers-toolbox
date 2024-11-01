<?php

/**
 * Define the internationalization functionality
 *
 * Loads and defines the internationalization files for this plugin
 * so that it is ready for translation.
 *
 * @link       https://readwebtechnology.com/
 * @since      1.0.0
 *
 * @package    Wp_Developers_Toolbox
 * @subpackage Wp_Developers_Toolbox/includes
 */

/**
 * Define the internationalization functionality.
 *
 * Loads and defines the internationalization files for this plugin
 * so that it is ready for translation.
 *
 * @since      1.0.0
 * @package    Wp_Developers_Toolbox
 * @subpackage Wp_Developers_Toolbox/includes
 * @author     James Read <james@readwebtechnology.com>
 */
class Wp_Developers_Toolbox_i18n {


	/**
	 * Load the plugin text domain for translation.
	 *
	 * @since    1.0.0
	 */
	public function load_plugin_textdomain() {

		load_plugin_textdomain(
			'wp-developers-toolbox',
			false,
			dirname( dirname( plugin_basename( __FILE__ ) ) ) . '/languages/'
		);

	}



}
