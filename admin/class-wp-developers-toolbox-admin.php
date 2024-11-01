<?php

/**
 * The admin-specific functionality of the plugin.
 *
 * @link       https://readwebtechnology.com/
 * @since      1.0.0
 *
 * @package    Wp_Developers_Toolbox
 * @subpackage Wp_Developers_Toolbox/admin
 */

/**
 * The admin-specific functionality of the plugin.
 *
 * Defines the plugin name, version, and two examples hooks for how to
 * enqueue the admin-specific stylesheet and JavaScript.
 *
 * @package    Wp_Developers_Toolbox
 * @subpackage Wp_Developers_Toolbox/admin
 * @author     James Read <james@readwebtechnology.com>
 */
class Wp_Developers_Toolbox_Admin {

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
	 * @param      string    $plugin_name       The name of this plugin.
	 * @param      string    $version    The version of this plugin.
	 */
	public function __construct( $plugin_name, $version ) {

    $this->admin_init();
		$this->plugin_name = $plugin_name;
		$this->version = $version;
    $this->delete_error_log();
    $this->rename_plugins_directory();

	}

  /**
   *
   * Load plugin functions
   *
   * @since  1.0.0
   */
  public function admin_init() {
    require_once(ABSPATH . 'wp-includes/pluggable.php');
  }

	/**
	 * Register the stylesheets for the admin area.
	 *
	 * @since    1.0.0
	 */
	public function enqueue_styles() {

		wp_enqueue_style( $this->plugin_name, plugin_dir_url( __FILE__ ) . 'css/wp-developers-toolbox-admin.css', array(), $this->version, 'all' );

	}

	/**
  	 * Register the JavaScript for the admin area.
  	 *
  	 * @since    1.0.0
  	 */
  	public function enqueue_scripts() {

  		wp_enqueue_script( $this->plugin_name, plugin_dir_url( __FILE__ ) . 'js/wp-developers-toolbox-admin.js', array( 'jquery' ), $this->version, false );

	}

  /**
  	 * The options name to be used in this plugin
  	 *
  	 * @since  	1.0.0
  	 * @access 	private
  	 * @var  	string 		$option_name 	Option name of this plugin
  	 */
  	private $option_name = 'developer_toolbox_options';

  /**
     * Register settings
     *
     * @since  1.0.0
     */
    public function register_setting() {
    	add_settings_section(
    		$this->option_name . '_general',
    		__( 'General Settings', 'wp-developers-toolbox' ),
    		array( $this, $this->option_name . '_general_cb' ),
    		$this->plugin_name
    	);
      add_settings_field(
    		$this->option_name . '_debug_mode',
    		__( 'Debug Mode', 'wp-developers-toolbox' ),
    		array( $this, $this->option_name . '_debug_mode_cb' ),
    		$this->plugin_name,
    		$this->option_name . '_general',
    		array( 'label_for' => $this->option_name . '_debug_mode' )
    	);
      add_settings_field(
        $this->option_name . '_admin_only',
        __( 'Display Errors To Admin Only', 'wp-developers-toolbox' ),
        array( $this, $this->option_name . '_admin_only_cb' ),
        $this->plugin_name,
        $this->option_name . '_general',
        array( 'label_for' => $this->option_name . '_admin_only' )
      );
      add_settings_field(
        $this->option_name . '_ip_only',
        __( 'Display Errors To This IP Only', 'wp-developers-toolbox' ),
        array( $this, $this->option_name . '_ip_only_cb' ),
        $this->plugin_name,
        $this->option_name . '_general',
        array( 'label_for' => $this->option_name . '_ip_only' )
      );
      add_settings_field(
        $this->option_name . '_error_log',
        __( 'Error Log', 'wp-developers-toolbox' ),
        array( $this, $this->option_name . '_error_log_cb' ),
        $this->plugin_name,
        $this->option_name . '_general',
        array( 'label_for' => $this->option_name . '_error_log' )
      );
      add_settings_field(
        $this->option_name . '_admin_bar_toggle',
        __( 'Show WP Admin Bar', 'wp-developers-toolbox' ),
        array( $this, $this->option_name . '_admin_bar_toggle_cb' ),
        $this->plugin_name,
        $this->option_name . '_general',
        array( 'label_for' => $this->option_name . '_admin_bar_toggle' )
      );

      register_setting( $this->plugin_name, $this->option_name . '_debug_mode', array( $this, $this->option_name . '_sanitize_radio_input' ) );
      register_setting( $this->plugin_name, $this->option_name . '_error_log', array( $this, $this->option_name . '_sanitize_radio_input' ) );
      register_setting( $this->plugin_name, $this->option_name . '_admin_bar_toggle', array( $this, $this->option_name . '_sanitize_radio_input' ) );
      register_setting( $this->plugin_name, $this->option_name . '_admin_only', array( $this, $this->option_name . '_sanitize_radio_input' ) );
      register_setting( $this->plugin_name, $this->option_name . '_ip_only', array( $this, $this->option_name . '_sanitize_ip_input' ) );
    }

