<?php namespace CAHNRS\Plugin\AccessibilityReport;

  class CAHNRS_Accesssibility_Script {
    public function __construct() {
        add_action('admin_enqueue_scripts', array($this, 'wsuwp_accessibility_report_enqueue_custom_css'));
    }

    public function wsuwp_accessibility_report_enqueue_custom_css() {
      $plugin_url = plugin_dir_url( __DIR__ );
      
      wp_enqueue_style('wsuwp-accessibility-report-dashboard-css', $plugin_url . 'assets/admin/css/admin-styles.min.css');
    }
  }

$CAHNRS_Accesssibility_Script = new CAHNRS_Accesssibility_Script();

?>