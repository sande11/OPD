<?php
session_start();

if (isset($_SESSION['name'])) {
    $userName = $_SESSION['name'];
    $departmentName = $_SESSION['department'];
} else {
    header("Location: ../Auth.php");
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
    <link rel="stylesheet" type="text/css" href="../style/nurse.css">
    <link rel="stylesheet" href="../css/all.css">
    <script src="../java.js"></script>
    <style>
        form .view {
            display: inline-flexbox;
            float: right;
            padding: 10px;
            margin: 8px;
            border-radius: 5px;
            border: 1px;
            background-color: #f0a732;
        }

        .pagination {
            display: flex;
            justify-content: center;
            margin-top: 20px;
            margin-left: 200px;
            font-size: 20px;
            position: fixed;
            bottom: 20px;
            left: 0;
            right: 0
        }

        .pagination a {
            color: black;
            padding: 8px 16px;
            text-decoration: none;
        }

        .pagination a:hover {
            background-color: #f2f2f2;
        }
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
                    <li>
                        <a href="Home.php"><i class="fa fa-th-large"></i> Home</a>
                    </li>
                    <li>
                        <a href="dataclerk.php"><i class="fa-solid fa-hospital-user"></i>Register Patient</a>
                    </li>
                    <li>
                        <a href="OPR.php"><i class="fa fa-book"></i> OPR</a>
                    </li>
                    <li>
                        <a href="staff.php"><i class="fa fa-user-doctor"></i>Register Staff</a>
                    </li>
                    <li>
                        <a href="../calendar/index.php"><i class="fa fa-calendar-days"></i>Appointments</a>
                    </li><!-- <li>
              <a href=""><i class="fa fa-money-bill"></i> Bills</a>
            </li>
            <li>
              <a href=""><i class="fa fa-bullhorn"></i> Support</a>
            </li> -->
                </ul>

                <br><br>
                <div class="fill" style="margin-top:40px;">
                    <img src="../Assets/undraw_medicine_b1ol.png">
                </div>
            </div>
            <br>
            <div class="right-div">
                <div id="main">
                    <br>
                    <div class="head">
                        <div class="col-1">
                            <p class="nav-title">Dataclerk</p>

                            <div class="user-profile">
                                <div onclick="myFunction()" class="user"><i class="fa fa-user"></i> <?php echo $userName ?> </div>
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
                        <br>
                        <div class="search-div">
                            <form method="GET" action="">
                                <div class="search">
                                    <input type="text" class="searchTerm" name="search_query" placeholder="Enter Patient Name">
                                    <button type="submit" class="searchButton">
                                        <i class="fa fa-search"></i>
                                    </button>
                                </div>
                            </form>
                        </div>

                        <?php

                        // display data
                        include_once '../includes/conn.php';

                        $limit = 8; // Number of records per page
                        $page = isset($_GET['page']) ? $_GET['page'] : 1; // Current page number, default is 1
                        $start = ($page - 1) * $limit; // Offset for SQL query

                        if (isset($_GET['search_query'])) {
                            $search_query = $_GET['search_query'];

                            // Construct the SQL query to search for the patient
                            $sql = "SELECT * FROM patient_records WHERE name LIKE '%$search_query%' OR id_number LIKE '%$search_query%'";
                        } else {
                            // Default query when no search query is present
                            $sql = "SELECT patient_id, name FROM patient_records LIMIT $start, $limit";
                        }

                        $result = mysqli_query($conn, $sql);
                        // Handle status update
                        if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST["patient_id"]) && isset($_POST["patient_status"])) {
                            $patient_id = $_POST["patient_id"];
                            $status = $_POST["patient_status"];

                            // Update status for the selected patient ID
                            $update_sql = "UPDATE patient_records SET status = '$status' WHERE patient_id = $patient_id";
                            $update_result = mysqli_query($conn, $update_sql);

                            if ($update_result) {
                                $alert = "Patient refered to $status successfully";
                                echo "<script>alert('$alert')</script>";
                            } else {
                                echo "Error updating record: " . mysqli_error($conn);
                            }
                        }

                        // Display patient data
                        if (mysqli_num_rows($result) > 0) {
                            while ($row = mysqli_fetch_assoc($result)) {
                                echo '<div class="patient-block">';
                                echo '<p class="name">' . $row['name'] . '</p>';
                                echo '<form method="post" action="OPR.php">';
                                echo '<input type="hidden" name="patient_id" value="' . $row['patient_id'] . '">'; // Hidden input for patient ID
                                echo '<button type="submit" class="view">Proceed</button>';
                                echo '<select name="patient_status" class="view">';
                                echo '<option>Refer To:</option>';
                                echo '<option value="Nurse">See Nurse</option>';
                                // echo '<option value="Doctor">See Doctor</option>';
                                echo '<option value="Pharmacy">See Pharmacist</option>';
                                echo '</select>';
                                echo '</form>';
                                echo '<button class="view"><a href="./patient_profile.php?patient_id=' . $row["patient_id"] . '">View Patient</a></button>';
                                echo '</div>';
                            }
                        } else {
                            if (isset($_GET['search_query'])) {
                                echo '<h2 style="text-align: center;">No results found. Please register the patient</h2>';
                            } else {
                                echo "<h2>No patients registered yet.</h2>";
                            }
                        }


                        $sqlTotal = "SELECT COUNT(*) as total FROM patient_records";
                        $resultTotal = mysqli_query($conn, $sqlTotal);
                        $rowTotal = mysqli_fetch_assoc($resultTotal);
                        $totalPages = ceil($rowTotal['total'] / $limit);

                        // echo '<div style="margin-left: 30px; margin-top: 10px;">';
                        echo '<div class="pagination">';

                        for ($i = 1; $i <= $totalPages; $i++) {
                            echo '<a href="?page=' . $i . '"> ' . $i . '</a>';
                        }
                        echo '</div>';

                        mysqli_close($conn);
                        ?>
