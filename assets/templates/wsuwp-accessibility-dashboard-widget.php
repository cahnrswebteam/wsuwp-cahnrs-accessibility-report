<?php namespace CAHNRS\Plugin\AccessibilityReport;

$wsu_accessibility_query = new CAHNRSAccessibilityQuery();
$report_data = $wsu_accessibility_query->cahnrs_report_query();

?>

<div class="wsu-accessibility-report-dashboard-widget">
    <h2>WSU Accessibility Report</h2>
    <p>Below are your total accessibility issues on your website. To edit them, please visit the <a href="admin.php?page=cahnrs-accessibility">Accessibility Reports</a> page</p>
    <hr>

    <?php if( ($report_data['total_errors'] > 0) || ($report_data['total_alerts'] > 0 ) || ($report_data['total_warnings'] > 0 ) ){ ?>
        <h3>Total pages/posts with accessibility issues</h3>
        <ul>
            <li><?php echo '<span style="font-weight: bold;">Errors:</span> ' . $report_data['total_errors']; ?></li>
            <li><?php echo '<span style="font-weight: bold;">Alerts:</span> ' . $report_data['total_alerts']; ?></li>
            <li><?php echo '<span style="font-weight: bold;">Warnings:</span> ' . $report_data['total_warnings']; ?></li>
        </ul>
   <?php 
    }else { ?>
        <p>Congratulations! Your website currently doesn't have any accessibility issues that would be picked up by our checker.</p>
    <?php }

    ?>
    
</div>