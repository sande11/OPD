<?php 
include_once '../includes/conn.php';

$current_date = date('Y-m-d');
if (isset($_GET['patient_id'])) {
    $patient_id = $_GET['patient_id'];
    $sql_orders = "SELECT * FROM xray_test_orders WHERE patient_id = '$patient_id' AND DATE(created_at)='$current_date'";
    $result_orders = mysqli_query($conn, $sql_orders);
   
   }

             echo '<h1>Xray Orders</h1>';
             // Display vitals if available
                            
             if (mysqli_num_rows($result_orders) > 0) {
                 echo '<h2>Test Details</h2>';
                 echo '<ul>';
                 while ($row_orders = mysqli_fetch_assoc($result_orders)) {
                     echo '<li>';
                     echo 'Date: ' . $row_orders['order_date'] . ' ';
                     echo '</li>';
                     echo '<li>';
                     echo 'Test Details: <br>' . $row_orders['imagery_details'] . ', ';
                     echo '</li>';
                 }
                 echo '</ul>';
            } else {
                 echo '<p>No tests orders for this patient.</p>';
             }
            //  echo '<button type="button" class="btn cancel" onclick="closeView()">View Medical History</button>';
             echo '</div>';
           
        ?>