  /**
	 * Render the text for the general section
	 *
	 * @since  1.0.0
	 */
	public function developer_toolbox_options_general_cb() {
		echo '<p>' . __( 'Click here for features usage and more information : ', 'wp-developers-toolbox' ) . '<a target="_BLANK" href="https://readwebtechnology.com/wp-developers-tool-box/">https://readwebtechnology.com/wp-developers-tool-box/</a></p>';
	}

  /**
	 * Render the radio input field for debug mode option
	 *
	 * @since  1.0.0
	 */
	public function developer_toolbox_options_debug_mode_cb() {
    $radio_input_value = get_option( $this->option_name . '_debug_mode' );
		?>
			<fieldset>
				<label>
					<input type="radio" name="<?php echo $this->option_name . '_debug_mode' ?>" id="<?php echo $this->option_name . '_debug_mode' ?>" value="true" <?php checked( $radio_input_value, 'true' ); ?>>
					<?php _e( 'Debug Mode On', 'wp-developers-toolbox' ); ?>
				</label>
				<br>
				<label>
					<input type="radio" name="<?php echo $this->option_name . '_debug_mode' ?>" value="false" <?php checked( $radio_input_value, 'false' ); ?>>
					<?php _e( 'Debug Mode Off', 'wp-developers-toolbox' ); ?>
				</label>
			</fieldset>
		<?php
	}

  /**
   * Render the radio input field for error logging option
   *
   * @since  1.0.0
   */
  public function developer_toolbox_options_error_log_cb() {
    $radio_input_value = get_option( $this->option_name . '_error_log' );
    ?>
      <fieldset>
        <label>
          <input type="radio" name="<?php echo $this->option_name . '_error_log' ?>" id="<?php echo $this->option_name . '_error_log' ?>" value="true" <?php checked( $radio_input_value, 'true' ); ?>>
          <?php _e( 'Error Log On', 'wp-developers-toolbox' ); ?>
        </label>
        <br>
        <label>
          <input type="radio" name="<?php echo $this->option_name . '_error_log' ?>" value="false" <?php checked( $radio_input_value, 'false' ); ?>>
          <?php _e( 'Error Log Off', 'wp-developers-toolbox' ); ?>
        </label>
      </fieldset>
    <?php
  }

  /**
   * Render the radio input field for admin bar display option
   *
   * @since  1.0.0
   */
  public function developer_toolbox_options_admin_bar_toggle_cb() {
    $radio_input_value = get_option( $this->option_name . '_admin_bar_toggle' );
    ?>
      <fieldset>
        <label>
          <input type="radio" name="<?php echo $this->option_name . '_admin_bar_toggle' ?>" id="<?php echo $this->option_name . '_admin_bar_toggle' ?>" value="true" <?php checked( $radio_input_value, 'true' ); ?>>
          <?php _e( 'WP Admin Bar On', 'wp-developers-toolbox' ); ?>
        </label>
        <br>
        <label>
          <input type="radio" name="<?php echo $this->option_name . '_admin_bar_toggle' ?>" value="false" <?php checked( $radio_input_value, 'false' ); ?>>
          <?php _e( 'WP Admin Bar Off', 'wp-developers-toolbox' ); ?>
        </label>
      </fieldset>
    <?php
  }

