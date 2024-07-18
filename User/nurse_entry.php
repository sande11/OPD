<?php
include_once '../includes/conn.php';

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    // Check if the necessary data is submitted
    if (isset($_POST["visit_id"], $_POST["temperature"], $_POST["weight"], $_POST["bp"])) {
        // Use proper validation and sanitation
        $visit_id = intval($_POST["visit_id"]); // Assuming it's an integer, adjust accordingly
        $temperature = mysqli_real_escape_string($conn, $_POST["temperature"]);
        $weight = mysqli_real_escape_string($conn, $_POST["weight"]);
        $blood_pressure = mysqli_real_escape_string($conn, $_POST["bp"]);

        // Insert query without prepared statement (be cautious about SQL injection)
        $insert_sql = "INSERT INTO `opr` (`visit_id`, `weight`, `temperature`, `blood_pressure`) VALUES ('$visit_id', '$weight', '$temperature', '$blood_pressure')";

        // Execute the insert query
        if (mysqli_query($conn, $insert_sql)) {
            echo "Vitals inserted successfully.";
        } else {
            echo "Error inserting vitals: " . mysqli_error($conn);
        }
    } else {
        echo "Incomplete data submitted.";
    }
}
// Close the database connection
mysqli_close($conn);
header("Location: nurse.php");
?>
