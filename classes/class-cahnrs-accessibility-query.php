<?php namespace CAHNRS\Plugin\AccessibilityReport;

class CAHNRSAccessibilityQuery {
    private $total_errors = 0;
    private $total_alerts = 0;
    private $total_warnings = 0;

    public function cahnrs_report_query(){

        $selected_post_types = array('post', 'page');    
        $selected_issue_types = get_option('selected_issue_types', array());
        
        $args = array(
            'post_type' => $selected_post_types,
            'posts_per_page' => -1, 
        );

        $query = new \WP_Query($args);

        if ($query->have_posts()) {
        
            while ($query->have_posts()) {
                $query->the_post();
                $post_id = get_the_ID();
                $custom_meta = get_post_meta($post_id, 'wsuwp_accessibility_report', true);
                $report = json_decode($custom_meta);


                if (!empty($report->errors) || !empty($report->alerts) || !empty($report->warnings)) {

                    if(in_array('errors', $selected_issue_types) && !empty($report->errors)){
                        foreach ($report->errors as $error) {
                            $this->total_errors++;
                        }
                    }
                    
                    if(in_array('alerts', $selected_issue_types) && !empty($report->alerts)){
                        foreach ($report->alerts as $alert) {
                            $this->total_alerts++;
                        }
                    } 
                    

                    if(in_array('warnings', $selected_issue_types) && !empty($report->warnings)){
                        foreach ($report->warnings as $warning) {
                            $this->total_warnings++;
                        }
                    }

                }
            }

        }

        return [
            'total_errors' => $this->total_errors,
            'total_warnings' => $this->total_warnings,
            'total_alerts' => $this->total_alerts
        ];

        wp_reset_postdata();

    }
}

