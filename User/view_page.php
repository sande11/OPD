
<?php
include_once '../includes/conn.php';

$current_date = date("Y-m-d"); // Get current date
$date_range_start = date('Y-m-d', strtotime($current_date . ' -2 days')); // Start date (current date - 2 days)
$date_range_end = $current_date; // End date (current date)

if (isset($_GET['patient_id'])) {
    $patient_id = $_GET['patient_id'];
    $sql_vitals = "SELECT * FROM vital_records WHERE patient_id = '$patient_id' AND DATE(created_at) ='$current_date'";
    $result_vitals = mysqli_query($conn, $sql_vitals);
}

// Display vitals if available

if (mysqli_num_rows($result_vitals) > 0) {
    echo '<h2 style="background-color: #6db2e7; text-align: center;">Patient\'s Vitals</h2>';
    echo '<ul style= "font-size: 20px;">';
    while ($row_vitals = mysqli_fetch_assoc($result_vitals)) {
        echo '<ul>';

        if (!empty($row_vitals)) {
            echo '<li>';
            echo 'Visit Date: ' . ($row_vitals['visit_date'] ?? 'N/A') . '';
            echo '</li>';
            echo '<li>';
            echo 'Weight: ' . ($row_vitals['weight'] ?? 'N/A') . '';
            echo '</li>';
            echo '<li>';
            echo 'Temperature: ' . ($row_vitals['temperature'] ?? 'N/A') . '';
            echo '</li>';
            echo '<li>';
            echo 'BP: ' . ($row_vitals['blood_pressure'] ?? 'N/A') . '';
            echo '</li>';
            echo '<li>';
            echo 'Heart Rate: ' . ($row_vitals['heart_rate'] ?? 'N/A') . '';
            echo '</li>';
            echo '<li>';
            echo 'Respiratory Rate: ' . ($row_vitals['respiratory_rate'] ?? 'N/A') . '';
            echo '</li>';
        }
    }

    echo '</ul>';
} else {
    echo '<p>No vitals found for this patient.</p>';
}
echo '</div>';
echo '<div style=" margin-top: 40px;">';
echo '<a href="patient_history.php?patient_id=' . $patient_id . '" style="margin-left: 270px;">';
echo '<button type="button" style="background-color:#f0a732; padding: 15px; cursor: pointer; font-size:15px;">View Medical History</button>';
echo '</a>';
echo '</div>';


?>
