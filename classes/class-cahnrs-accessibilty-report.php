<?php namespace CAHNRS\Plugin\AccessibilityReport;

class CAHNRS_Accessibility_Report_Menu {

    private $total_errors = 0;
    private $total_alerts = 0;
    private $total_warnings = 0;

    
    public function __construct() {
        add_action('admin_menu', array($this, 'cahnrs_accessibility_menu_page'));
    }


    // Sets up the menu page for accessibility report
    public function cahnrs_accessibility_menu_page() {
        $parent_slug = 'cahnrs-accessibility';

        add_menu_page(
            'WSU Accessiblity Report',
            'WSU Accessiblity Report',
            'manage_options',
            $parent_slug,  
            array($this, 'cahnrs_create_menu_page'),
            'dashicons-admin-generic',

        );

        add_submenu_page(
            $parent_slug,
            'WSU Accessiblity ReportSettings',
            'Settings', 
            'manage_options',
            'cahnrs-settings-page', 
            array($this, 'cahnrs_create_sub_page')
        );
    }


    // Creates the Main reports page 
    public function cahnrs_create_menu_page() {
        echo '<div style="display:flex;align-items: baseline;">';
        echo '<div class="wrap" style="flex: 1 65%;"><h2>' . get_bloginfo('name') . ' Accessibility Report</h2>';
    
        //Right now only use post and pages. Later other post types can be implemented
        $selected_post_types = array('post', 'page');
        $selected_issue_types = get_option('selected_issue_types', array());
    
        //Generate the report
        if (!empty($selected_post_types)) {
            foreach ($selected_post_types as $selected_post_type) {
                $this->cahnrs_generate_post_type_report($selected_post_type, $selected_issue_types);
            }
        }
    
        echo '</div>';
    
        //Retrieve sidebar with total accessibility issues
        $this->cahnrs_generate_summary_sidebar();
        echo '</div>';
    }


    // Creates report of all accessibility issues on the selected post types
    private function cahnrs_generate_post_type_report($selected_post_type, $selected_issue_types) {
        $args = array(
            'post_type' => $selected_post_type,
            'posts_per_page' => -1,
        );

        $query = new \WP_Query($args);

        if ($query->have_posts()) {
            echo '<h3>' . ucfirst($selected_post_type) . 's</h3>';
            echo '<table class="wp-list-table widefat fixed striped">';
            echo '<thead><tr><th>Title</th><th>Accessibilty Issues</th></tr></thead>';
            echo '<tbody>';

            while ($query->have_posts()) {
                $query->the_post();
                $post_id = get_the_ID();
                $custom_meta = get_post_meta($post_id, 'wsuwp_accessibility_report', true);
                $report = json_decode($custom_meta);

                $has_selected_issues = false;
                foreach ($selected_issue_types as $issue_type) {
                    if (!empty($report->$issue_type)) {
                        $has_selected_issues = true;
                        break;
                    }
                }

                if ($has_selected_issues) {
                    echo "<tr>";

                    echo '<td><a href="' . get_edit_post_link() . '">' . get_the_title() . '</a></td>';

                       echo '<td>';

                        if(in_array('errors', $selected_issue_types) && !empty($report->errors)){
                            echo "<h4 style='margin: 0;'>Errors</h4>";
                            foreach ($report->errors as $error) {
                                echo $error->message . " (Error) <br>";
                                $this->total_errors++;
                            }
                        }

                        if(in_array('alerts', $selected_issue_types) && !empty($report->alerts)){
                            echo "<h4 style='margin: 0;'>Alerts</h4>";
                            foreach ($report->alerts as $alert) {
                                echo $alert->message . " (Alert) <br>";
                                $this->total_alerts++;
                            }
                        } 

                        if(in_array('warnings', $selected_issue_types) && !empty($report->warnings)){
                            echo "<h4 style='margin: 0;'>Warnings</h4>";
                            foreach ($report->warnings as $warning) {
                                echo $warning->message . " (Warning) <br>";
                                $this->total_warnings++;
                            }
                        }
                       
                       echo '</td>';
                       echo '</tr>';
                   }
               }

               
               echo '</tbody></table>';
           }

           wp_reset_postdata();
       }
    

