<?php namespace CAHNRS\Plugin\AccessibilityReport;

class CAHNRSAccessibilityReportPlugin {

	public static function get( $property ) {

		switch ( $property ) {

			case 'version':
				return CAHNRSACCESSIBILITYREPORTVERSION;

			case 'dir':
				return plugin_dir_path( dirname( __FILE__ ) );

			default:
				return '';

		}

	}

	public static function init() {

		// Do plugin stuff here
		//require_once __DIR__ . '/functions.php';
        require_once __DIR__ . '/../classes/class-cahnrs-accessibilty-report.php';
		require_once __DIR__ . '/../classes/class-cahnrs-cron-schedules.php';
	}


}

CAHNRSAccessibilityReportPlugin::init();