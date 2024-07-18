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
      align-items: center;
      font-size: 40px;
      margin-left: 35px;
      margin-top: -45px;
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
              <p class="nav-title">Dataclerk</p>

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
            <!-- search button -->
            <br>
            <br>
            <br>
            <div class="back">
              <a href="home.php"><i class="fa-solid fa-chevron-left"></i></a>
            </div>

            <div class="search-div">
              <form method="GET" action="">
                <div class="search">
                  <input type="text" class="searchTerm" name="search_query" placeholder="Enter Patient Name">
                  <button type="submit" class="searchButton">
                    <i class="fa fa-search"></i>
                  </button>
                </div>
              </form>
            </div>


            <?php
            include_once '../includes/conn.php';

            $limit = 8; // Number of records per page
            $page = isset($_GET['page']) ? $_GET['page'] : 1; // Current page number, default is 1
            $start = ($page - 1) * $limit; // Offset for SQL query

            if (isset($_GET['search_query'])) {
              $search_query = $_GET['search_query'];

              // Construct the SQL query to search for the patient
              $sql = "SELECT * FROM patient_records WHERE name LIKE '%$search_query%' OR id_number LIKE '%$search_query%'";
            } else {
              // Default query when no search query is present
              $sql = "SELECT patient_id, name FROM patient_records LIMIT $start, $limit";
            }
            $result = mysqli_query($conn, $sql);

            // $sql_patient = "SELECT patient_id, name FROM patient_records WHERE status = 'Doctor'";
            $result = mysqli_query($conn, $sql);
            if (mysqli_num_rows($result) > 0) {
              while ($row = mysqli_fetch_assoc($result)) {
                $patient_id = $row['patient_id'];
                $patient_name = $row['name'];
                $patient_ids[] = $patient_id;

                echo '<div class="patient-block" >';
                echo '<p class="name"> ' . $patient_name . '</p>';

                // echo '<button class="view" onclick="openTest()">Order Test</button>';
                echo '<button class="view"><a href="./patient_profile.php?patient_id=' . $row["patient_id"] . '">View</a></button>';
                echo '<button class="view"><a href="./Update_patient_records.php?patient_id=' . $row["patient_id"] . '">Edit</a></button>';
                echo '</div>';
              }
            } else {
              echo "<h2 style='text-align: center;'>No patients registered yet.</h2>";
            }

            $sqlTotal = "SELECT COUNT(*) as total FROM patient_records";
            $resultTotal = mysqli_query($conn, $sqlTotal);
            $rowTotal = mysqli_fetch_assoc($resultTotal);
            $totalPages = ceil($rowTotal['total'] / $limit);

            // echo '<div style="margin-left: 30px; margin-top: 10px;">';
            echo '<div class="pagination">';

            for ($i = 1; $i <= $totalPages; $i++) {
              echo '<a href="?page=' . $i . '"> ' . $i . '</a>';
            }
            echo '</div>';
            ?>
