<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
</head>

<body style="width: 100%; height:auto;">
    <?php
    // Include the file that contains the database connection
    include_once '../includes/conn.php';
    // Function to calculate prescription cost
    function calculatePrescriptionCost($prescription, $conn)
    {
        // Remove brackets and quotes from the prescription string
        $prescription = str_replace(['[', ']', '"'], '', $prescription);
        // Explode the prescription string by commas to get individual drugs
        $prescription_items = explode(',', $prescription);
        $total_cost = 0;

        foreach ($prescription_items as $item) {
            $item = trim($item);

            // Fetch the cost/dosage of the drug from the drugs table
            $sql = "SELECT medication_name, cost FROM drugs WHERE medication_name = '$item'";
            $result = mysqli_query($conn, $sql);

            if ($result && mysqli_num_rows($result) > 0) {
                $row = mysqli_fetch_assoc($result);
                $total_cost += $row['cost'];

                // Display the drug and its price in a table row
                echo "<tr>";
                echo "<td>" . $row['medication_name'] . "</td>";
                echo "<td>" . $row['cost'] . "</td>";
                echo "</tr>";
            }
        }

        return $total_cost;
    }

    // Check if the patient_id is provided
    if (isset($_GET['patient_id'])) {
        $patient_id = $_GET['patient_id'];

        $sql_lab_tests = "SELECT lto.test_details, t.test_price
                      FROM lab_test_orders lto
                      JOIN tests t ON lto.test_details LIKE CONCAT('%', t.test_name, '%')
                      WHERE lto.patient_id = '$patient_id'
                      AND DATE(lto.order_date) = CURDATE()";
        $result_lab_tests = mysqli_query($conn, $sql_lab_tests);

        $sql_xray_tests = "SELECT xto.imagery_details, t.test_price
                       FROM xray_test_orders xto
                       JOIN tests t ON xto.imagery_details LIKE CONCAT('%', t.test_name, '%')
                       WHERE xto.patient_id = '$patient_id'
                       AND DATE(xto.order_date) = CURDATE()";
        $result_xray_tests = mysqli_query($conn, $sql_xray_tests);

        $sql_consultation = "SELECT test_price FROM tests WHERE test_name = 'Consultation'";
        $result_consultation = mysqli_query($conn, $sql_consultation);
        $consultation_cost = 0;
        if ($result_consultation && mysqli_num_rows($result_consultation) > 0) {
            $row = mysqli_fetch_assoc($result_consultation);
            $consultation_cost = $row['test_price'];
        }

        // Prepare and execute a SQL query to fetch the prescription for the patient for the current date
        $sql_prescription = "SELECT prescription
                         FROM diagnosis
                         WHERE patient_id = '$patient_id'
                         AND DATE(diagnosis_date) = CURDATE()"; // Current date condition added
        $result_prescription = mysqli_query($conn, $sql_prescription);

        if ($result_lab_tests && $result_xray_tests && $result_prescription) {
            // Display the table header
            echo "<table border='1' style='width:100%; height: 80%; font-size:30px;'>";
            echo "<tr style='background-color:#6db2e7;';><th>Item Name</th><th>Price</th></tr>";

            // Display consultation cost
            echo "<tr>";
            echo "<td>Consultation</td>";
            echo "<td>$consultation_cost</td>";
            echo "</tr>";

            // Iterate over the lab test orders and display the test details
            while ($row = mysqli_fetch_assoc($result_lab_tests)) {
                echo "<tr>";
                echo "<td>" . $row['test_details'] . "</td>";
                echo "<td>" . $row['test_price'] . "</td>";
                echo "</tr>";
            }

            // Iterate over the X-ray test orders and display the test details
            while ($row = mysqli_fetch_assoc($result_xray_tests)) {
                echo "<tr>";
                echo "<td>" . $row['imagery_details'] . "</td>";
                echo "<td>" . $row['test_price'] . "</td>";
                echo "</tr>";
            }

            // Include consultation cost in the total hospital bill
            $total_hospital_bill = $consultation_cost;

            // Iterate over the prescriptions and display each drug with its price
            $total_prescription_cost = 0;
            while ($row = mysqli_fetch_assoc($result_prescription)) {
                $total_prescription_cost += calculatePrescriptionCost($row['prescription'], $conn);
            }

            // Add prescription cost to the total hospital bill
            $total_hospital_bill += $total_prescription_cost;

            // Add test prices to the total hospital bill
            mysqli_data_seek($result_lab_tests, 0); // Reset the pointer to the beginning
            while ($row = mysqli_fetch_assoc($result_lab_tests)) {
                $total_hospital_bill += $row['test_price'];
            }

            mysqli_data_seek($result_xray_tests, 0); // Reset the pointer to the beginning
            while ($row = mysqli_fetch_assoc($result_xray_tests)) {
                $total_hospital_bill += $row['test_price'];
            }

            // Display the total prescription cost as an additional row
            echo "<tr><td colspan='2'><strong>Total Prescription Cost:</strong>K$total_prescription_cost</td></tr>";

            // Display the total hospital bill as an additional row
            echo "<tr><td colspan='2'><strong>Total Hospital Bill:</strong>K$total_hospital_bill</td></tr>";

            echo "</table>";
        } else {
            echo "Error: " . mysqli_error($conn);
        }
    } else {
        echo "Patient ID not provided.";
    }
    ?>
</body>

</html>
