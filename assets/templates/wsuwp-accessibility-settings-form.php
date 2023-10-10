<div class="wrap wsuwp-accessibility-settings-form">
    <?php 
        $last_sent_date = get_option('last_sent_date', '');
        if(!empty($last_sent_date)){
            echo "<p style='text-align:right;'>Report last sent on $last_sent_date";
        }
    ?>

    <h2>Settings</h2>

    <form method="post" action="">
    <div class="wsuwp-accessibility-settings-form__accessibility-issues">
        <label>Select the issues you would like to see in the report</label>

        <?php 

            foreach ($issue_types as $issue_key => $issue_label) {
                $checked = in_array($issue_key, $selected_issue_types) ? 'checked' : '';
                echo '<input type="checkbox" name="issue_types[]" value="' . $issue_key . '" ' . $checked . '> ' . $issue_label . '<br>';
            }

        ?>
    </div>
    

    <div class="wsuwp-accessibility-settings-form__recipients">
        <label>Select Recipients</label>

        <div class="wsuwp-accessibility-settings-form__recipients__container">
            <?php
                foreach ($users as $user) {
                    $checked = in_array($user->user_email, $selected_recipients) ? 'checked' : '';
                    echo '<span><input type="checkbox" name="selected_recipients[]" value="' . $user->user_email . '" ' . $checked . '> ' . $user->user_email . ' (' . $user->roles[0] .  ')</span> ';
                }
            ?>
        </div>
        
    </div>

    <div class="wsuwp-accessibility-settings-form__email-frequency">
        <label for="email_frequency">Select Email Frequency:</label>
        <select name="email_frequency">
            <option value="none" <?php selected($email_frequency, 'none', false); ?> >None</option>
            <option value="weekly" <?php selected($email_frequency, 'weekly', false)?> >Weekly</option>
            <option value="monthly" <?php selected($email_frequency, 'monthly', false)?> >Monthly</option>
        </select>
    </div>
    
    <div class="wsuwp-accessibility-settings-form__email-message">
        <label for="custom_email_content">Custom Email Content</label>
        <span class="description">Enter your custom message that you would like to email users if they have any accessibility issues. If nothing is entered below, users will receive the message on the right.</span>

        <div class="wsuwp-accessibility-settings-form__custom-message-container">

            <div class="wsuwp-accessibility-settings-form__custom-message-container_left">
                <?php wp_editor($custom_email_content, 'custom_email_content', array('textarea_name' => 'custom_email_content')); ?>
            </div>

            <div class="wsuwp-accessibility-settings-form__custom-message-container_right">
                <h3>Default Email Message</h3>
                <?php echo $default_email_content; ?>
            </div>

        </div>

        
    </div>
    
    <input type="submit" name="submit" class="button-primary" value="Save Settings">

    </form>
</div>