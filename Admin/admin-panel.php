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
    <link rel="stylesheet" type="text/css" href="../style/style.css">
    <link rel="stylesheet" href="../css/all.css">
    <script src="../java.js"></script>
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
                    <li><a href=""><i class="fa fa-th-large"></i> Home</a></li>
                    <li><a href="hospital.php"><i class="fa-regular fa-hospital"></i> Manage Hospital</a></li>
                    <li><a href="staff.php"><i class="fa-solid fa-user-doctor"></i> Medical Staff</a></li>
                    <li><a href="reports.php"><i class="fa-solid fa-chart-simple"></i> Reports</a></li>
                    <li><a href="mailto:kelvinsande9@gmail.com"><i class="fa fa-bullhorn"></i> Support</a></li>
                </ul>

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
                            <p class="nav-title">Admin Dashboard</p>

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
                        <div class="line"></div>
                        <div class="services">
                            <a href="dataclerk.php">
                                <p>Data Clerk</p>
                                <img src="../Assets/register.png">
                                <p>View Registry <i class="fa-solid fa-arrow-right"></i></p>
                            </a>

                            <a href="nurse.php">
                                <p>Nurse's Station</p>
                                <img src="../Assets/consultation.png">
                                <p>View Nurse's Station <i class="fa-solid fa-arrow-right"></i></p>
                            </a>

                            <a href="doctor.php">
                                <p>Doctor's Office</p>
                                <img src="../Assets/consultation.png">
                                <p>View Doctor's Office <i class="fa-solid fa-arrow-right"></i></p>
                            </a>
                        </div>

                        <div class="services">

                            <a href="labxray.php">
                                <p>Lab & X-Ray</p>
                                <img src="../Assets/lab.png">
                                <p>View Tests <i class="fa-solid fa-arrow-right"></i></p>
                            </a>
                            <a href="emergency.php">
                                <p>Emergency</p>
                                <img src="../Assets/register.png">
                                <p>View Emergency Dept.<i class="fa-solid fa-arrow-right"></i></p>
                            </a>

                            <a href="pharmacy.php">
                                <p>Pharmacy</p>
                                <img src="../Assets/pharmacy.png">
                                <p>View Pharmacy <i class="fa-solid fa-arrow-right"></i></p>
                            </a>

                        </div>
                    </div>
                </div>
            </div>
    </div>
    </div>
    </section>
    </div>
</body>

</html>
