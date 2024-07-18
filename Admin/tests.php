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
            border: 2px solid #6db2e7;
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
            bottom: 0;
            right: 15px;
            border: 3px solid #f1f1f1;
            z-index: 9;
        }

        /* Add styles to the form container */
        .form-container {
            max-width: 300px;
            padding: 10px;
            background-color: white;
        }

        /* Full-width input fields */
        .form-container input[type=text],
        .form-container input[type=password] {
            width: 100%;
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
                    <li><a href="hospital.php"><i class="fa-solid fa-chevron-left"></i>Manage Hospital</a></li>
                </ul>
                <br><br>
                <div class="fill" style="margin-top: 190px;">
                    <img src="../Assets/undraw_medicine_b1ol.png">
                </div>
            </div>
            <br>
            <div class="right-div">
                <div id="main">
                    <br>
                    <div class="head">
                        <div class="col-1">
                            <p class="nav-title">Manage Tests</p>

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
                        <button class="open-button"><i class="fa-solid fa-plus" onclick="openForm()"></i></button>

                        <table style="width:95%; margin-left: 30px; margin-top: 40px; ">
                            <tr>
                                <th colspan="2"><span style="margin-left:-470px;">Test Name</span></th>
                                <th style="width:15%;">Price</th>
                                <th style="width:14%;">Test Status</th>
                                <th style="width:12%;">Actions</th>
                            </tr>
                        </table>

                        <div class="form-popup" id="myForm">
                            <form action="" method="POST" class="form-container" onsubmit="return validateForm()">
                                <h1>Add a test</h1>

                                <label for="test_name"><b>Test Name</b></label>
                                <input type="text" placeholder="Enter test name" name="test_name" id="test_name" required pattern="[A-Za-z\s]+" title="Please enter only letters and spaces">

                                <label for="test_price"><b>Test Price</b></label>
                                <input type="text" placeholder="Enter price e.g 12,000" name="test_price" id="test_price" required pattern="^[A-Za-z0-9\s,.]+$" title="Please enter valid price format">

                                <button type="submit" class="btn">Add test</button>
                                <button type="button" class="btn cancel" onclick="closeForm()">Close</button>
                            </form>
                        </div>

                        <script>
                            function validateForm() {
                                var testName = document.getElementById("test_name").value;
                                var testPrice = document.getElementById("test_price").value;

                                var testNameRegex = /^[A-Za-z\s]+$/;
                                var testPriceRegex = /^[A-Za-z0-9\s,.]+$/;

                                if (!testNameRegex.test(testName)) {
                                    alert("Please enter only letters and spaces for test name.");
                                    return false;
                                }

                                if (!testPriceRegex.test(testPrice)) {
                                    alert("Please enter valid price format.");
                                    return false;
                                }

                                return true;
                            }

                            function openForm() {
                                document.getElementById("myForm").style.display = "block";
                            }

                            function closeForm() {
                                document.getElementById("myForm").style.display = "none";
                            }
                        </script>

                        <?php
                        include_once '../includes/conn.php';

                        if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['test_name'], $_POST['test_price'])) {
                            // Check if RoomName and Use_for are set in the POST data
                            $test_name = $conn->real_escape_string($_POST['test_name']);
                            $test_price = $conn->real_escape_string($_POST['test_price']);

                            // Insert data into rooms table
                            $sql = "INSERT INTO tests (test_name, test_price) VALUES ('$test_name', '$test_price')";

                            if ($conn->query($sql) === TRUE) {
                                echo '<script>alert("New test successfully added.");</script>';
                            } else {
                                echo "Error adding test: " . $conn->error;
                            }
                        }

                        $limit = 8; // Number of records per page
                        $page = isset($_GET['page']) ? $_GET['page'] : 1; // Current page number, default is 1
                        $start = ($page - 1) * $limit; // Offset for SQL query

                        $sql = "SELECT * FROM tests LIMIT $start, $limit";
                        $result = mysqli_query($conn, $sql);

                        if (mysqli_num_rows($result) > 0) {
                            while ($row = mysqli_fetch_assoc($result)) {
                                echo '<div class="patient-block">';
                                echo '<p class="name">' . $row['test_name'] . '</p>';
                                echo '<form method="post" action="" id="status_form_' . $row['test_id'] . '">';
                                echo '<input type="hidden" name="test_id" value="' . $row['test_id'] . '">';
                                echo '<select name="test_status" class="view" onchange="submitForm(' . $row['test_id'] . ')">';
                                echo '<option value="Active" ' . ($row['test_status'] == 'Active' ? 'selected' : '') . '>Deactivate</option>';
                                echo '<option value="Inactive" ' . ($row['test_status'] == 'Inactive' ? 'selected' : '') . '>Activate</option>';
                                echo '</select>';
                                echo '</form>';
                                echo '<button class="view" style="background-color: #6db2e7; width: 130px; font-size: 15px;">' . $row['test_status'] . '</button>';
                                echo '<button class="view" style="background-color: #6db2e7; font-size: 15px; justify-content:center; width: 130px;">' . $row['test_price'] . '</button>';
                                echo '</div>';
                            }
                        } else {
                            echo '<h2 style="text-align:center;">No tests created yet</h2>';
                        }

                        if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['test_id'], $_POST['test_status'])) {
                            // Check if the form for updating status was submitted
                            $user_id = $conn->real_escape_string($_POST['test_id']);
                            $new_status = $conn->real_escape_string($_POST['test_status']);

                            // Update the status in the database
                            $update_query = "UPDATE tests SET test_status='$new_status' WHERE test_id='$user_id'";
                            $update_result = mysqli_query($conn, $update_query);

                            if ($update_result) {
                                // Display success message using JavaScript alert
                                echo '<script>alert("Status updated successfully.");</script>';
                                // Reload the page
                                echo '<script>window.location.href = window.location.href;</script>';
                                exit(); // Stop further execution to prevent duplicate alerts
                            } else {
                                echo "Error updating status: " . mysqli_error($conn);
                            }
                        }
                        ?>
                        <script>
                            function submitForm(roomId) {
                                document.getElementById('status_form_' + roomId).submit();
                            }
                        </script>