  /**
   * Render the radio input field for admin only error display option
   *
   * @since  1.0.0
   */
  public function developer_toolbox_options_admin_only_cb() {
    $radio_input_value = get_option( $this->option_name . '_admin_only' );
    ?>
      <fieldset>
        <label>
          <input type="radio" name="<?php echo $this->option_name . '_admin_only' ?>" id="<?php echo $this->option_name . '_admin_only' ?>" value="true" <?php checked( $radio_input_value, 'true' ); ?>>
          <?php _e( 'Display Errors To Logged In Admin Users Only', 'wp-developers-toolbox' ); ?>
        </label>
        <br>
        <label>
          <input type="radio" name="<?php echo $this->option_name . '_admin_only' ?>" value="false" <?php checked( $radio_input_value, 'false' ); ?>>
          <?php _e( 'Display Errors To All Visitors', 'wp-developers-toolbox' ); ?>
        </label>
      </fieldset>
    <?php
  }

  /**
   * Render the text input field for IP address only error display option
   *
   * @since  1.0.0
   */
  public function developer_toolbox_options_ip_only_cb() {
    $ip_input_value = get_option( $this->option_name . '_ip_only' );
    ?>
      <fieldset>
        <label>
          <?php _e( 'Current IP address', 'wp-developers-toolbox' ); ?>
          <?php echo $this->get_user_ip(); ?>
          <br /><br />
          <input type="text" name="<?php echo $this->option_name . '_ip_only' ?>" id="<?php echo $this->option_name . '_ip_only' ?>" value="<?php echo $ip_input_value; ?>">
        </label>
      </fieldset>
    <?php
  }

  /**
	 * Sanitize the $radio_input_value value before being saved to database
	 *
	 * @param  string $radio_input_value $_POST value
	 * @since  1.0.0
	 * @return string           Sanitized value
	 */
	public function developer_toolbox_options_sanitize_radio_input( $radio_input_value ) {
		if ( in_array( $radio_input_value, array( 'true', 'false' ), true ) ) {
      $radio_input_value = sanitize_text_field( $radio_input_value );
      return $radio_input_value;
    }
	}

  /**
	 * Sanitize the $ip_input_value value before being saved to database
	 *
	 * @param  string $ip_input_value $_POST value
	 * @since  1.0.0
	 * @return string           Sanitized value
	 */
	public function developer_toolbox_options_sanitize_ip_input( $ip_input_value ) {
		if ( filter_var($ip_input_value, FILTER_VALIDATE_IP) ) {
      $ip_input_value = sanitize_text_field( $ip_input_value );
      return $ip_input_value;
    } else {
        return 'IP Not Set / Valid!';
      }
  	}

  /**
   * Delete error log file
   *
   * @since  1.0.0
   */
  public function delete_error_log() {
    if ( sanitize_text_field( $_POST['delete_log_file_submitted'] ) == 'delete' ) {
      if (file_exists(WP_CONTENT_DIR . '/debug.log' )) {
        unlink( WP_CONTENT_DIR . '/debug.log' );
      }
    }
  }

  /**
   * Rename plugins directory
   *
   * @since  1.0.0
   */
  public function rename_plugins_directory() {
    if ( sanitize_text_field( $_POST['rename_plugins_dir_submitted'] ) == 'rename' ) {
      $nonce = wp_create_nonce( 'rename-plugin-dir' );
      require(WP_CONTENT_DIR . '/rename-plugins-dir.php');
      exit;
    }
  }

  /**
   * Render the menu for the plugin
   *
   * @since  1.0.0
   */
  public function add_options_page() {
    $developers_toolbox_title = __( 'WP Developer\'s Toolbox', 'wp-developers-toolbox' );
    $system_info_title = __( 'System Info', 'wp-developers-toolbox' );
    $database_export_title = __( 'Database Export', 'wp-developers-toolbox' );
    $user_can = 'manage_options';

    add_menu_page($developers_toolbox_title, $developers_toolbox_title , $user_can, $this->plugin_name, array( $this, 'display_options_page'));
    add_submenu_page( $this->plugin_name, $system_info_title , $system_info_title , $user_can, 'system-info', array( $this, 'rwt_system_info'));
    add_submenu_page( $this->plugin_name, $database_export_title , $database_export_title , $user_can, 'database-export', array( $this, 'database_export'));
  }

