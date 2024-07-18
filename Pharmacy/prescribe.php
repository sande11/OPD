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
  <script src="java.js"></script>
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
      font-size: 25px;
      font-style: bold;
    }

    .form-row p {
      font-size: 18px;
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
      margin-top: 2px;
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
          <li><a href="pharmacy dashboard.php"><i class="fa fa-th-large"></i> Home</a></li>
          <li><a href="drugs.php"><i class="fa fa-pills"></i> Drugs</a></li>
          <li><a href="calendar/index.php"><i class="fa-solid fa-calendar-days"></i> Schedule</a></li>
        </ul>

        <br /><br />
        <div class="fill" style="margin-top: 140px;">
          <img src="../Assets/undraw_medicine_b1ol.png" />
        </div>
      </div>
      <br />
      <div class="right-div">
        <div id="main">
          <br />
          <div class="head">
            <div class="col-1">
              <p class="nav-title">Prescribe Medication</p>

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


            // Fetch user data based on user_id
            if (isset($_GET['patient_id'])) {
              $patient_id = $_GET['patient_id'];

              $sql = "SELECT *
            FROM patient_records
            JOIN diagnosis ON patient_records.patient_id = diagnosis.patient_id
            JOIN vital_records ON patient_records.patient_id = vital_records.patient_id
            WHERE patient_records.patient_id = $patient_id AND DATE(diagnosis.diagnosis_date) = DATE(NOW())";

              $result = mysqli_query($conn, $sql);
              if ($result->num_rows > 0) {
                $row = $result->fetch_assoc();
                // Output form for editing user
                echo '<div class="background">';
                echo '<form action="" method="post" class="patient-form">';
                echo '<h2  class="patient-form-title">Prescription Details</h2>';

                echo '<div class="patient-form-row patient-form-row--half" style="width: 300px;">';
                echo '<div class="patient-form-half right-form">';
                echo '<div class="form-row">';
                echo '<label for="name" class="patient-form-label">Name:</label> <br>';
                echo '<p>' . $row['name'] . '</p>';
                echo '</div>';

                $birthdate = new DateTime($row['dob']);
                $now = new DateTime();
                $age = $now->diff($birthdate);

                echo '<div class="form-row">';
                echo '<label for="name" class="patient-form-label">Age:</label> <br>';
                echo '<p>' . $age->y . ' years</p>';
                echo '</div>';

                echo '<div class="form-row">';
                echo '<label for="name" class="patient-form-label">Diagnosis:</label> <br>';
                echo '<p>' . $row['diagnosis'] . '</p>';
                echo '</div>';

                echo '<div class="form-row">';
                echo '<label for="nationality" class="patient-form-label">Weight:</label> <br>';
                echo '<p>' . $row['weight'] . '</p>';
                echo '</div>';
                echo '</div>';
                echo '</div>';

                echo '<div class="patient-form-half left-form">';
                echo '<div class="form-row">';
                $prescriptions = str_getcsv($row['prescription']);
                $quantities = str_getcsv($row['quantity']);

                if (count($prescriptions) === 1) {
                  $quantity = trim($quantities[0]);
                  $quantity = str_replace(['[', ']', '"'], '', $quantity);
                  $quantity = trim($quantity);

                  // Process single prescription and quantity
                  $prescription = trim($prescriptions[0]);
                  $prescription = str_replace(['[', ']', '"'], '', $prescription);
                  echo '<label for="prescription_' . $prescription . '">' . $prescription . ' (' . $quantity . ')</label><br>';
                  echo '<input type="text" name="dosage[]" id="prescription_' . $prescription . '"><br>';
                } else {
                  // Iterate over prescriptions and quantities
                  foreach (array_combine($prescriptions, $quantities) as $prescription => $quantity) {
                    $prescription = trim($prescription);
                    $quantity = trim(str_replace(['[', ']', '"'], '', $quantity));
                    $quantity = "(" . $quantity . ")";

                    $prescription = str_replace(['[', ']', '"'], '', $prescription);
                    echo '<label for="prescription_' . $prescription . '">' . $prescription . ' ' . $quantity . '</label><br>';
                    echo '<input type="text" name="dosage[]" id="prescription_' . $prescription . '"><br>';
                  }
                }
                echo '</div>';
                echo '<button type="submit" class="patient-form-button" style="margin-top:270px; width: 60%; margin-left: -120px; margin-bottom: 20px;">Submit</button>';
                echo '</form>';
                echo '</div>';
              } else {
                echo "No prescription found";
              }
            }

            if ($_SERVER["REQUEST_METHOD"] == "POST") {
              $patient_id = $_GET['patient_id'];
              $dosage = $_POST['dosage'];

              $dosages_csv = implode(';', $dosage);

              $sql = "UPDATE diagnosis SET dosage='$dosages_csv' WHERE patient_id=$patient_id AND diagnosis_date = DATE(NOW())";

              if (mysqli_query($conn, $sql)) {
                echo '<script>alert("Prescriptions updated successfully"); window.location.href="./pharmacy dashboard.php";</script>';
              } else {
                echo "Error updating record: " . mysqli_error($conn);
              }
            }

            $conn->close();
            ?>
