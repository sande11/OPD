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

        .view p {
            display: inline-flexbox;
            float: right;
            padding: 10px;
            margin: 8px;
            border-radius: 5px;
            border: 1px;
            background-color: #f0a732;
            cursor: pointer;
        }

        table,
        th,
        td {
            border: 1px solid black;
            border-collapse: collapse;
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

        .open-button {
            background-color: #f0a732;
            color: black;
            padding: 16px 25px 15px 15px;
            border: none;
            cursor: pointer;
            opacity: 0.8;
            right: 28px;
            width: 25px;

            position: fixed;
        }

        /* The popup form - hidden by default */
        .form-popup {
            display: none;
            position: fixed;
            top: 50%;
            /* Adjusted to center vertically */
            left: 37%;
            /* Adjusted to center horizontally */
            transform: translate(-50%, -50%);
            /* Centering trick */
            border: 3px solid #f1f1f1;
            z-index: 9;
        }

        /* Add styles to the form container */
        .form-container {
            max-width: 550px;
            padding: 10px;
            background-color: white;
        }

        /* Full-width input fields */
        .form-container input[type=text],
        .form-container input[type=password] {
            width: 550px;
            padding: 15px;
            margin: 5px 0 22px 0;
            border: none;
            background: #f1f1f1;
        }

        /* When the inputs get focus, do something */
        .form-container input[type=text]:focus,
        .form-container input[type=password]:focus {
            background-color: #ddd;
            outline: none;
        }

        /* Set a style for the submit/login button */
        .form-container .btn {
            background-color: #6db2e7;
            color: black;
            padding: 16px 20px;
            border: none;
            cursor: pointer;
            width: 100%;
            margin-bottom: 10px;
            opacity: 0.8;
        }

        /* Add a red background color to the cancel button */
        .form-container .cancel {
            background-color: red;
        }

        /* Add some hover effects to buttons */
        .form-container .btn:hover,
        .open-button:hover {
            opacity: 1;
        }

        table,
        th,
        td {
            border: 2px solid #6db2e7;
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
                    <li><a href="payment.php"><i class="fa fa-th-large"></i> Home</a></li>
                    <!-- <li><a href="patients.html"><i class="fa fa-user"></i> Patients</a></li> -->
                    <li><a href="payment_history.php"><i class="fa-solid fa-book"></i>Payment History</a></li>
                    <!-- <li><a href=""><i class="fa-solid fa-phone"></i>Emergency Requests</a></li> -->
                </ul> <br><br>
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
                            <p class="nav-title">Accounts</p>

                            <div class="user-profile">
                                <div onclick="myFunction()" class="user"><i class="fa fa-user"> </i> <?php echo  $userName ?></div>
                                <div id="myDropdown" class="dropdown-content">
                                    <a href="#"><i class="fa-regular fa-address-card"></i> Profile</a>
                                    <a href="../logout.php"><i class="fa-solid fa-right-from-bracket"></i> Logout</a>
                                </div>
                            </div>
                        </div>
                        <br>
                        <br>

                        <table style="width:95%; margin-left: 30px; margin-top: 35px; ">
                            <tr>
                                <th colspan="2"><span style="margin-left:-460px;">Patient Name</span></th>
                                <th style="width:8%;">Bill</th>
                                <th style="width:12%;">Verify Payment</th>
                                <!-- <th style="width:9%;">Tests Form</th> -->
                                <th style="width:12%;">Refer To</th>
                                <th style="width:9%;">Confirm</th>
                            </tr>
                        </table>
                        <?php
                        // Display data
                        include_once '../includes/conn.php';

                        $sql_patient = "SELECT * FROM patient_records WHERE status = 'Cashier'";
                        $resultPatients = $conn->query($sql_patient);

                        $patient_id = "";
                        $patient_name = "";

                        if (mysqli_num_rows($resultPatients) > 0) {
                            while ($row = mysqli_fetch_assoc($resultPatients)) {
                                $patient_id = $row['patient_id'];
                                $patient_name = $row['name'];

                                echo '<div class="patient-block" >';
                                echo '<p class="name"> ' . $patient_name . '</p>';

                                echo '<form method="post" action="">';
                                echo '<input type="hidden" name="patient_id" value="' . $row['patient_id'] . '">';
                                echo '<input type="hidden" name="name" value="' . $row['name'] . '">';
                                echo '<button type="submit" class="view">Proceed</button>';
                                echo '<select name="patient_status" class="view">';
                                echo '<option value="Doctor room 1">Doctor</option>';
                                echo '<option value="Pharmacy">Pharmacy</option>';
                                echo '<option value="Finish">Finish</option>';
                                // echo '<option value="Xray & Lab">Xray & Lab</option>';
                                echo '</select>';
                                echo '</form>';
                                echo '<button class="view" onclick="openForm(' . $row["patient_id"] . ')">Verify Payment</button>';
                                echo '<button class="view" onclick="openVerify(' . $row["patient_id"] . ')">View Bill</button>';
                                echo '</div>';
                            }
                        } else {
                            echo '<h2 style="text-align:center;"> No patients waiting </h2>';
                        }
                        // set status
                        if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST["patient_id"]) && isset($_POST["patient_status"])) {
                            $patient_id = $_POST["patient_id"];
                            $status = $_POST["patient_status"];

                            // Update status for the selected patient ID
                            $update_sql = "UPDATE patient_records SET status = '$status' WHERE patient_id = $patient_id";
                            $update_result = mysqli_query($conn, $update_sql);

                            if ($update_result) {
                                $alert = "Patient sent to the pharmacy successfully";

                                echo "<script>alert('$alert'); window.location.href = 'payment.php';</script>";
                            } else {
                                echo "Error updating record: " . mysqli_error($conn);
                            }
                        }

                        ?>

                        <div class="form-popup" id="myForm" style="display:none; width: 600px;">
                            <form action="verify.php" method="POST" class="form-container">
                                <input type="hidden" name="patient_id" value="<?php echo $patient_id ?>">
                                <div class="airtellogo"> <img src="../Assets/airtel.png" alt="airtel" style="width: 20%; margin-left: 220px;"> </div>
                                <div class="payment_details" style="text-align: center;">
                                    <p id="pp">PAYMENT DETAILS</p>
                                    <!-- <p class="purchase_id">Patient Name : <span id="purchase_id"></span></p> -->
                                    <p id="payto">SEND TO : <span>+265(0) 993 817 112</span></p>
                                    <p id="pay_detail">Airtel Money Name : <span>Kelvin Sande</span></p>
                                </div>

                                <h1>Verify Payment</h1>

                                <label for=""><b>Phone Number</b></label>
                                <input type="text" placeholder="Enter phone number used to send money" name="phone_number" required>

                                <label for="psw"><b>Amount(MWK)</b></label>
                                <input type="text" placeholder="Enter amount" name="amount" required>


                                <label for="psw"><b>Trans ID</b></label>
                                <input type="text" placeholder="Enter transaction ID" name="trans_id" required>

                                <button type="submit" class="btn">Verify Payment</button>
                                <button type="button" class="btn cancel" onclick="closeForm()">Close</button>
                            </form>
                        </div>

                        <script>
                            // Function to open the form
                            function openForm() {
                                document.getElementById("myForm").style.display = "block";
                            }

                            // Function to close the form
                            function closeForm() {
                                document.getElementById("myForm").style.display = "none";
                            }

                            // Function to open the view
                            function openVerify(patientId) {
                                const url = `./bill.php?patient_id=${patientId}`;
                                const width = 800;
                                const height = 500;
                                const left = (window.innerWidth / 2) - (width / 2);
                                const top = (window.innerHeight / 2) - (height / 2);
                                const features = `width=${width},height=${height},left=${left},top=${top}`;
                                const newWindow = window.open(url, 'Edit Patient Record', features);
                                newWindow.focus();
                            }
                        </script>
