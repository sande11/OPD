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
  <meta charset="UTF-8" />
  <meta http-equiv="X-UA-Compatible" content="IE=edge" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title>265 OutPatient</title>
  <link rel="stylesheet" type="text/css" href="../style/doctorsoffice.css">
  <link rel="stylesheet" href="../css/all.css">
  <script src="../java.js"></script>
  <style>
    .container {
      max-width: 700px;
      margin: 50px auto;
      padding: 20px;
      display: flex;
      justify-content: space-between;
      border-width: 2px;
      border-style: solid;
      border-radius: 10px;
      border-color: black;
      background-color: #f0f8fd;
    }

    .profile-picture {
      width: 200px;
      height: 200px;
      border-radius: 50%;
      object-fit: cover;
      margin-bottom: 20px;
    }

    .profile-info {
      display: flex;
      flex-direction: column;
      justify-content: center;
      align-items: center;
      margin-bottom: 20px;
      color: black;
      font-size: 20px;
    }

    .profile-info .name {
      font-size: 34px;
      font-weight: bold;
      margin-bottom: 10px;
    }

    .profile-info .email {
      font-size: 16px;
      /* color: ; */
    }

    .profile-info .edit-profile {
      background-color: #f0a732;
      padding: 10px 20px;
      border-radius: 5px;
      cursor: pointer;
      margin: 10px;
    }

    .profile-info .edit-profile:hover {
      background-color: #6db2e7;
    }

    .profile-details {
      flex: 1;

    }

    span {
      font-size: 25px;
      color: #007acc;
    }

    .back {
      margin-top: 20px;
      display: flex;
      align-items: center;
    }

    .back a {
      text-decoration: none;
      color: #333;
      font-size: 18px;
      margin-left: 10px;
    }

    .back i {
      font-size: 24px;
      color: #333;
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
            <a href="dataclerk.php"><i class="fa-solid fa-hospital-user"></i>Register Patients</a>
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
        </ul>

        <br /><br />
        <div class="fill" style="margin-top: -1px;">
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
            <div class="back">
              <a href="doctors.php"><i class="fa-solid fa-chevron-left"></i></a>
            </div>
            <?php
            include_once '../includes/conn.php';

            if (isset($_GET['user_id'])) {
              $user_id = $_GET['user_id'];
              $sql = "SELECT * FROM users WHERE user_id=$user_id";
              $result = mysqli_query($conn, $sql);
              $user = mysqli_fetch_assoc($result);
            }
            ?>

            <!-- user profile -->
            <div class="container">
              <div class="profile-picture-container">
                <img class="profile-picture" src="../Assets/user.png" alt="Profile Picture" style="margin-top:50px;">
                <div class="profile-info" style="margin-top:-230px; margin-left: 300px; font-size: 35px;">
                  <div class="name"><?php echo isset($user['name']) ? $user['name'] : 'N/A'; ?></div>
                  <div>ID Number:<?php echo isset($user['id_number']) ? $user['id_number'] : 'N/A'; ?></div>
                  <div><?php echo isset($user['email']) ? $user['email'] : 'N/A'; ?></div>
                  <div><?php echo isset($user['phone_number']) ? $user['phone_number'] : 'N/A'; ?></div>
                  <div><?php echo isset($user['gender']) ? $user['gender'] : 'N/A'; ?></div>
                  <div><?php echo isset($user['department']) ? $user['department'] : 'N/A'; ?></div>
                  <?php if (isset($patient['user_id'])) : ?>
                    <a href="update_staff_records.php?patient_id=<?php echo $user['user_id']; ?>" class="edit-profile">Edit Profile</a>
                  <?php endif; ?>
                </div>
              </div>