  /**
   * Render the options page for plugin
   *
   * @since  1.0.0
   */
  public function display_options_page() {
    include_once 'partials/wp-developers-toolbox-admin-display.php';
  }

  /**
   * Get options
   *
   * @since  1.0.0
   */
  public function get_options() {
    $this->debug_mode = get_option( $this->option_name . '_debug_mode' );
    $this->error_log = get_option( $this->option_name . '_error_log' );
    $this->admin_bar_toggle = get_option( $this->option_name . '_admin_bar_toggle' );
    $this->admin_only = get_option( $this->option_name . '_admin_only' );
    $this->ip_only = get_option( $this->option_name . '_ip_only' );
    return $this;
  }

  /**
   * Render menu on the WP admin bar
   *
   * @since  1.0.0
   */
  public function add_admin_bar_menu() {
    global $wp_admin_bar;

    $on_label = __('ON', 'wp-developers-toolbox');
    $off_label = __('OFF', 'wp-developers-toolbox');
    $show_label = __('SHOW', 'wp-developers-toolbox');
    $hide_label = __('HIDE', 'wp-developers-toolbox');

    if ($this->get_options()->debug_mode == 'false') {
      $debug_toggle = $on_label;
    }
    if ($this->get_options()->debug_mode == 'true') {
      $debug_toggle = $off_label;
    }
    if ($this->get_options()->admin_bar_toggle == 'false') {
      $admin_bar_toggle = $show_label;
    }
    if ($this->get_options()->admin_bar_toggle == 'true') {
      $admin_bar_toggle = $hide_label;
    }

    $menu_id = 'debug_switch';
    $wp_admin_bar->add_menu(array('id' => $menu_id, 'title' => __('Developer\'s Toolbox', 'wp-developers-toolbox'), 'href' => admin_url( 'admin.php?page=wp-developers-toolbox' )));
    $wp_admin_bar->add_menu(array('parent' => $menu_id, 'title' => __('Developer\'s Toolbox : Options', 'wp-developers-toolbox'), 'id' => 'debug_switch_options', 'href' =>  admin_url( 'admin.php?page=wp-developers-toolbox' )));
    $wp_admin_bar->add_menu(array('parent' => $menu_id, 'title' =>  __('Toggle Debug Mode : ', 'wp-developers-toolbox') . $debug_toggle, 'id' => 'debug_switch_toggle', 'href'  => admin_url( 'admin.php?page=wp-developers-toolbox&debug=toggle')));
    $wp_admin_bar->add_menu(array('parent' => $menu_id, 'title' => __('Toggle WP Admin Bar to: ', 'wp-developers-toolbox') . $admin_bar_toggle, 'id' => 'debug_wp_admin_toggle', 'href'  => admin_url( 'admin.php?page=wp-developers-toolbox&wp_admin_bar=toggle')));
  }

  /**
   * Render the system info
   *
   * @since  1.0.0
   */
  public function rwt_system_info() {
    $system_info_title = __( 'System Info', 'wp-developers-toolbox' );
    echo '<div class="wrap"><div id="icon-options-general" class="icon32"><br></div>
    <h2>'.$system_info_title.'</h2>';
    ob_start();
    phpinfo();
    $pinfo = ob_get_contents();
    ob_end_clean();

    $pinfo = preg_replace( '%^.*<body>(.*)</body>.*$%ms','$1',$pinfo);
    echo $pinfo;
    echo '</div>';
  }

  /**
   * Export database
   *
   * @since  1.0.0
   */
  public function database_export() {
    $database_export_title = __( 'Database Export', 'wp-developers-toolbox' );
    echo '<div class="wrap"><div id="icon-options-general" class="icon32"><br></div>
    <h2>'.$database_export_title.'</h2></div>';
    include_once( plugin_dir_path( __FILE__ ) . '../includes/db-backup.php' );
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
}