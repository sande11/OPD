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
    <script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.1.53/pdfmake.min.js"></script>
    <script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.1.53/vfs_fonts.js"></script>
    <style>
        .open-button {
            background-color: #f0a732;
            color: black;
            padding: 16px 25px 15px 15px;
            border: none;
            cursor: pointer;
            opacity: 0.8;
            right: 20px;
            width: 25px;
            margin-top: -80px;
            position: fixed;
        }
    </style>
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
                            <p class="nav-title">Registration Reports</p>

                            <div class="user-profile">
                                <div onclick="myFunction()" class="user"><i class="fa fa-user"> </i> <?php echo  $userName ?></div>
                                <div id="myDropdown" class="dropdown-content">
                                    <a href="#"><i class="fa-regular fa-address-card"></i> Profile</a>
                                    <a href="../logout.php"><i class="fa-solid fa-right-from-bracket"></i> Logout</a>
                                </div>
                            </div>
                        </div>
                        <canvas id="myChart" style="width:100%;max-width:900px; margin-top: 150px; margin-left: 80px;"></canvas>

                        <button class="open-button" id="export_pdf" onclick="exportPDF();" style="background-color:#f0a732;"><i class="fa-solid fa-download"></i></button>
                        <?php

                        $sql = "SELECT MONTHNAME(`date_joined`) AS `month_name`, COUNT(*) AS `registrations` 
        FROM `patient_records` 
        GROUP BY MONTH(`date_joined`) 
        ORDER BY MONTH(`date_joined`) ASC";

                        // Initialize arrays to hold the data
                        $xValues = [];
                        $registrationsData = [];
                        $patientNamesByMonth = [];

                        // Attempt to execute the SQL query
                        $result = $conn->query($sql);

                        if ($result === false) {
                            echo "Error executing the query: " . $conn->error;
                        } else {
                            // Check if there are any rows returned
                            if ($result->num_rows > 0) {
                                // Loop through the results and store the data in the arrays
                                while ($row = $result->fetch_assoc()) {
                                    // Store month name
                                    $monthName = $row["month_name"];
                                    $xValues[] = $monthName;
                                    // Store registration count as integer
                                    $registrationsData[] = (int)$row["registrations"];
                                    // Fetch patient names for this month
                                    $sqlNames = "SELECT `name` FROM `patient_records` WHERE MONTHNAME(`date_joined`) = '$monthName'";
                                    $resultNames = $conn->query($sqlNames);
                                    $patientNames = [];
                                    if ($resultNames === false) {
                                        echo "Error executing the query: " . $conn->error;
                                    } else {
                                        if ($resultNames->num_rows > 0) {
                                            while ($nameRow = $resultNames->fetch_assoc()) {
                                                $patientNames[] = $nameRow["name"];
                                            }
                                        }
                                    }
                                    $resultNames->close();
                                    // Store patient names for this month
                                    $patientNamesByMonth[$monthName] = $patientNames;
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
const registrationsData = [" . implode(',', $registrationsData) . "];

new Chart('myChart', {
    type: 'bar',
    data: {
        labels: xValues,
        datasets: [{
            label: 'Number of Registrations',
            data: registrationsData,
            backgroundColor: 'blue',
            borderColor: 'black',
            borderWidth: 1
        }]
    },
    options: {
        plugins: {
            datalabels: {
                display: true,
                color: 'black',
                align: 'center',
                font: {
                    weight: 'bold'
                }
            }
        },
        scales: {
            x: {
                title: {
                    display: true,
                    text: 'Month'
                }
            },
            y: {
                title: {
                    display: true,
                    text: 'Number of Registrations'
                },
                beginAtZero: true,
                suggestedMin: 0,
                ticks: {
                    stepSize: 1
                }
            }
        }
    }
});
</script>";

                        $docDefinition = [
                            'content' => [
                                [
                                    'text' => 'Monthly Registration Statistics',
                                    'style' => 'header'
                                ],
                                [
                                    'table' => [
                                        'widths' => ['30%', '30%', 'auto'],
                                        'body' => [
                                            ['Month', 'Registrations', 'Patient Names'],
                                        ]
                                    ]
                                ]
                            ],
                            'styles' => [
                                'header' => [
                                    'fontSize' => 18,
                                    'bold' => true,
                                    'alignment' => 'center',
                                    'margin' => [0, 0, 0, 20]
                                ]
                            ]
                        ];

                        // put data into the table
                        for ($i = 0; $i < count($xValues); $i++) {
                            $monthName = $xValues[$i];
                            $registrations = $registrationsData[$i];
                            $patientNames = implode(', ', $patientNamesByMonth[$monthName]);
                            $docDefinition['content'][1]['table']['body'][] = [$monthName, $registrations, $patientNames];
                        }

                        ?>
                        <script>
                            function exportPDF() {
                                //generate and download the PDF
                                var docDefinition = <?php echo json_encode($docDefinition); ?>;
                                pdfMake.createPdf(docDefinition).download("registration_statistics.pdf");
                            }
                        </script>