    //Creates sidebar to let user know total amount of issues on their site. 
    private function cahnrs_generate_summary_sidebar() {
        echo '<div style="flex: 1 15%;background: white;padding: 20px 20px 110px;border-top: #ca1237 6px solid;position: relative;top: 120px;">';
    
        $selected_issue_types = get_option('selected_issue_types', array());
        
        echo "<h3>Total accessibility issues</h3>";
        
        if(($this->total_errors == 0) && ($this->total_alerts == 0) && ($this->total_warnings == 0)){
            echo "<p>There were no accessibility issues found on your site.</p>";
        }else{
            echo "<p>To change the accessibility issue/s that show up, please go to the <a href='wp-admin/admin.php?page=cahnrs-settings-page'>settings page</a>.</p>";

            if(in_array('errors', $selected_issue_types)){
                echo "<p style='margin:0;'><span style='font-weight:bold;font-size: 21px;'>$this->total_errors </span> Errors </p> ";
            }
            
            if(in_array('alerts', $selected_issue_types)){
                echo "<p style='margin:0;'><span style='font-weight:bold;font-size: 21px;'>$this->total_alerts </span> Alerts </p>";
            }
            
            if(in_array('warnings', $selected_issue_types)){
                echo "<p style='margin:0;'><span style='font-weight:bold;font-size: 21px;'>$this->total_warnings </span> Warnings </p>";
            }
        }
    
        echo '</div>';
    }


    //Settings Page
    public function cahnrs_create_sub_page() {
        $selected_issue_types = get_option('selected_issue_types', array());
        $report_email = get_option('report_email', '');
        $email_frequency = get_option('email_frequency', 'monthly');
    
        if (isset($_POST['submit'])) {
            $this->cahnrs_process_settings_form();
        }else {
            $selected_issue_types = get_option('selected_issue_types', array());
            $report_email = get_option('report_email', '');
            $email_frequency = get_option('email_frequency', 'monthly');
        }
    
        $this->displaySettingsForm($selected_issue_types, $report_email, $email_frequency);
    }


    // Processes the form on the settings page
    private function cahnrs_process_settings_form() {
        $email_frequency = get_option('email_frequency', 'monthly');

        $selected_issue_types = isset($_POST['issue_types']) ? $_POST['issue_types'] : array();
        update_option('selected_issue_types', $selected_issue_types);

        $report_email = isset($_POST['report_email']) ? sanitize_email($_POST['report_email']) : '';
        update_option('report_email', $report_email);

        $new_email_frequency = isset($_POST['email_frequency']) ? $_POST['email_frequency'] : 'monthly';

        if($email_frequency != $new_email_frequency){
            update_option('email_frequency', $new_email_frequency);
            
            include 'class-cahnrs-accessibility-cron.php';
            $cahnrs_wsu_set_email_cron = new CAHNRSEmailCron();
            $cahnrs_wsu_set_email_cron->cahnrs_accessibility_set_frequency();
        }
    
        echo '<div class="updated notice"><p>Settings successfully saved!</p></div>';
    }
    
    // Creates settings form
    private function displaySettingsForm($selected_issue_types, $report_email, $email_frequency) {
        $selected_issue_types = get_option('selected_issue_types', array());
        $report_email = get_option('report_email', '');
        $email_frequency = get_option('email_frequency', 'none');

    
        echo '<div class="wrap" style="background: white;padding: 20px;border-left: 3px solid #ca1237;"><h2>Settings</h2>';
        echo '<form method="post" action="">';
    
        echo '<div><label>Select the issues you would like to see in the report:</label></div>';
    
        $issue_types = array(
            'errors' => 'Errors',
            'alerts' => 'Alerts',
            'warnings' => 'Warnings',
        );
    
        foreach ($issue_types as $issue_key => $issue_label) {
            $checked = in_array($issue_key, $selected_issue_types) ? 'checked' : '';
            echo '<input type="checkbox" name="issue_types[]" value="' . $issue_key . '" ' . $checked . '> ' . $issue_label . '<br>';
        }
    
        echo '<br><label for="report_email">Enter Report Email Address:</label><br>';
        echo '<input type="text" name="report_email" id="report_email" value="' . esc_attr($report_email) . '" /><br>';
    
        
        echo '<br><label for="email_frequency">Select Email Frequency:</label><br>';
        echo '<select name="email_frequency">';
        echo '<option value="none" ' . selected($email_frequency, 'none', false) . '>None</option>';
        echo '<option value="weekly" ' . selected($email_frequency, 'weekly', false) . '>Weekly</option>';
        echo '<option value="monthly" ' . selected($email_frequency, 'monthly', false) . '>Monthly</option>';
        echo '</select><br>';
        echo '<br><input type="submit" name="submit" class="button-primary" value="Save Settings">';
        echo '</form>';

        $last_sent_date = get_option('last_sent_date', '');
        if(!empty($last_sent_date)){
        echo "<p>Report last sent on $last_sent_date";
        }
        

        echo '</div>';

    }
}

$CAHNRS_Accessibility_Report_Menu = new CAHNRS_Accessibility_Report_Menu();