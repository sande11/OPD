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
    <script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.1.53/pdfmake.min.js"></script>
    <script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.1.53/vfs_fonts.js"></script>

    <style>
        /* CSS styles for the bar chart */
        .graph {
            text-align: center;
        }

        .simple-bar-chart {
            display: flex;
            justify-content: space-around;
            align-items: flex-end;
            background-color: #f2f2f2;
            border-radius: 8px;
            /* padding: 1 */
        }

        .item {
            flex: 1;
            margin: 0 5px;
            border-radius: 2px;
        }

        .label {
            font-weight: bold;
        }

        form,
        .graph h1 {
            display: inline-flex;
        }

        .open-button {
            background-color: #f0a732;
            color: black;
            padding: 16px 25px 15px 15px;
            border: none;
            cursor: pointer;
            opacity: 0.8;
            right: 28px;
            width: 25px;
            margin-top: -50px;

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
                            <p class="nav-title">Reports</p>

                            <div class="user-profile">
                                <div onclick="myFunction()" class="user"><i class="fa fa-user"></i> Admin</div>
                                <div id="myDropdown" class="dropdown-content">
                                    <a href="#"><i class="fa-regular fa-address-card"></i> Profile</a>
                                    <a href="../logout.php"><i class="fa-solid fa-right-from-bracket"></i> Logout</a>
                                </div>
                            </div>
                        </div>

                        <body>
                            <?php

                            // Initialize disease counts
                            $diseaseCounts = array(
                                "Cold & Flu" => 0,
                                "STIs" => 0,
                                "Bacterial Infections" => 0,
                                "Malaria" => 0,
                                "Other" => 0
                            );

                            $stats_type = isset($_POST['stats_type']) ? $_POST['stats_type'] : 'monthly';
                            $month_condition = "MONTH(diagnosis_date) = MONTH(CURRENT_DATE())";
                            $year_condition = "YEAR(diagnosis_date) = YEAR(CURRENT_DATE())";

                            $queries = array(
                                "monthly" => array(
                                    "Cold & Flu" => "SELECT COUNT(*) AS count FROM diagnosis WHERE ($month_condition) AND (diagnosis LIKE '%cold%' OR diagnosis LIKE '%flu%')",
                                    "STIs" => "SELECT COUNT(*) AS count FROM diagnosis WHERE ($month_condition) AND (diagnosis LIKE '%sti%' OR diagnosis LIKE '%sexually transmitted infection%')",
                                    "Bacterial Infections" => "SELECT COUNT(*) AS count FROM diagnosis WHERE ($month_condition) AND (diagnosis LIKE '%bacterial infection%')",
                                    "Malaria" => "SELECT COUNT(*) AS count FROM diagnosis WHERE ($month_condition) AND (diagnosis LIKE '%malaria%')"
                                ),
                                "yearly" => array(
                                    "Cold & Flu" => "SELECT COUNT(*) AS count FROM diagnosis WHERE ($year_condition) AND (diagnosis LIKE '%cold%' OR diagnosis LIKE '%flu%')",
                                    "STIs" => "SELECT COUNT(*) AS count FROM diagnosis WHERE ($year_condition) AND (diagnosis LIKE '%sti%' OR diagnosis LIKE '%sexually transmitted infection%')",
                                    "Bacterial Infections" => "SELECT COUNT(*) AS count FROM diagnosis WHERE ($year_condition) AND (diagnosis LIKE '%bacterial infection%')",
                                    "Malaria" => "SELECT COUNT(*) AS count FROM diagnosis WHERE ($year_condition) AND (diagnosis LIKE '%malaria%')"
                                )
                            );

                            // Execute queries and update disease counts
                            foreach ($queries[$stats_type] as $disease => $query) {
                                $result = mysqli_query($conn, $query);
                                if ($result && mysqli_num_rows($result) > 0) {
                                    $row = mysqli_fetch_assoc($result);
                                    $diseaseCounts[$disease] = intval($row['count']);
                                }
                            }

                            // Calculate total count for all diseases
                            $totalCount = array_sum($diseaseCounts);

                            // Calculate percentages for each disease
                            $diseasePercentages = array();
                            foreach ($diseaseCounts as $disease => $count) {
                                $percentage = ($totalCount > 0) ? ($count / $totalCount) * 100 : 0;
                                $diseasePercentages[$disease] = $percentage;
                            }

                            ?>

                            <div class="graph">

                                <form method="post" style="width: 250px; margin-top: 10px; margin-bottom: 10px;margin-left: -10px; font-size: 20px;" class="side">
                                    <label for="stats_type" style="background-color: #6db2e7; font-size: 20px;">Select Stats Type:</label>
                                    <select name="stats_type" id="stats_type" onchange="this.form.submit()">
                                        <option value="monthly" <?php if (isset($_POST['stats_type']) && $_POST['stats_type'] == 'monthly') echo 'selected'; ?>>Monthly disease stats</option>
                                        <option value="yearly" <?php if (isset($_POST['stats_type']) && $_POST['stats_type'] == 'yearly') echo 'selected'; ?>>Yearly disease stats</option>
                                    </select>
                                </form>
                                <!-- <button style="width: 250px; margin-top: 10px; margin-bottom: 10px;margin-left: -30px; font-size: 20px; float: right;">Download</button> -->
                                <div class="simple-bar-chart" style="height: 450px;">
                                    <?php foreach ($diseasePercentages as $disease => $percentage) : ?>
                                        <div class="item" style="--clr: <?php echo getRandomColor(); ?>; --val: <?php echo $percentage; ?>">
                                            <div class="label"><?php echo $disease; ?></div>
                                            <div class="value"><?php echo number_format($percentage, 2); ?> %</div>
                                        </div>
                                    <?php endforeach; ?>
                                </div>
                            </div>

                            <?php

                            mysqli_close($conn);

                            // Function to generate a random color for the bar chart items
                            function getRandomColor()
                            {
                                $colors = array('#f1c305', '#5EB344', '#F8821A', '#00008B', '#E0393E', '#3eaf43');
                                return $colors[array_rand($colors)];
                            }
                            ?>

                            <button class="open-button" id="export_pdf" onclick="generatePDF();" style="background-color:#f0a732;" download><i class="fa-solid fa-download"></i></button>
                            <script>
                                function generatePDF() {
                                    // Get current month
                                    var months = ['January', 'February', 'March', 'April', 'May', 'June', 'July', 'August', 'September', 'October', 'November', 'December'];
                                    var currentMonth = months[new Date().getMonth()];

                                    // Define document content
                                    var docDefinition = {
                                        content: [{
                                                text: 'Disease Statistics',
                                                style: 'header'
                                            },
                                            {
                                                text: 'Stats Type: <?php echo ucfirst($stats_type); ?>',
                                                style: 'subheader'
                                            },
                                            {
                                                text: 'Month: ' + currentMonth, // Display current month
                                                style: 'subheader'
                                            },
                                            {
                                                table: {
                                                    widths: ['*', 'auto', 'auto'],
                                                    body: [
                                                        ['Disease', 'Count', 'Percentage'],
                                                        <?php foreach ($diseaseCounts as $disease => $count) : ?>['<?php echo $disease; ?>', '<?php echo $count; ?>', '<?php echo number_format($diseasePercentages[$disease], 2); ?>%'],
                                                        <?php endforeach; ?>['Total', '<?php echo $totalCount; ?>', '100%']
                                                    ]
                                                }
                                            }
                                        ],
                                        styles: {
                                            header: {
                                                fontSize: 18,
                                                bold: true,
                                                alignment: 'center',
                                                margin: [0, 0, 0, 20]
                                            },
                                            subheader: {
                                                fontSize: 14,
                                                bold: true,
                                                margin: [0, 0, 0, 10]
                                            }
                                        }
                                    };

                                    // Create PDF
                                    pdfMake.createPdf(docDefinition).open();
                                }
                            </script>
