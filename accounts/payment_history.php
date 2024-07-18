<?php
session_start();

if (isset($_SESSION['name'])) {
    $userName = $_SESSION['name'];
    $departmentName = $_SESSION['department'];
} else {
    header("Location: Auth.php");
    exit();
}
?>
<!DOCTYPE html>
<html>

<head>
    <!-- <link rel="stylesheet" type="text/css" href="../style/nurse.css"> -->
    <link rel="stylesheet" href="../css/all.css">
    <script src="../java.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/js/all.min.js"></script>
    <style>
        .back {
            margin-top: 20px;
            display: flex;
            align-items: center;
        }

        .back a {
            text-decoration: none;
            color: #333;
            font-size: 18px;
            margin-left: 10px;
        }

        .back i {
            font-size: 24px;
            color: #333;
        }

        table {
            border-collapse: collapse;
            width: 100%;
        }

        th,
        td {
            text-align: left;
            padding: 8px;
        }

        tr:nth-child(even) {
            background-color: #f2f2f2
        }

        th {
            background-color: #6db2e7;
            color: white;
        }
    </style>
</head>

<body>
    <div class="back">
        <a href="#" onclick="goBack()"><i class="fa-solid fa-chevron-left"></i> Back</a>
    </div>

    <!-- JavaScript function to go back to the previous page -->
    <script>
        function goBack() {
            window.history.back();
        }
    </script>
    <?php
    include_once '../includes/conn.php';

    // Construct the main query to retrieve payments information
    $sqlQuery = "SELECT * FROM payments WHERE state = 'used'";
    $result = mysqli_query($conn, $sqlQuery);

    if ($result) {
        echo "<table>";
        echo "<tr>
            <th>Date</th>
            <th>Amount</th>
            <th>Patient Name</th>
          </tr>";

        while ($row = mysqli_fetch_assoc($result)) {
            // Get the patient_id from the current row
            $patient_id = $row['patient_id'];

            // Query to fetch patient's name based on patient_id
            $nameQuery = "SELECT name FROM patient_records WHERE patient_id = '$patient_id'";
            $nameResult = mysqli_query($conn, $nameQuery);

            if ($nameResult && mysqli_num_rows($nameResult) > 0) {
                $nameRow = mysqli_fetch_assoc($nameResult);
                $patient_name = $nameRow['name']; // Get the patient's name

                // Extract the amount from the text "you have received"
                $message = $row['ref_num'];
                preg_match("/you have received\s*MK\s*([0-9.]+)/i", $message, $amount_matches);
                $amount = isset($amount_matches[1]) ? $amount_matches[1] : 'N/A';

                // Display payment details along with patient's name
                echo "<tr>
                    <td>{$row['payment_date']}</td>
                    <td>{$amount}</td>
                    <td>{$patient_name}</td>
                  </tr>";
            } else {
                // If no patient is found or no name is associated with the patient_id
                echo "<tr>
                    <td>{$row['payment_date']}</td>
                    <td>N/A</td>
                    <td>N/A</td>
                  </tr>";
            }
        }

        echo "</table>";
    } else {
        echo "Error: " . mysqli_error($conn);
    }
    ?>


</body>

</html>
