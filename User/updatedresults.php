<?php 
include_once '../includes/conn.php';

if (isset($_GET['order_id'])) {
    $order_id = $_GET['order_id'];
    $sql_orders = "SELECT results, created_at FROM lab_test_orders WHERE order_id = '$order_id'";
    $result_orders = mysqli_query($conn, $sql_orders);
   
   }
                            
            //  // view patient 
            //  echo '<div class="form-popup" id="view" style="display:none;">';
            //  echo '<form action="" class="form-container" method="POST">';
             echo '<h1>Laboratory</h1>';
             // Display vitals if available
                            
             if (mysqli_num_rows($result_orders) > 0) {
                 echo '<h2>Lab Results</h2>';
                 echo '<ul>';
                 while ($row_orders = mysqli_fetch_assoc($result_orders)) {
                     echo '<li>';
                     echo 'Results: ' . $row_orders['results'] . ' ';
                     echo '</li>';

                     echo '<li>';
                     echo 'Date: ' . $row_orders['created_at'] . ' ';
                     echo '</li>';
                 }
                 echo '</ul>';
            } else {
                 echo '<p>No test results found for this patient.</p>';
             }
            //  echo '<button type="button" class="btn cancel" onclick="closeView()">View Medical History</button>';
             echo '</div>';
           
        ?>
