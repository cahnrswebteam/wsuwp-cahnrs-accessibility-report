<?php
/**
 *
 * @wordpress-plugin
 * Plugin Name:       WSUWP Accessibilty Report
 * Plugin URI:        https://cahnrs.wsu.edu/
 * Description:       Creates a page in dashboard that shows all accessibility issues on one page.
 * Version:           1.5.3
 * Author:            CAHNRS Communications
 * Author URI:        https://cahnrs.wsu.edu/
 * Text Domain:       cahnrs-accessibility report
 */

// If this file is called directly, abort.
if ( ! defined( 'WPINC' ) ) {
	die;
}

//Checks to see if WSUWP Gutenberg plugin is activated. 
register_activation_hook( __FILE__, 'wsuwp_plugin_accessibility_plugin_check' );

function wsuwp_plugin_accessibility_plugin_check(){
    if ( ! is_plugin_active( 'wsuwp-plugin-gutenberg-accesssibility/wsuwp-plugin-gutenberg-accessibility.php' ) and current_user_can( 'activate_plugins' ) ) {
      wp_die('Sorry, this plugin requires WSUWP Gutenberg Accessibility to be activated. Please activate plugin that plugin first before activating this one. <br><a href="' . admin_url( 'plugins.php' ) . '">&laquo; Return to Plugins</a> <style>div#query-monitor{display:none;}');
    }
}

//Include files from WSUWP Gutenberg Plugin
require_once( WP_PLUGIN_DIR . '/wsuwp-plugin-gutenberg/includes/plugin.php');

//Define the version of this CAHNRS Gutenberg plugin
define( 'CAHNRSACCESSIBILITYREPORTVERSION', '1.5.3' );

// Gets CAHNRS Gutenberg plugin URL.
function _get_cahnrs_accessibility_report_plugin_url() {
  static $cahnrs_accessibility_report_plugin_url;

  if (empty($cahnrs_accessibility_report_url)) {
    $cahnrs_accessibility_report_url = plugins_url(null, __FILE__);
  }

  return $cahnrs_accessibility_report_url;
}

//Load other files of this plugin
function cahnrs_accessibility_report_init(){
	require_once __DIR__ . '/includes/plugin.php';
}

add_action( 'plugins_loaded', 'cahnrs_accessibility_report_init' );

