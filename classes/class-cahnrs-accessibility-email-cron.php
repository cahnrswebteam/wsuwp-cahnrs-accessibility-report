<?php namespace CAHNRS\Plugin\AccessibilityReport;

class CAHNRSEmailCron {

    //Sets how frequent the cron should run. $email_frequency set in settings page.
    public function cahnrs_accessibility_set_frequency(){
        $email_frequency = get_option('email_frequency', 'none');

        if ($email_frequency === 'none') {
            wp_clear_scheduled_hook('cahnrs_accessibility_email_cron');
        } else {
            wp_clear_scheduled_hook('cahnrs_accessibility_email_cron');

            $current_time = time();
            $next_run_time = wp_next_scheduled('cahnrs_accessibility_email_cron');            
    
            if ($email_frequency === 'weekly') {
                if ($next_run_time - $current_time < 604800) { 
                    $next_run_time = $current_time + 604800; 
                }

                wp_schedule_event($next_run_time, 'weekly', 'cahnrs_accessibility_email_cron');
            } elseif ($email_frequency === 'monthly') {
                if ($next_run_time - $current_time < 2592000) { 
                    $next_run_time = $current_time + 2592000; 
                }
                wp_schedule_event($next_run_time, 'monthly', 'cahnrs_accessibility_email_cron');
            }
        }
    }

}
