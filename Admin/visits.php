<?php

session_start();
header("Cache-Control: no-store, no-cache, must-revalidate, post-check=0, pre-check=0");
header("Pragma: no-cache");


if (isset($_SESSION['name'])) {
    $userName = $_SESSION['name'];
    $departmentName = $_SESSION['department'];
} else {
    header("Location: ../Auth.php");
    exit();
}
include_once '../includes/conn.php';

?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>265 OutPatient</title>
    <link rel="stylesheet" type="text/css" href="../style/style.css">
    <link rel="stylesheet" href="../css/all.css">
    <script src="../java.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/Chart.js/2.9.4/Chart.js"></script>
</head>

<body style="width: 100%; height:auto;">
    <div class="overlay">
        <!-- left bar  -->
        <section>

            <div class="left">
                <div class="logo">
                    <img src="../Assets/cardiogram 2.png" alt="logo">
                    <p class="logo-name"><span>265</span>OPMS</p>
                </div>
                <br>
                <div class="side-line"></div>
                <!-- nav -->
                <ul class="nav">
                    <li><a href="admin-panel.php"><i class="fa-solid fa-chevron-left"></i> Admin Panel</a></li>
                    <li><a href="reports.php"><i class="fa-solid fa-chart-simple"></i> Disease Reports</a></li>
                    <li><a href="visits.php"><i class="fa-solid fa-chart-simple"></i> Visit Reports</a></li>
                    <li><a href="registration_report.php"><i class="fa-solid fa-chart-simple"></i> Registration Reports</a></li>
                    <!-- <li><a href="diagnosis.php"><i class="fa-solid fa-chart-simple"></i> Diagnosis Reports</a></li> -->
                    <!-- <li><a href="staff_reports.php"><i class="fa-solid fa-chart-simple"></i> Staff Reports</a></li> -->

                </ul>

                <div class="fill" style="margin-top: 150px;">
                    <img src="../Assets/undraw_medicine_b1ol.png">
                </div>
            </div>
            <br>
            <div class="right-div">
                <div id="main">
                    <br>
                    <div class="head">
                        <div class="col-1">
                            <p class="nav-title">Visit Reports</p>

                            <div class="user-profile">
                                <div onclick="myFunction()" class="user"><i class="fa fa-user"> </i> <?php echo  $userName ?></div>
                                <div id="myDropdown" class="dropdown-content">
                                    <a href="#"><i class="fa-regular fa-address-card"></i> Profile</a>
                                    <a href="../logout.php"><i class="fa-solid fa-right-from-bracket"></i> Logout</a>
                                </div>
                            </div>
                        </div>
                        <canvas id="myChart" style="width:100%;max-width:900px; margin-top: 150px; margin-left: 80px;"></canvas>
                        <?php
                        // Assuming you have already established the database connection in $conn

                        // Get the current date
                        $currentDate = date('Y-m-d');

                        // SQL query to get weekly visit counts starting from the current week
                        $sql = "SELECT WEEK(`diagnosis_date`, 1) AS `week_number`, COUNT(*) AS `visits` 
        FROM `diagnosis` 
        WHERE YEARWEEK(`diagnosis_date`, 1) <= YEARWEEK('$currentDate', 1) 
        GROUP BY WEEK(`diagnosis_date`, 1) 
        ORDER BY `week_number` ASC 
        LIMIT 8";

                        // Initialize arrays to hold the data
                        $xValues = [];
                        $visitData = [];

                        // Attempt to execute the SQL query
                        $result = $conn->query($sql);

                        if ($result === false) {
                            echo "Error executing the query: " . $conn->error;
                        } else {
                            // Check if there are any rows returned
                            if ($result->num_rows > 0) {
                                // Loop through the results and store the data in the arrays
                                while ($row = $result->fetch_assoc()) {
                                    // Store week number (assuming it's numeric)
                                    $xValues[] = 'Week ' . $row["week_number"];
                                    // Store visit count as integer
                                    $visitData[] = (int)$row["visits"];
                                }
                            } else {
                                echo "0 results";
                            }
                            // Close the result set
                            $result->close();
                        }

                        // Close the connection
                        $conn->close();

                        // Echo the JavaScript code to display the graph
                        echo "<script>
const xValues = [" . implode(',', array_map('json_encode', $xValues)) . "];
const visitData = [" . implode(',', $visitData) . "];

new Chart('myChart', {
    type: 'line',
    data: {
        labels: xValues,
        datasets: [{
            label: 'Number of Visits',
            data: visitData,
            borderColor: 'red',
            fill: false,
            datalabels: {
                align: 'end',
                anchor: 'end',
                formatter: Math.round
            }
        }]
    },
    options: {
        plugins: {
            datalabels: {
                display: true,
                color: 'black'
            }
        },
        scales: {
            x: {
                ticks: {
                    beginAtZero: true
                }
            },
            y: {
                beginAtZero: true,
                suggestedMin: 0,
                suggestedMax: 10,
                ticks: {
                    stepSize: 1
                }
            }
        }
    }
});
</script>";
                        ?>
