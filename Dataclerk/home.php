<?php
include_once '../includes/conn.php';
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
  <meta charset="UTF-8" />
  <meta http-equiv="X-UA-Compatible" content="IE=edge" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title>265 OutPatient</title>
  <link rel="stylesheet" type="text/css" href="../style/clerk.css" />
  <link rel="stylesheet" href="../css/all.css" />
  <script src="../java.js"></script>

  <style>
    clerk-tiles a {
      height: 30px;
    }

    .notification-tiles {
      display: inline-flex;
      justify-content: center;
      align-items: center;
      flex-wrap: wrap;
      padding: 20px;
      margin-left: px;
    }

    .notification-tiles a {
      width: 350px;
      height: 50px;
      background-color: rgba(0, 0, 0, 0.6);
      color: white;
      padding: 15px 30px;
      text-align: center;
      text-decoration: none;
      margin: 10px;
      font-size: 20px;
      border-radius: 5px;
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

    /* 
.notification:hover {
  background: red;
} */

    .notification .badge {
      position: absolute;
      top: -15px;
      right: -18px;
      padding: 5px 10px;
      border-radius: 50%;
      background: red;
      color: white;
    }
  </style>
</head>

<body>
  <div class="overlay">
    <!-- left bar  -->
    <section>
      <div class="left">
        <div class="logo">
          <img src="../Assets/cardiogram 2.png" alt="logo" />
          <p class="logo-name"><span>265</span>OPMS</p>
        </div>
        <br />
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
            <a href="../calendar"><i class="fa fa-calendar-days"></i>Appointments</a>
          </li>
          <!-- <li>
              <a href=""><i class="fa fa-money-bill"></i> Bills</a>
            </li>
            <li>
              <a href=""><i class="fa fa-bullhorn"></i> Support</a>
            </li> -->
        </ul>

        <br /><br />
        <div class="fill" style="margin-top: 40px;">
          <img src="../Assets/undraw_medicine_b1ol.png" />
        </div>
      </div>
      <br />
      <div class="right-div">
        <div id="main">
          <br />
          <div class="head">
            <div class="col-1">
              <p class="nav-title">Data Clerk</p>

              <div class="user-profile">
                <div onclick="myFunction()" class="user">
                  <i class="fa fa-user"></i> <?php echo $userName ?>
                </div>
                <div id="myDropdown" class="dropdown-content">
                  <a href="#"><i class="fa-regular fa-address-card"></i> Profile</a>
                  <a href="../logout.php"><i class="fa-solid fa-right-from-bracket"></i> Logout</a>
                </div>
              </div>
            </div>
            <br />
            <br />
            <div class="line"></div>

            <!-- search button -->
            <br />
            <br />
            <br />
            <br />
            <!-- tiles -->

            <div class="clerk-tiles" style="margin-top: -30px;">
              <a href="staff_home.php" style="height: 110px; width: 350px;">
                <p style="margin-top: -10px">Staff</p>
                <p class="numbers"><i class="fa-solid fa-user-doctor" style="font-size: 35px;"></i></p>
                <!-- <i class="fa-solid fa-arrow-right" style="margin-top: -55px;"></i> -->
              </a>

              <a href="patients.php" style="height: 110px; width: 350px;">
                <p style="margin-top: -10px">Patients</p>
                <p class="numbers"><i class="fa-solid fa-hospital-user" style="font-size: 35px;"></i></p>
                <!-- <i class="fa-solid fa-arrow-right" style="margin-top: -55px;"></i> -->
              </a>
            </div>
            <!-- <div class="notification-tiles" style="margin-top: -30px; margin-left: 80px;">
          <a href="#" class="notification" >
              <span style="color: white;"><i class="fa fa-user" style=" padding: 10px;"></i>Nurse 1 Queue</span><span class="badge">3</span>
          </a>
          <a href="#" class="notification" >
              <span style="color: white;"><i class="fa fa-user" style=" padding: 10px;"></i>Nurse 2 Queue</span><span class="badge">3</span>
          </a>
        </div>
         -->
