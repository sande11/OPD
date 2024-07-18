<?php

session_start();

if (isset($_SESSION['name'])) {
    $userName = $_SESSION['name'];
    $departmentName = $_SESSION['department'];
    $userId = $_SESSION['user_id'];
} else {
    header("Location: ../Auth.php");
    exit();
}

include_once '../includes/conn.php';
// Update test orders
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['patient_id'])) {
    $patient_id = $_POST['patient_id'];
    $test_details = isset($_POST["lab_test_orders"]) ? $_POST["lab_test_orders"] : '';
    $imagery_details = isset($_POST["xray_imaging_orders"]) ? $_POST["xray_imaging_orders"] : '';

    // Check if lab test details are provided and insert into lab test orders table
    if (!empty($test_details)) {
        $lab_test_query = "INSERT INTO lab_test_orders (patient_id, test_details, ordered_by, order_date, created_at, updated_at) VALUES ('$patient_id', '$test_details', '{$_SESSION['user_id']}', NOW(), NOW(), NOW())";
        $lab_test_result = mysqli_query($conn, $lab_test_query);

        if (!$lab_test_result) {
            // Error occurred
            echo "Error: " . mysqli_error($conn);
        }
    }

    // Check if X-ray imaging details are provided and insert into xray imaging orders table
    if (!empty($imagery_details)) {
        $xray_imaging_query = "INSERT INTO xray_test_orders (patient_id, imagery_details, ordered_by, order_date, created_at, updated_at) VALUES ('$patient_id', '$imagery_details', '{$_SESSION['user_id']}', NOW(), NOW(), NOW())";
        $xray_imaging_result = mysqli_query($conn, $xray_imaging_query);

        if (!$xray_imaging_result) {
            // Error occurred
            echo "Error: " . mysqli_error($conn);
        }
    }

    // If both lab test and X-ray imaging orders are filled, and both queries are successful
    if ((!empty($test_details) && $lab_test_result) || (!empty($imagery_details) && $xray_imaging_result)) {
        echo "<script>alert('Test orders submitted successfully.')</script>";
        echo "<script>window.location.href = 'doctorsoffice.php';</script>"; // Redirect back to the doctor's office page
    }
}
