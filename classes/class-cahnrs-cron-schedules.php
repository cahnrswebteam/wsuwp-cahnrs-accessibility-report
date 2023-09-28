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
        include 'class-cahnrs-accessibility-query.php';

        $accessibilityQuery = new CAHNRSAccessibilityQuery();
        $errorData = $accessibilityQuery->cahnrs_report_query();
        $totalErrors = $errorData['total_errors'];
        $totalWarnings = $errorData['total_warnings'];
        $totalAlerts = $errorData['total_alerts'];

        $report_content = $this->cahnrs_generate_report_content(); 
        $report_email = get_option('report_email', '');
        
        if ($totalErrors > 0 || $totalWarnings > 0 || $totalAlerts > 0) {
            $subject = 'Accessibility Report';
            $message = $report_content;
            $current_date = date('m-d-Y g:i:s A  ');
            update_option('last_sent_date', $current_date);
            
            wp_mail($report_email, $subject, $message);
        }

        
    }

    //Generates content for email.
    public function cahnrs_generate_report_content() {

        $content = '';
        $cahnrs_admin_report_page = get_site_url() . '/wp-admin/admin.php?page=cahnrs-accessibility';
        $cahnrs_site_title = get_bloginfo('name');

        $content .= "<p>Hello, <br> Your website $cahnrs_site_title has some accessibility issues that need to be looked at. Please visit the link below to make changes to your website.</p>";

        $content .= "<p><a href='$cahnrs_admin_report_page'>View Accessibility Report</a></p> " ;

        $content .= "<p>If you have questions about this report, please contact the CAHNRS Web Team. If you need assistance fixing the accessibility issues on your site, please fill out the web support form or refer to the list of web accessibility resources below:</p> " ;

        $content .= "<ul>
                        <li><a href='https://communications.cahnrs.wsu.edu/web-services/training/'>Virtual Open Lab with the CAHNRS web team</a></li>
                        <li><a href='https://communications.cahnrs.wsu.edu/web-services/training/web-accessibility-resources/'>Web Accessibility Resources</a></li>
                        <li><a href='https://web.wsu.edu/web-accessibility/accessibility-guides/'>Accessibility Guides</a></li>
                        <li><a href='https://wave.webaim.org/'>WAVE Web Accessibility Evaluation Tools</a></li>
                        <li><a href='https://web.wsu.edu/web-accessibility/accessibility-guides/creating-an-accessible-microsoft-word-document/'>Creating an Accessible Word Document</a></li>
                        <li><a href='https://web.wsu.edu/web-accessibility/accessibility-guides/creating-an-accessible-pdf/'>Creating and Accessible PDF</a></li>
                        <li><a href='https://web.wsu.edu/web-accessibility/web-accessibility-training/'>Required WSU Web Accessibility Training</a></li>
                    </ul>";

        $content .= "<p>Thank you, <br> CAHNRS Web Team</p>";
    

        return $content;
    }
}

$CAHNRSEmailCronSchedule = new CAHNRSEmailCronSchedule();