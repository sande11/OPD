<?php
session_start();

if (isset($_SESSION['name'])) {
    $userName = $_SESSION['name'];
    $departmentName = $_SESSION['department'];
} else {
    header("Location: Auth.php");
    exit();
}

include_once '../includes/conn.php';
$sqlcount = "SELECT COUNT(*) AS nurse_count FROM patient_records WHERE status = 'Nurse'";
$result = $conn->query($sqlcount);

if ($result->num_rows > 0) {
    // Output data of each row
    while ($row = $result->fetch_assoc()) {
        $nurseCount = $row["nurse_count"];
    }
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

        .notification {
            background-color: #f0f8fd;
            color: white;
            text-decoration: none;
            padding: 15px 0px;
            position: relative;
            display: inline-block;
            border-radius: 2px;
        }


        .notification .badge {
            position: absolute;
            top: -15px;
            right: -18px;
            padding: 5px 10px;
            border-radius: 50%;
            background: red;
            color: white;
        }

        table,
        th,
        td {
            border: 1px solid black;
            border-collapse: collapse;
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
                    <li><a href="admin-panel.php"><i class="fa-solid fa-chevron-left"></i>Admin panel</a></li>
                </ul>
                <br><br>
                <div class="fill" style="margin-top: 180px;">
                    <img src="../Assets/undraw_medicine_b1ol.png">
                </div>
            </div>
            <br>
            <div class="right-div">
                <div id="main">
                    <br>
                    <div class="head">
                        <div class="col-1">
                            <p class="nav-title">Nurse</p>

                            <div class="user-profile">
                                <div onclick="myFunction()" class="user"><i class="fa fa-user"></i> <?php echo $userName ?></div>
                                <div id="myDropdown" class="dropdown-content">
                                    <a href="#"><i class="fa-regular fa-address-card"></i> Profile</a>
                                    <a href="../logout.php"><i class="fa-solid fa-right-from-bracket"></i> Logout</a>
                                </div>
                            </div>
                        </div>
                        <br>
                        <br>
                        <div class="line"></div>



                        <?php

                        $sql = "SELECT patient_id, name FROM patient_records WHERE status = 'Nurse'";
                        $result = mysqli_query($conn, $sql);

                        $patient_id = "";
                        if (mysqli_num_rows($result) > 0) {
                            while ($row = mysqli_fetch_assoc($result)) {
                                $patient_id = $row['patient_id'];
                                echo '<div class="patient-block">';
                                echo '<p class="name">' . $row['name'] . '</p>';

                                // $room = "SELECT * FROM rooms WHERE Status = 'Active'";
                                // $resultRooms = $conn->query($room);
                                // echo '<form method="post" action="">';
                                // echo '<input type="hidden" name="patient_id" value="' . $row['patient_id'] . '">';
                                // echo '<input type="hidden" name="name" value="' . $row['name'] . '">';
                                // echo '<button type="submit" class="view">Proceed</button>';
                                // echo '<select name="patient_status" class="view">';
                                // while ($rowRoom = $resultRooms->fetch_assoc()) {
                                //     $roomName = $rowRoom["RoomName"];
                                //     echo '<option value="' . $roomName . '">' . $roomName . '</option>';
                                // }
                                // echo '</select>';
                                // echo '</form>';
                                // echo '<button class="record" onclick="openForm()">Record Vitals</button>';
                                // echo '<button class="view"><a href="./patient_history.php?patient_id=' . $row["patient_id"] . '">View Patient</a></button>';
                                // echo '</div>';

                                // vitals form
                                echo '<div class="form-popup" id="myForm" style="margin-top: -130px">';
                                echo '<form action="" class="form-container" method="POST">';
                                echo '<h1>Record Vitals</h1>';
                                echo '<input type="hidden" name="patient_id" value="' . $row['patient_id'] . '">';
                                echo '<label for="email"><b>Weight</b></label>';
                                echo '<input type="text" placeholder="Enter Weight e.g. 40kg" name="weight" required>';
                                echo '<label for="psw"><b>Temperature</b></label>';
                                echo '<input type="text" placeholder="Enter Body Temperature e.g. 37 degrees celcius" name="temperature" required>';
                                echo '<label for="psw"><b>Blood Pressure</b></label>';
                                echo '<input type="text" placeholder="Enter Blood Bressure e.g. 120/80 mmHg" name="bp" required>';
                                echo '<label for="psw"><b>Heart Rate</b></label>';
                                echo '<input type="text" placeholder="Enter Heart Rate e.g. 78 BPM" name="heart_rate">';
                                echo '<label for="psw"><b>Respiratory Rate</b></label>';
                                echo '<input type="text" placeholder="Enter Respiratory Rate e.g. 12-18 Breaths/min" name="respiratory_rate">';
                                echo '<button type="submit" class="btn" onclick="closeForm()">Submit</button>';
                                echo '<button type="button" class="btn cancel" onclick="closeForm()">Close</button>';
                                echo '</form>';
                                echo '</div>';
                            }
                        } else {
                            echo "<h2 style=\"text-align: center;\"> No Patients Waiting</h2>";
                        }
                        // submit vitals
                        if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST["weight"], $_POST["temperature"], $_POST["bp"], $_POST["patient_id"])) {
                            $patient_id = $_POST["patient_id"];
                            $weight =  $_POST["weight"];
                            $temperature = $_POST["temperature"];
                            $blood_pressure = $_POST["bp"];

                            // Check if heart_rate and respiratory_rate are set
                            if (isset($_POST["heart_rate"])) {
                                $heart_rate = mysqli_real_escape_string($conn, $_POST["heart_rate"]);
                            } else {
                                $heart_rate = NULL;
                            }

                            if (isset($_POST["respiratory_rate"])) {
                                $respiratory_rate = mysqli_real_escape_string($conn, $_POST["respiratory_rate"]);
                            } else {
                                $respiratory_rate = NULL;
                            }

                            // Get the current date
                            $visit_date = date("Y-m-d");

                            // Insert query 
                            $insert_sql = "INSERT INTO `vital_records` (`patient_id`, `visit_date`, `weight`, `temperature`, `blood_pressure`, `heart_rate`, `respiratory_rate`) 
        VALUES ('$patient_id', '$visit_date', '$weight', '$temperature', '$blood_pressure', '$heart_rate', '$respiratory_rate')";

                            if (mysqli_query($conn, $insert_sql)) {
                                $alert = "Vitals inserted successfully.";
                                echo "<script>alert('$alert')</script>";
                            } else {
                                echo "Error inserting vitals: " . mysqli_error($conn);
                            }
                        }

                        // set status
                        if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST["patient_id"]) && isset($_POST["patient_status"])) {
                            $patient_id = $_POST["patient_id"];
                            $status = $_POST["patient_status"];

                            // Update status for the selected patient ID
                            $update_sql = "UPDATE patient_records SET status = '$status' WHERE patient_id = $patient_id";
                            $update_result = mysqli_query($conn, $update_sql);

                            if ($update_result) {
                                $alert = "Patient status updated successfully";

                                echo "<script>alert('$alert'); window.location.href = 'nurse.php';</script>";
                            } else {
                                echo "Error updating record: " . mysqli_error($conn);
                            }
                        }
                        ?>
