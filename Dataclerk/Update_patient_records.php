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
      margin-left: 280px;
    }

    .patient-form-row {
      margin: 0 0 20px 0;
    }

    /* .patient-form-row:nth-child(even) {
    margin-right: 
} */

    .patient-form-half {
      width: 50%;
    }

    input[type="text"],
    input[type="email"],
    input[type="number"],
    input[type="date"] {
      width: 280px;
      box-sizing: border-box;
      height: 30px;
      border-radius: 5px;
      border: 2px;
      border-style: solid;
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
      margin-top: 30px;
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

            <?php
            include_once '../includes/conn.php';

            if ($_SERVER["REQUEST_METHOD"] == "POST") {
              $patient_id = $_GET['patient_id'];
              $name = mysqli_real_escape_string($conn, $_POST['name']);
              $dob = mysqli_real_escape_string($conn, $_POST['dob']);
              $gender = mysqli_real_escape_string($conn, $_POST['gender']);
              $nationality = mysqli_real_escape_string($conn, $_POST['nationality']);
              $district = mysqli_real_escape_string($conn, $_POST['district']);
              $area = mysqli_real_escape_string($conn, $_POST['area']);
              $phone_number = mysqli_real_escape_string($conn, $_POST['phone_number']);
              $email = mysqli_real_escape_string($conn, $_POST['email']);
              $married = mysqli_real_escape_string($conn, $_POST['married']);
              $spouse_name = mysqli_real_escape_string($conn, $_POST['spouse_name']);
              $spouse_number = mysqli_real_escape_string($conn, $_POST['spouse_number']);
              $spouse_email = mysqli_real_escape_string($conn, $_POST['spouse_email']);
              $spouse_physical_address = mysqli_real_escape_string($conn, $_POST['spouse_physical_address']);
              $emergency_contact_name = mysqli_real_escape_string($conn, $_POST['emergency_contact_name']);
              $relationship_to_patient = mysqli_real_escape_string($conn, $_POST['relationship_to_patient']);
              $emergency_contact_phone_number = mysqli_real_escape_string($conn, $_POST['emergency_contact_phone_number']);

              $sql = "UPDATE patient_records SET name='$name', dob='$dob', gender='$gender', nationality='$nationality', district='$district', area='$area', phone_number='$phone_number', email='$email', married='$married', spouse_name='$spouse_name', spouse_number='$spouse_number', spouse_email='$spouse_email', spouse_physical_address='$spouse_physical_address', emergency_contact_name='$emergency_contact_name', relationship_to_patient='$relationship_to_patient', emergency_contact_phone_number='$emergency_contact_phone_number' WHERE patient_id=$patient_id";
              // Execute update 
              if (mysqli_query($conn, $sql)) {
                echo '<script>alert("Record updated successfully"); window.location.href="./patient_profile.php?patient_id=' . $patient_id . '";</script>';
                echo "Error updating record: " . mysqli_error($conn);
              }
            }

            // Fetch user data based on user_id
            if (isset($_GET['patient_id'])) {
              $patient_id = $_GET['patient_id'];
              $sql = "SELECT * FROM patient_records WHERE patient_id=$patient_id";
              $result = mysqli_query($conn, $sql);
              if ($result->num_rows > 0) {
                $row = $result->fetch_assoc();
                // Output form for editing user
                echo '<div class="background">';
                echo '<form action="" method="post" class="patient-form">';
                echo '<h2  class="patient-form-title">Patient Details</h2>';

                echo '<div class="patient-form-row patient-form-row--half">';
                echo '<div class="patient-form-half right-form">';
                echo '<div class="form-row">';
                echo '<label for="name" class="patient-form-label">Name:</label> <br>';
                echo '<input type="text" name="name" value="' . $row['name'] . '" required>';
                echo '</div>';

                echo '<div class="form-row">';
                echo '<label for="dob" class="patient-form-label">Date of Birth:</label>';
                echo '<input type="text" name="dob" value="' . $row['dob'] . '" required>';
                echo '</div>';

                echo '<div class="form-row">';
                echo '<label for="gender" class="patient-form-label">Gender:</label><br>';
                echo '<input type="text" name="gender" value="' . $row['gender'] . '" required>';
                echo '</div>';

                echo '<div class="form-row">';
                echo '<label for="nationality" class="patient-form-label">Nationality:</label> <br>';
                echo '<input type="text" name="nationality" value="' . $row['nationality'] . '" required>';
                echo '</div>';

                echo '<div class="form-row">';
                echo '<label for="nationality" class="patient-form-label">District:</label> <br>';
                echo '<input type="text" name="district" value="' . $row['district'] . '" required>';
                echo '</div>';

                echo '<div class="form-row">';
                echo '<label for="nationality"class="patient-form-label">Area of Residence:</label>';
                echo '<input type="text" name="area" value="' . $row['area'] . '" required>';
                echo '</div>';

                echo '<div class="form-row">';
                echo '<label for="nationality"class="patient-form-label">Phone Number:</label>';
                echo '<input type="text" name="phone_number" value="' . $row['phone_number'] . '" required>';
                echo '</div>';

                echo '<div class="form-row">';
                echo '<label for="nationality"class="patient-form-label">Email:</label><br>';
                echo '<input type="text" name="email" value="' . $row['email'] . '" required>';
                echo '</div>';

                echo '</div>';
                echo '</div>';

                echo '<div class="patient-form-half left-form">';

                echo '<div class="form-row">';
                echo '<label for="nationality"class="patient-form-label">Married:</label><br>';
                echo '<input type="text" name="married" value="' . $row['married'] . '" required>';
                echo '</div>';

                echo '<div class="form-row">';
                echo '<label for="nationality"class="patient-form-label">Spouse Name:</label><br>';
                echo '<input type="text" name="spouse_name" value="' . $row['spouse_name'] . '">';
                echo '</div>';

                echo '<div class="form-row">';
                echo '<label for="nationality"class="patient-form-label">Spouse Number:</label><br>';
                echo '<input type="text" name="spouse_number" value="' . $row['spouse_number'] . '" >';
                echo '</div>';

                echo '<div class="form-row">';
                echo '<label for="nationality"class="patient-form-label">Spouse Email:</label><br>';
                echo '<input type="text" name="spouse_email" value="' . $row['spouse_email'] . '" >';
                echo '</div>';

                echo '<div class="form-row">';
                echo '<label for="nationality" class="patient-form-label">Spouse Physical Address:</label><br>';
                echo '<input type="text" name="spouse_physical_address" value="' . $row['spouse_physical_address'] . '">';
                echo '</div>';

                echo '<div class="form-row">';
                echo '<label for="nationality" class="patient-form-label">Emergency Contact Name:</label><br>';
                echo '<input type="text" name="emergency_contact_name" value="' . $row['emergency_contact_name'] . '" required>';
                echo '</div>';

                echo '<div class="form-row">';
                echo '<label for="nationality" class="patient-form-label">Relationship to Patient:</label><br>';
                echo '<input type="text" name="relationship_to_patient" value="' . $row['relationship_to_patient'] . '" required>';
                echo '</div>';

                echo '<div class="form-row">';
                echo '<label for="nationality" class="patient-form-label">Emergency Contact Phone Number:</label><br>';
                echo '<input type="text" name="emergency_contact_phone_number" value="' . $row['emergency_contact_phone_number'] . '" required>';
                echo '</div>';
                echo '<input type="hidden" name="patient_id" value="' . $patient_id . '">';
                echo '<button type="submit" class="patient-form-button" style="margin-top:20px; width: 60%; margin-left: -225px; margin-bottom: 20px;">Update</button>';
                echo '</div>';
                echo '</div>';

                echo '</form>';
                echo '</div>';
              } else {
                echo "User not found";
              }
            }

            $conn->close();
            ?>
