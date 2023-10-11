<?php namespace CAHNRS\Plugin\AccessibilityReport;

class CAHNRSEmailCronSchedule {

    public function __construct() {
		add_filter('cron_schedules', array( $this, 'cahnrs_custom_cron_schedule'));
        add_action('cahnrs_accessibility_email_cron', array($this, 'cahnrs_accessibility_report_email_cron'));
	}    

    //Create new cron schedule of 1 month
    public function cahnrs_custom_cron_schedule($schedules) {
        // Add a new schedule named 'monthly'
        $schedules['monthly'] = array(
            'interval' => 30 * 24 * 60 * 60, 
            'display'  => __('Once Monthly')
        );
    
        return $schedules;
    }

    
    //Emails user a report if there is at least one issue, warning, or alert. 
    public function cahnrs_accessibility_report_email_cron(){
        $cahnrs_accessibility_query = new CAHNRSAccessibilityQuery();
        $cahnrs_accessibility_query = $cahnrs_accessibility_query->cahnrs_report_query();

        $total_errors = $cahnrs_accessibility_query['total_errors'];
        $total_alerts = $cahnrs_accessibility_query['total_alerts'];
        $total_warnings = $cahnrs_accessibility_query['total_warnings'];
            
        $report_content = $this->cahnrs_generate_report_content(); 
        $report_email = get_option('selected_recipients', '');
        $report_email = implode(',', $report_email);
        
        if ($total_errors > 0 || $total_alerts > 0 || $total_warnings > 0) {
            $subject = 'Accessibility Report';
            $message = $report_content;
            $current_date = current_datetime()->format('m-d-Y H:i:s A');
            update_option('last_sent_date', $current_date);
            
            wp_mail($report_email, $subject, $message);
        }
        
    }

    //Generates content for email.
    public static function cahnrs_generate_report_content() {

        $content = '';
        
        if (!empty($custom_email_content)) {
            $content = $custom_email_content; 

            return $content;
        }else {
            $cahnrs_admin_report_page = get_site_url() . '/wp-admin/admin.php?page=cahnrs-accessibility';

            $cahnrs_site_title = get_bloginfo('name');
            $custom_email_content = get_option('custom_email_content', '');

            ob_start();
            include CAHNRSAccessibilityReportPlugin::get('dir') . 'assets/templates/wsuwp-accessibility-default-email-content.php';
            $content = ob_get_clean();

            return $content;
        }

        
    }
}

$CAHNRSEmailCronSchedule = new CAHNRSEmailCronSchedule();