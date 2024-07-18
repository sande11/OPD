<?php
include_once '../includes/conn.php';

if ($_SERVER["REQUEST_METHOD"] === "POST" && isset($_POST["patient_id"], $_POST["diagnosis"], $_POST["prescription"], $_POST["quantity"])) {
    $patient_id = mysqli_real_escape_string($conn, $_POST["patient_id"]);
    $diagnosis = mysqli_real_escape_string($conn, $_POST['diagnosis']);
    $prescription = $_POST['prescription'];
    $quantity = $_POST['quantity'];
    $diagnosis_date = date("Y-m-d");

    // Convert prescription and quantity arrays to JSON format
    $prescription_json = json_encode($prescription);

    // Filter out empty quantity values and encode quantity array to JSON format
    $non_empty_quantities = array_filter($quantity, function ($value) {
        return !empty($value);
    });
    $quantity_json = json_encode(array_values($non_empty_quantities));

    // Construct and execute the SQL query to insert the diagnosis data
    $sql = "INSERT INTO diagnosis (patient_id, diagnosis_date, diagnosis, prescription, quantity)
                    VALUES ('$patient_id', '$diagnosis_date', '$diagnosis', '$prescription_json', '$quantity_json')";

    if (mysqli_query($conn, $sql)) {
        // Data inserted successfully
        $alert = "Diagnosis and Prescription updated successfully";
        echo "<script>alert('$alert')</script>";
        echo "<script>window.location.href = 'doctorsoffice.php';</script>"; // Redirect back to the doctor's office page
    }
} else {
    // Error inserting data
    echo "Error: " . $sql . "<br>" . mysqli_error($conn);
}
