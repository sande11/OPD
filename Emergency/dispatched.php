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
  <meta charset="UTF-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>265 OutPatient</title>
  <!-- <link rel="stylesheet" type="text/css" href="../style/style.css"> -->
  <link rel="stylesheet" type="text/css" href="../style/nurse.css">
  <link rel="stylesheet" href="../css/all.css">
  <script src="../java.js"></script>

  <style>
    form .view {
      display: inline-flexbox;
      float: right;
      padding: 5px;
      border-radius: 5px;
      border: 1px;
      font-size: 15px;
      background-color: #f0a732;
      margin-left: -50px;
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

    table {
      font-family: arial, sans-serif;
      border-collapse: collapse;
      width: 98%;
      margin-left: 10px;
      overflow-x: auto;
    }

    td,
    th {
      border: 1px solid #dddddd;
      text-align: left;
      padding: 8px;
    }

    tr:nth-child(even) {
      background-color: #dddddd;
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
          <li><a href="dispatched.php"><i class="fa-solid fa-phone"></i>Dispatched Emergencies</a></li>
        </ul>
        <br><br>
        <div class="fill">
          <img src="../Assets/undraw_medicine_b1ol.png">
        </div>
      </div>
      <br>
      <div class="right-div">
        <div id="main">
          <br>
          <div class="head">
            <div class="col-1">
              <p class="nav-title">Emergency Dept.</p>

              <div class="user-profile">
                <div onclick="myFunction()" class="user">
                  <i class="fa fa-user"></i> <?php echo $userName ?>
                </div>
                <div id="myDropdown" class="dropdown-content">
                  <a href="#"><i class="fa-regular fa-address-card"></i> Profile</a>
                  <a href="../logout.php"><i class="fa-solid fa-right-from-bracket"></i> Logout</a>
                </div>

              </div>
              <br>
              <br>

              <div class="notification-tile" style="background-color:#f0f8fd; margin-top: 45px;">
                <h2 style="text-align: center;">Notifications</h2>

                <?php
                // function for sending SMSs
                function sendPasswordSMS($phone, $message)
                {

                  $curl = curl_init();

                  curl_setopt_array($curl, array(
                    CURLOPT_URL => 'https://telcomw.com/api-v2/send',
                    CURLOPT_RETURNTRANSFER => true,
                    CURLOPT_ENCODING => '',
                    CURLOPT_MAXREDIRS => 10,
                    CURLOPT_TIMEOUT => 0,
                    CURLOPT_FOLLOWLOCATION => true,
                    CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
                    CURLOPT_CUSTOMREQUEST => 'POST',
                    CURLOPT_POSTFIELDS => array(
                      'api_key' => 'b1Xvj5CWS78RWDP3HZBl ', 'password' => 'God1stGod', 'text' => $message,
                      'numbers' => $phone, 'from' => 'WGIT'
                    ),
                  ));

                  $response = curl_exec($curl);
                  curl_close($curl);
                }


                include_once '../includes/conn.php'; // Include your database connection file

                $sql = "SELECT * FROM emergencies WHERE driver_name != 'not_dispatched' ORDER BY emergency_id ASC";

                $result = mysqli_query($conn, $sql);

                // Check if there are any rows returned
                if (mysqli_num_rows($result) > 0) {
                  // Start creating the HTML table
                  echo '<table class="styled-table" id="emergencies_table">';
                  echo '<thead>';
                  echo '<tr>';
                  echo '<th>Emergency Type</th>';
                  echo '<th>Emergency Description</th>';
                  echo '<th>Location</th>';
                  echo '<th>Dispatched</th>';
                  echo '</tr>';
                  echo '</thead>';
                  echo '<tbody>';

                  // Loop through each row of data
                  while ($row = mysqli_fetch_assoc($result)) {
                    echo '<tr>';
                    echo '<td>' . $row['emergency_type'] . '</td>';
                    echo '<td>' . $row['emergency_description'] . '</td>';
                    echo '<td>' . $row['latitude'] . ', ' . $row['longitude'] . '</td>';

                    // Form for dispatching a driver
                    echo '<td>' . $row['driver_name'] . '</td>';

                    echo '</tr>';
                  }

                  echo '</tbody>';
                  echo '</table>';
                } else {
                  echo '<h2 style="text-align: center;">No emergencies yet</h2>'; // Display a message if no data is returned
                }
