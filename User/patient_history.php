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

    if (isset($_GET['patient_id'])) {
        $patient_id = $_GET['patient_id'];

        $nameQuery = "SELECT name FROM patient_records WHERE patient_id = '$patient_id'";
        $nameResult = mysqli_query($conn, $nameQuery);

        if ($nameResult && mysqli_num_rows($nameResult) > 0) {
            $row = mysqli_fetch_assoc($nameResult);
            $patient_name = $row['name']; // Get the patient's name

            // Construct the main query to retrieve diagnosis and test information
            $sqlQuery = "SELECT d.diagnosis_date, lto.test_details, lto.results, d.diagnosis, d.prescription
            FROM diagnosis d
            LEFT JOIN lab_test_orders lto ON d.patient_id = lto.patient_id
            WHERE d.patient_id = '$patient_id'";

            $result = mysqli_query($conn, $sqlQuery);

            if ($result) {
                echo "<h2>Patient's Name: $patient_name</h2>";

                echo "<table>";
                echo "<tr>
                    <th>Date</th>
                    <th>Lab Tests Ordered</th>
                    <th>Lab Results</th>
                    <th>Diagnosis</th>
                    <th>Prescription</th>
                  </tr>";

                while ($row = mysqli_fetch_assoc($result)) {
                    // Handle NULL values or missing data
                    $lab_tests_ordered = $row['test_details'] ?? 'N/A';
                    $lab_results = $row['results'] ?? 'N/A';

                    echo "<tr>
                        <td>{$row['diagnosis_date']}</td>
                        <td>{$lab_tests_ordered}</td>
                        <td>{$lab_results}</td>
                        <td>{$row['diagnosis']}</td>
                        <td>{$row['prescription']}</td>
                      </tr>";
                }

                echo "</table>";
            } else {
                echo "Error: " . mysqli_error($conn);
            }

            // Now, let's fetch and display X-ray imagery information in a separate table
            $xrayQuery = "SELECT xto.created_at, xto.imagery_details, xto.image_data 
            FROM xray_test_orders xto 
            WHERE xto.patient_id = '$patient_id'";

            $xrayResult = mysqli_query($conn, $xrayQuery);

            if ($xrayResult && mysqli_num_rows($xrayResult) > 0) {
                echo "<h2>X-ray Imagery Information</h2>";
                echo "<table>";
                echo "<tr>
          <th>Date</th>
          <th>X-ray Imagery Ordered</th>
          <th>Images</th>
        </tr>";

                while ($xrayRow = mysqli_fetch_assoc($xrayResult)) {
                    $xray_date = $xrayRow['created_at'];
                    $xray_imagery_ordered = $xrayRow['imagery_details'] ?? 'N/A';
                    $xray_imagery_results = $xrayRow['image_data'] ?? 'N/A';

                    echo "<tr>
              <td>{$xray_date}</td>
              <td>{$xray_imagery_ordered}</td>
              <td><button onclick=\"openImagePopup('../xray/" . htmlspecialchars($xray_imagery_results) . "')\">View</button></td>
            </tr>";
                }

                echo "</table>";
            }
        }
    }
    ?>
    <script>
        function openImagePopup(imagePath) {
            var imgWindow = window.open('', 'ImagePopup', 'width=800,height=600');
            imgWindow.document.write('<html><head><title>Image Popup</title></head><body><img src="' + imagePath + '" alt="X-ray Image"></body></html>');
            imgWindow.focus();
        }
    </script>
</body>

</html>
