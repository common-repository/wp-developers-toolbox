<?php

/**
 * Provide a admin area view for the plugin
 *
 * This file is used to markup the admin-facing aspects of the plugin.
 *
 * @link       https://readwebtechnology.com/
 * @since      1.0.0
 *
 * @package    Wp_Developers_Toolbox
 * @subpackage Wp_Developers_Toolbox/admin/partials
 */

if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

// Check if the current user is administrator, if not terminate.
if( !current_user_can( 'manage_options' ) ) {
  wp_die( __( 'Hasta la vista, baby. You must be an admin to make changes here.' , 'wp-developers-toolbox' ));
}
?>

<?php
/**
 * Render the "delete error log" button
 *
 * @since  1.0.0
 */
function developer_toolbox_options_delete_log_file() { ?>
  <tr>
    <th scope="row"><label for="delete_log_file_submit"><?php _e( 'Delete Error Log File', 'wp-developers-toolbox' ) ?></label></th>
    <td>
      <fieldset>
        <?php if(file_exists(WP_CONTENT_DIR . '/debug.log')){ ?>
          <form name="delete_log_file" method="post" action="">
            <input type="hidden" name="delete_log_file_submitted" value="delete">
            <p><input class="button" type="submit" name="delete_log_file_submit" value="<?php _e( 'Delete Error Log File', 'wp-developers-toolbox' ) ?>" /></p>
          </form>
        <?php } else {
              echo 'No log file present at ' . content_url() . '/debug.log';
            } ?>
      </fieldset>
    </td>
  </tr>
<?php }

/**
 * Render the "download error log" button
 *
 * @since  1.0.0
 */
function developer_toolbox_options_download_log_file() { ?>
  <tr>
    <th scope="row"><label for="download_link"><?php _e( 'Download Log File', 'wp-developers-toolbox' ) ?></label></th>
    <td>
      <fieldset>
        <?php if(file_exists(WP_CONTENT_DIR . '/debug.log')){ ?>
          <a class="button" href="<?php echo content_url() . '/debug.log'; ?>" download><?php _e( 'Download Error Log File', 'wp-developers-toolbox' ) ?></a>
        <?php } else {
              echo 'No log file present at ' . content_url() . '/debug.log';
            } ?>
      </fieldset>
    </td>
  </tr>
<?php }

/**
 * Render the "rename plugins directory" button
 *
 * @since  1.0.0
 */
function developer_toolbox_options_rename_plugins_dir() { ?>
  <tr>
    <th scope="row"><label for="rename_plugins_dir_submit"><?php _e( 'Rename Plugins Directory', 'wp-developers-toolbox' ) ?></label></th>
    <td>
      <fieldset>
        <form name="rename_plugins_dir" method="post" action="">
          <input type="hidden" name="rename_plugins_dir_submitted" value="rename">
          <p><input class="button" type="submit" name="rename_plugins_dir_submit" value="<?php _e( 'Rename Plugins Directory', 'wp-developers-toolbox' ) ?>" /></p>
        </form>
      </fieldset>
    </td>
  </tr>
<?php } ?>

<div class="wrap">
  <h2><?php echo esc_html( get_admin_page_title() ); ?></h2>
  <form action="options.php" method="post">
    <?php
      settings_fields( $this->plugin_name );
      do_settings_sections( $this->plugin_name );
      submit_button();
    ?>
  </form>
  <table class="form-table">
    <?php
      developer_toolbox_options_download_log_file();
      developer_toolbox_options_delete_log_file();
      developer_toolbox_options_rename_plugins_dir();
    ?>
  </table>
</div>