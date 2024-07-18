<?php
include_once '../includes/conn.php';
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
            <a href="staff.php"><i class="fa fa-calendar-days"></i>Appointments</a>
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
                  <i class="fa fa-user"></i><?php echo $userName ?>
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
            <div class="clerk-tiles" style="margin-top: -30px;">
              <a href="doctors.php" style="height: 100px;">
                <p style="margin-top: -10px">Doctors</p>
                <p class="numbers"><i class="fa-solid fa-user-doctor" style="font-size: 35px;"></i></p>
                <!-- <i class="fa-solid fa-arrow-right" style="margin-top: -55px;"></i> -->
              </a>
              <a href="nurses.php" style="height: 100px;">
                <p style="margin-top: -10px">Nurses</p>
                <p class="numbers"><i class="fa-solid fa-user-nurse" style="font-size: 35px;"></i></p>
                <!-- <i class="fa-solid fa-arrow-right" style="margin-top: -55px;"></i> -->
              </a>

              <a href="dataclerks.php" style="height: 100px;">
                <p style="margin-top: -10px">Data Clerks</p>
                <p class="numbers"><i class="fa-solid fa-clipboard" style="font-size: 35px;"></i></p>
                <!-- <i class="fa-solid fa-arrow-right" style="margin-top: -55px;"></i> -->
              </a>

              <a href="labtech.php" style="height: 100px;">
                <p style="margin-top: -10px">Lab Techs</p>
                <p class="numbers"><i class="fa-solid fa-flask-vial" style="font-size: 35px;"></i></p>
                <!-- <i class="fa-solid fa-arrow-right" style="margin-top: -55px;"></i> -->
              </a>

              <a href="xraytech.php" style="height: 100px;">
                <p style="margin-top: -10px">Xray Techs</p>
                <p class="numbers"><i class="fa-solid fa-x-ray" style="font-size: 35px;"></i></p>
                <!-- <i class="fa-solid fa-arrow-right" style="margin-top: -55px;"></i> -->
              </a>

              <a href="emergency.php" style="height: 100px;">
                <p style="margin-top: -10px">Emergency</p>
                <p class="numbers"><i class="fa-solid fa-truck-medical" style="font-size: 35px;"></i></p>
                <!-- <i class="fa-solid fa-arrow-right" style="margin-top: -55px;"></i> -->
              </a>

              <a href="pharmacists.php" style="height: 100px;">
                <p style="margin-top: -10px">Pharmacists</p>
                <p class="numbers"><i class="fa-solid fa-pills" style="font-size: 35px;"></i></p>
                <!-- <i class="fa-solid fa-arrow-right" style="margin-top: -55px;"></i> -->
              </a>

              <a href="accounting.php" style="height: 100px;">
                <p style="margin-top: -10px">Accounting</p>
                <p class="numbers"><i class="fa-solid fa-file-invoice-dollar" style="font-size: 35px;"></i></p>
                <!-- <i class="fa-solid fa-arrow-right" style="margin-top: -55px;"></i> -->
              </a>
            </div>
