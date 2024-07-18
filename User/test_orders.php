<?php 
include_once '../includes/conn.php';

$current_date = date("Y-m-d"); // Get current date
$date_range_start = date('Y-m-d', strtotime($current_date . ' -2 days')); // Start date (current date - 2 days)
$date_range_end = $current_date; // End date (current date)

if (isset($_GET['patient_id'])) {
    $patient_id = $_GET['patient_id'];
    $sql_vitals = "SELECT * FROM vital_records WHERE patient_id = '$patient_id' AND DATE(created_at) BETWEEN '$date_range_start' AND '$date_range_end'";
    $result_vitals = mysqli_query($conn, $sql_vitals);
   
   }
                            
            //  // view patient 
            //  echo '<div class="form-popup" id="view" style="display:none;">';
            //  echo '<form action="" class="form-container" method="POST">';
             echo '<h1>Patient Details</h1>';
             // Display vitals if available
                            
             if (mysqli_num_rows($result_vitals) > 0) {
                 echo '<h2>Vitals:</h2>';
                 echo '<ul>';
                 while ($row_vitals = mysqli_fetch_assoc($result_vitals)) {
                     echo '<li>';
                     echo 'Visit Date: ' . $row_vitals['visit_date'] . ', ';
                     echo '</li>';
                     echo '<li>';
                     echo 'Weight: ' . $row_vitals['weight'] . ', ';
                     echo '</li>';
                     echo '<li>';
                     echo 'Temperature: ' . $row_vitals['temperature'] . ', ';
                     echo '</li>';
                     echo '<li>';
                     echo 'BP: ' . $row_vitals['blood_pressure'] . ', ';
                     echo '</li>';
                     echo '<li>';
                     echo 'Heart Rate: ' . $row_vitals['heart_rate'] . ', ';
                     echo '</li>';
                     echo '<li>';
                     echo 'Respiratory Rate: ' . $row_vitals['respiratory_rate'] . ', ';
                     echo '</li>';
                 }
                 echo '</ul>';
            } else {
                 echo '<p>No vitals found for this patient.</p>';
             }
             echo '<button type="button" class="btn cancel" onclick="closeView()">View Medical History</button>';
             echo '</div>';
           
        ?>
