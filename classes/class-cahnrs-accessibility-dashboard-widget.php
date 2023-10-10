<?php namespace CAHNRS\Plugin\AccessibilityReport;

class CAHNRSAccessibilityDashboardWidget {

	public function __construct() {
        add_action('wp_dashboard_setup', array($this, 'add_dashboard_widget'));
	}

    public function add_dashboard_widget() {
        wp_add_dashboard_widget(
            'wsu_accessibility_report_widget',
            'WSU Accessibility Report',
            array( $this , 'wsu_accessibility_report_widget_content'),
            'dashboard',
            'high'
        );
    }

    public function wsu_accessibility_report_widget_content() {
        include CAHNRSAccessibilityReportPlugin::get('dir') . '/assets/templates/wsuwp-accessibility-dashboard-widget.php';
    }

}

$CAHNRSAccessibilityDashboardWidget = new CAHNRSAccessibilityDashboardWidget();