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
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>265 OutPatient</title>
    <link rel="stylesheet" type="text/css" href="../style/doctorsoffice.css">
    <link rel="stylesheet" href="../css/all.css">
    <script src="../java.js"></script>
    <script src="../jQuery/jquery.js"></script>
    <style>
        form .view {
            display: flex;
            float: right;
            padding: 10px;
            margin: 8px;
            border-radius: 5px;
            border: 1px;
            background-color: #f0a732;
        }

        /* form, input[type="checkbox"] {
            display: inline;
            margin-right: 10px; 
        } */
        #author_bio_wrap {
            margin-top: 0px;
            margin-bottom: 30px;
            background: gray;
            width: auto;
            height: 50px;
        }

        #author_bio_wrap_toggle {
            display: block;
            width: 100%;
            height: 35px;
            line-height: 35px;
            background: #9E9E77;
            text-align: center;
            color: white;
            font-weight: bold;
            font-variant: small-caps;
            box-shadow: 2px 2px 3px #888888;
            text-decoration: none;
        }

        .patient-block .view {
            cursor: pointer;
        }

        /* 
        .nav li a:active,
        .nav li a:hover,
        .nav li a:focus {
            background-color: #f0a732;
            color: white;
        } */
    </style>

</head>

<body>
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
                    <li><a href="doctorsoffice.php"><i class="fa-solid fa-chevron-left"></i> Doctor's Office</a></li>
                    <li><a href="testresults.php"><i class="fa-solid fa-microscope"></i> Lab Results</a></li>
                    <li><a href="digitalresults.php"><i class="fa-solid fa-microscope"></i> Digital lab results</a></li>
                    <li><a href="xray.php"><i class="fa-solid fa-x-ray"></i> Xray results</a></li>
                </ul>
                <br><br>
                <div class="fill" style="margin-top: 90px;">
                    <img src="../Assets/undraw_medicine_b1ol.png">
                </div>
            </div>
            <br>
            <div class="right-div">
                <div id="main">
                    <br>
                    <div class="head">
                        <div class="col-1">
                            <p class="nav-title">Lab Results</p>

                            <div class="user-profile">
                                <div onclick="myFunction()" class="user"><i class="fa fa-user"> </i><?php echo  $userName ?></div>
                                <div id="myDropdown" class="dropdown-content">
                                    <a href="#"><i class="fa-regular fa-address-card"></i> Profile</a>
                                    <a href="../logout.php"><i class="fa-solid fa-right-from-bracket"></i> Logout</a>
                                </div>
                            </div>
                        </div>
                        <br>
                        <br>
                        <div class="line"></div>
                        <!-- search button -->
                        <br>
                        <br>
                        <br>
                        <div style="margin-left: 893px; margin-top: -30px;">
                            <form method="post" action="" id="lab_status_form">
                                <label>Filter</label>
                                <select name="test_date" id="test_date" style="padding:6px; background-color:#f0a732;" onchange="document.getElementById('lab_status_form').submit()">
                                    <option value="Today" <?php if (isset($_POST['test_date']) && $_POST['test_date'] == 'Today') echo ' selected'; ?>>Today</option>
                                    <option value="This Week" <?php if (isset($_POST['test_date']) && $_POST['test_date'] == 'This Week') echo ' selected'; ?>>This Week</option>
                                    <option value="This Month" <?php if (isset($_POST['test_date']) && $_POST['test_date'] == 'This Month') echo ' selected'; ?>>This Month</option>
                                    <option value="All Tests" <?php if (isset($_POST['test_date']) && $_POST['test_date'] == 'All Tests') echo ' selected'; ?>>All Tests</option>
                                </select>
                            </form>
                        </div>
                        <!-- patients list -->
                        <!-- <p class="list-heading">Here is a list of patients currently being assisted</p> -->
                        <?php
                        // Include connection
                        include_once '../includes/conn.php';

                        // Fetch and display data
                        if (isset($_POST['test_date'])) {
                            $test_date = $_POST['test_date'];
                            $interval = 0;

                            switch ($test_date) {
                                case 'This Week':
                                    $interval = 7;
                                    break;
                                case 'This Month':
                                    $interval = 30;
                                    break;
                                case 'All Tests':
                                    $interval = 36500; // Approximately 100 years
                                    break;
                                    // 'Today' doesn't need special handling as it defaults to 0
                            }

                            $sql_patient = "SELECT * FROM lab_results WHERE test_date >= (CURDATE() - INTERVAL $interval DAY)";
                        } else {
                            $sql_patient = "SELECT * FROM lab_results";
                        }

                        $result = mysqli_query($conn, $sql_patient);

                        if (mysqli_num_rows($result) > 0) {
                            while ($row = mysqli_fetch_assoc($result)) {
                                $result_id = $row['result_id'];
                                $patient_name = $row['patient_name'];

                                echo '<div class="patient-block">';
                                echo '<p class="name" style="width: 80%;"> ' . $patient_name . ' || ' . $row['test_type'] . ' || '  . $row['test_date'] . ' || '  . $row['result_details'] . ' || '  . $row['normal_range'] . ' || '  . $row['unit'] . ' || '  . $row['lab_technician'] . '</p>';
                                echo '</div>';
                            }
                        } else {
                            echo '<div class="patient-block">';
                            echo '<p>No test results available for selected time frame</p>';
                            echo '</div>';
                        }
                        ?>


                        <script>
                            // test
                            function openTest() {
                                document.getElementById("myForm").style.display = "block";
                            }

                            function closeTest() {
                                document.getElementById("myForm").style.display = "none";
                            }
                            // view
                            function openView() {
                                document.getElementById("view").style.display = "block";
                            }

                            function closeView() {
                                document.getElementById("view").style.display = "none";
                            }
                        </script>
