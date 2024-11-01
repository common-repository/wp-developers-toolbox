<?php

/**
 * The public-facing functionality of the plugin.
 *
 * @link       https://readwebtechnology.com/
 * @since      1.0.0
 *
 * @package    Wp_Developers_Toolbox
 * @subpackage Wp_Developers_Toolbox/public
 */

/**
 * The public-facing functionality of the plugin.
 *
 * Defines the plugin name, version, and two examples hooks for how to
 * enqueue the admin-specific stylesheet and JavaScript.
 *
 * @package    Wp_Developers_Toolbox
 * @subpackage Wp_Developers_Toolbox/public
 * @author     James Read <james@readwebtechnology.com>
 */
class Wp_Developers_Toolbox_Public {

	/**
	 * The ID of this plugin.
	 *
	 * @since    1.0.0
	 * @access   private
	 * @var      string    $plugin_name    The ID of this plugin.
	 */
	private $plugin_name;

	/**
	 * The version of this plugin.
	 *
	 * @since    1.0.0
	 * @access   private
	 * @var      string    $version    The current version of this plugin.
	 */
	private $version;

	/**
	 * Initialize the class and set its properties.
	 *
	 * @since    1.0.0
	 * @param      string    $plugin_name       The name of the plugin.
	 * @param      string    $version    The version of this plugin.
	 */
	public function __construct( $plugin_name, $version ) {

		$this->plugin_name = $plugin_name;
		$this->version = $version;
    $this->get_options();
    $this->wp_admin_bar_debug_toggle();
    $this->wp_admin_bar_toggle();
    $this->get_user_ip();
	}

  /**
   * Public init run at after_setup_theme - Evaluate optoins to call debug mode method or silence error reporting.
   *
   * @since  1.0.0
   */
  public function public_init() {
    // Check admin only setting
    if( $this->admin_only == 'false' || current_user_can( 'manage_options' )) {
      $admin_only_ok = 'true';
    }
    // Check IP setting, if set check if set IP matches user IP
    if( $this->ip_only == 'IP Not Set / Valid!' ){
      $ip_only_ok = 'true';
    } else if ( $this->ip_only == $this->user_ip ) {
      $ip_only_ok = 'true';
    }
    // Check if debug mode is on and if admin only settings and IP settings pass: then turn error reporting on or off
    if( $this->debug_mode == 'true' && $admin_only_ok == 'true' || $this->debug_mode == 'true' && $ip_only_ok == 'true') {
      $this->wp_debug_mode();
    } else {
      error_reporting(0);
    }
    // Evaluate error loggin option and call method if true
    if ( $this->error_log == 'true' ) {
      $this->error_log();
    }
  }

  /**
   * Log errors to a file
   *
   * @since  1.0.0
   */
  public function error_log() {
    ini_set( 'log_errors', 1 );
    ini_set( 'error_log', WP_CONTENT_DIR . '/debug.log' );
  }

  /**
   * Get options
   *
   * @since  1.0.0
   */
  public function get_options() {
    $this->debug_mode = get_option( 'developer_toolbox_options_debug_mode' );
    $this->error_log = get_option( 'developer_toolbox_options_error_log' );
    $this->admin_bar_toggle = get_option( 'developer_toolbox_options_admin_bar_toggle' );
    $this->admin_only = get_option( 'developer_toolbox_options_admin_only' );
    $this->ip_only = get_option( 'developer_toolbox_options_ip_only' );
    return $this;
  }

  /**
   * Set options
   *
   * @since  1.0.0
   */
  public function set_options($option, $value){
    update_option( $option , $value);
    return $this;
  }

  /**
   * Get user's IP address
   *
   * @since  1.0.0
   */
  public function get_user_ip() {
    $this->user_ip = 'Not found';
    if ( ! empty( $_SERVER['HTTP_CLIENT_IP'] ) ) {
      //shared connection
      $this->user_ip = $_SERVER['HTTP_CLIENT_IP'];
    } elseif ( ! empty( $_SERVER['HTTP_X_FORWARDED_FOR'] ) ) {
      //from proxy
      $this->user_ip = $_SERVER['HTTP_X_FORWARDED_FOR'];
    } else {
      $this->user_ip = $_SERVER['REMOTE_ADDR'];
    }
    return $this->user_ip;
  }

	/**
	 * Register the stylesheets for the public-facing side of the site.
	 *
	 * @since    1.0.0
	 */
	public function enqueue_styles() {

		wp_enqueue_style( $this->plugin_name, plugin_dir_url( __FILE__ ) . 'css/wp-developers-toolbox-public.css', array(), $this->version, 'all' );

	}

	/**
	 * Register the JavaScript for the public-facing side of the site.
	 *
	 * @since    1.0.0
	 */
	public function enqueue_scripts() {

		wp_enqueue_script( $this->plugin_name, plugin_dir_url( __FILE__ ) . 'js/wp-developers-toolbox-public.js', array( 'jquery' ), $this->version, false );

	}

  /**
   * Display / Log errors : Debug Mode
   * Modified from: https://developer.wordpress.org/reference/functions/wp_debug_mode/
   *
   * @since  1.0.0
   */
  public function wp_debug_mode() {

    error_reporting( E_ALL );

    ini_set( 'display_errors', 1 );

    error_reporting( E_CORE_ERROR | E_CORE_WARNING | E_COMPILE_ERROR | E_ERROR | E_WARNING | E_PARSE | E_USER_ERROR | E_USER_WARNING | E_RECOVERABLE_ERROR );

    if ( defined( 'XMLRPC_REQUEST' ) ) {
      ini_set( 'display_errors', 0 );
    }
  }

  /**
   * Toggle Debug Mode settings from WP admin bar
   *
   * @since  1.0.0
   */
  public function wp_admin_bar_debug_toggle() {

    if ( sanitize_text_field( isset($_GET["debug"]) ) && sanitize_text_field( $_GET["debug"] == 'toggle') ) {
      if ($this->debug_mode == 'true') {
        $this->set_options('developer_toolbox_options_debug_mode' , 'false');
      }
      if ($this->debug_mode == 'false') {
        $this->set_options('developer_toolbox_options_debug_mode' , 'true');
      }
      if ( wp_get_referer() ) {
          wp_safe_redirect( wp_get_referer() );
      }
    }
  }

  /**
   *  Toggle the WP admin bar
   *
   * @since  1.0.0
   */
  public function wp_admin_bar_toggle() {
    if ( sanitize_text_field( isset($_GET["wp_admin_bar"]) ) && sanitize_text_field( $_GET["wp_admin_bar"] == 'toggle') ) {
      if ($this->admin_bar_toggle == 'true') {
        $this->set_options('developer_toolbox_options_admin_bar_toggle' , 'false');
      }
      if ($this->admin_bar_toggle == 'false') {
        $this->set_options('developer_toolbox_options_admin_bar_toggle' , 'true');
      }
      if ( wp_get_referer() ) {
        wp_safe_redirect( wp_get_referer() );
      }
    }
    if ( $this->admin_bar_toggle == 'false' ) {
      show_admin_bar( false );
    }
  }
}
