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
  <meta charset="UTF-8" />
  <meta http-equiv="X-UA-Compatible" content="IE=edge" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title>265 OutPatient</title>
  <link rel="stylesheet" type="text/css" href="../style/clerk.css" />
  <link rel="stylesheet" href="../css/all.css" />
  <script src="../java.js"></script>

  <style>
    .patient-form {
      display: flex;
      flex-wrap: wrap;
      /* justify-content: space-between; */
      width: 100%;
      margin-left: 140px;
      margin-right: 60px
    }

    .patient-form-title {
      width: 100%;
      margin-left: 200px;
    }

    .patient-form-row {
      margin: 0 0 20px 0;
    }

    .patient-form-row:nth-child(even) {
      margin-right: 0;
    }

    .patient-form-half {
      width: 50%;
    }

    input[type="text"],
    input[type="email"],
    input[type="number"],
    input[type="date"],
    select {
      width: 280px;
      box-sizing: border-box;
      height: 30px;
      border-radius: 5px;
      border: 2px;
      border-style: solid;
    }

    .group {
      display: inline-flex;
    }

    .patient-form-input {
      width: 100%;
      padding: 10px;
      margin: 5px 0;
      border: 1px solid #ccc;
      border-radius: 5px;
      font-size: 19px;
      line-height: 1.5;
    }

    .patient-form-label {
      font-size: 20px;
    }

    .patient-form-button {
      display: block;
      background-color: #6db2e7;
      color: white;
      border: none;
      border-radius: 5px;
      font-size: 16px;
      padding: 10px;
      cursor: pointer;
    }

    .background {
      background-color: #f0f8fd;
      margin-left: 40px;
      margin-right: 40px;
      margin-top: -70px;
      overflow: hidden;
    }

    .patient-form-button:hover {
      background-color: #efa632;
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
            <a href="../calendar/index.php"><i class="fa fa-calendar-days"></i>Appointments</a>
          </li> <!-- <li>
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
            <!-- <div class="clerk-tiles">
            
            <a href="doctors.php" style="height:90px">
                <p style="margin-top: -10px">Doctors</p> 
                <p class="numbers">5</p>
                <i class="fa-solid fa-arrow-right" style="margin-top:-3px"></i>
            </a>

            <a href="nurses.php" style="height:90px">
                <p style="margin-top: -10px">Nurses</p> 
                <p class="numbers">31</p>
                <i class="fa-solid fa-arrow-right" style="margin-top:-3px"></i>
            </a>

            <a href="total.php" style="height:90px">
                <p style="margin-top: -10px">Other</p> 
                <p class="numbers">36</p>
                <i class="fa-solid fa-arrow-right" style="margin-top:-3px"></i>
            </a>
        </div> -->


            <!-- form -->
            <div class="background">
              <form action="register_user.php" id="staff-form" method="POST" class="patient-form">
                <h2 class="patient-form-title">Staff Registration Form</h2>

                <div class="patient-form-row patient-form-row--half">
                  <div class="patient-form-half right-form">
                    <div class="form-row">
                      <label for="name" class="patient-form-label">Name:<span>*</span></label> <br>
                      <input type="text" name="name" required>
                    </div>
                    <div class="form-row">
                      <label for="dept" class="patient-form-label">Role:<span>*</span></label> <br>
                      <select name="dept" id="dept" required>
                        <option value="Laboratory">Lab Technician</option>
                        <option value="Data clerk">Data Clerk</option>
                        <option value="Doctor">Doctor</option>
                        <option value="Nurse">Nurse</option>
                        <option value="Xray">Xray Technician</option>
                        <option value="Emergency_dept">Emergency Officer</option>
                        <option value="Account">Account</option>

                      </select>
                    </div>

                    <div class="form-row">
                      <label for="id_number" class="patient-form-label">ID Number:<span>*</span></label> <br>
                      <input type="text" name="id_number" id="id_number" required>
                    </div>

                    <div class="form-row">
                      <label for="phone_number" class="patient-form-label">Phone Number:<span>*</span></label>
                      <input type="text" name="phone_number" id="phone_number" required>
                    </div>
                  </div>
                </div>

                <div class="patient-form-half left-form">
                  <div class="form-row">
                    <label for="email" class="patient-form-label">Email:<span>*</span></label><br>
                    <input type="text" name="email" id="email" required>
                  </div>

                  <div class="form-row">
                    <label for="position" class="patient-form-label">Position:<span>*</span></label><br>
                    <input type="text" name="position" id="position" required>
                  </div>

                  <div class="form-row">
                    <label for="gender" class="patient-form-label">Gender:<span>*</span></label><br>
                    <div class="group">
                      <input type="radio" id="male" name="gender" value="male" required>
                      <label for="male">Male</label><br>
                    </div>
                    <div class="group">
                      <input type="radio" id="female" name="gender" value="female" required>
                      <label for="female">Female</label><br>
                    </div>
                  </div>

                  <button type="submit" class="patient-form-button" style="margin-top: 75px; width: 60%; margin-left: -225px; margin-bottom: 20px;">Submit</button>
                </div>
              </form>
            </div>

            <script>
              document.getElementById("staff-form").addEventListener("submit", function(event) {
                // check if the name field is empty
                var patient_name = document.getElementById("name").value;
                if (/^[a-zA-Z\s]*$/.test(name)) {
                  // do nothing
                } else {
                  alert("Name should only contain letters and spaces.");
                  event.preventDefault();
                  return;
                }

                // check if the nationality field only allows text inputs without underscores
                if (!/^[a-zA-Z\s]*$/.test(document.getElementById("position").value)) {
                  alert("Position field should only contain letters and spaces.");
                  event.preventDefault();
                  return;
                }


                // check if the phone number field only allows text inputs without underscores
                if (!/^(08|09)[0-9]{8}$/.test(document.getElementById("phone_number").value)) {
                  alert("Phone number field should start with either 09 or 08 and should be 10 digits.");
                  event.preventDefault();
                  return;
                }
                if (!/^[\w-]+(\.[\w-]+)*@([\w-]+\.)+[a-zA-Z]{2,7}$/.test(document.getElementById("email").value)) {
                  alert("Please enter a valid email address.");
                  event.preventDefault();
                  return;
                }
              });
            </script>
