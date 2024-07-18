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
          <li><a href="emergency.php"><i class="fa fa-th-large"></i> Home</a></li>
          <!-- <li><a href="patients.html"><i class="fa fa-user"></i> Patients</a></li> -->
          <li><a href="drivers.php"><i class="fa-solid fa-car"></i>Drivers</a></li>
          <li><a href=""><i class="fa-solid fa-phone"></i>Dispatched Emergencies</a></li>
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
              <p class="nav-title">Drivers</p>

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

            <table style="width:95%; margin-left: 30px; margin-top: 40px; ">
              <tr>
                <th colspan="2"><span style="margin-left:-470px;">Driver Name</span></th>
                <th style="width:15%;">Phone Numbers</th>
                <th style="width:14%;">Status</th>
                <th style="width:12%;">Actions</th>
              </tr>
            </table>
            <button class="open-button"><i class="fa-solid fa-plus" onclick="openForm()"></i></button>
            <div class="form-popup" id="myForm">
              <form action="" method="POST" class="form-container" onsubmit="return validateForm()">
                <h1>Add Driver</h1>

                <label for="driver_name"><b>Name</b></label>
                <input type="text" placeholder="Enter name of a driver" name="driver_name" id="driver_name" required>

                <label for="phone_number"><b>Phone Number</b></label>
                <input type="text" placeholder="Enter driver's phone number" name="phone_number" id="phone_number" required>

                <button type="submit" class="btn">Add driver</button>
                <button type="button" class="btn cancel" onclick="closeForm()">Close</button>
              </form>
            </div>

            <script>
              function validateForm() {
                var driverNameInput = document.getElementById('driver_name');
                var phoneNumberInput = document.getElementById('phone_number');
                var driverName = driverNameInput.value.trim();
                var phoneNumber = phoneNumberInput.value.trim();

                // Validate driver name (text only)
                var namePattern = /^[a-zA-Z\s]+$/; // Only allows alphabets and spaces
                if (!namePattern.test(driverName)) {
                  alert('Please enter a valid driver name (text only).');
                  driverNameInput.focus();
                  return false;
                }

                // Validate phone number (10 digits)
                var phonePattern = /^\d{10}$/; // Exactly 10 digits
                if (!phonePattern.test(phoneNumber)) {
                  alert('Please enter a valid 10-digit phone number.');
                  phoneNumberInput.focus();
                  return false;
                }

                // Form is valid
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

            // Check if the form is submitted
            if (isset($_POST['driver_name'], $_POST['phone_number'])) {
              // Both driver_name and phone_number are set in the form submission
              $driver_name = trim($_POST['driver_name']);
              $phone_number = trim($_POST['phone_number']);
              // Check if both name and phone number are provided
              if (!empty($driver_name) && !empty($phone_number)) {

                // Prepare SQL statement to insert data into drivers table
                $sql = "INSERT INTO drivers (driver_name, phone_number) VALUES (?, ?)";
                $stmt = $conn->prepare($sql);

                // Bind parameters and execute the statement
                $stmt->bind_param("ss", $driver_name, $phone_number);
                if ($stmt->execute()) {
                  echo "<script>alert('Driver added successfully');</script>";
                } else {
                  echo "Error: " . $sql . "<br>" . $conn->error;
                }

                // Close statement and connection

              } else {
                echo "Both name and phone number are required.";
              }
            }

            $limit = 8; // Number of records per page
            $page = isset($_GET['page']) ? $_GET['page'] : 1; // Current page number, default is 1
            $start = ($page - 1) * $limit; // Offset for SQL query

            $sql = "SELECT * FROM drivers LIMIT $start, $limit";
            $result = mysqli_query($conn, $sql);

            if (mysqli_num_rows($result) > 0) {
              while ($row = mysqli_fetch_assoc($result)) {
                echo '<div class="patient-block">';
                echo '<p class="name">' . $row['driver_name'] . '</p>';
                echo '<form method="post" action="" id="status_form_' . $row['driver_id'] . '">';
                echo '<input type="hidden" name="driver_id" value="' . $row['driver_id'] . '">';
                echo '<select name="status" class="view" onchange="submitForm(' . $row['driver_id'] . ')">';
                echo '<option value="Active" ' . ($row['status'] == 'Active' ? 'selected' : '') . '>Deactivate</option>';
                echo '<option value="Inactive" ' . ($row['status'] == 'Inactive' ? 'selected' : '') . '>Activate</option>';
                echo '</select>';
                echo '</form>';
                echo '<button class="view" style="background-color: #6db2e7; width: 130px; font-size: 15px;">' . $row['status'] . '</button>';
                echo '<button class="view" style="background-color: #6db2e7; font-size: 15px; justify-content:center; width: 130px;">' . $row['phone_number'] . '</button>';
                echo '</div>';
              }
            } else {
              echo 'No rooms created yet';
            }
            if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['driver_id'], $_POST['status'])) {
              // Check if the form for updating status was submitted
              $driver_id = $conn->real_escape_string($_POST['driver_id']);
              $new_status = $conn->real_escape_string($_POST['status']);

              // Update the status in the database
              $update_query = "UPDATE drivers SET status='$new_status' WHERE driver_id='$driver_id'";
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
