<?php namespace CAHNRS\Plugin\AccessibilityReport;

class CAHNRSEmailCron {

    //Sets how frequent the cron should run. $email_frequency set in settings page.
    public function cahnrs_accessibility_set_frequency(){
        $email_frequency = get_option('email_frequency', 'none');

        if ($email_frequency === 'none') {
            wp_clear_scheduled_hook('cahnrs_accessibility_email_cron');
        } else {
            wp_clear_scheduled_hook('cahnrs_accessibility_email_cron');

            if ($email_frequency === 'weekly') {
                wp_schedule_event(time(), 'weekly', 'cahnrs_accessibility_email_cron');
            } elseif ($email_frequency === 'monthly') {
                wp_schedule_event(time(), 'monthly', 'cahnrs_accessibility_email_cron');
            }
        }
    }

}
