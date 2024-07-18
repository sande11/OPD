<?php
session_start();
header("Cache-Control: no-store, no-cache, must-revalidate, post-check=0, pre-check=0");
header("Pragma: no-cache");


if (isset($_SESSION['name'])) {
    $userName = $_SESSION['name'];
    $departmentName = $_SESSION['department'];
} else {
    header("Location: Auth.php");
    exit();
}
include_once '../includes/conn.php';

$sqlNurseCount = "SELECT COUNT(*) AS nurse_count FROM patient_records WHERE status = 'Nurse'";
$resultNurse = $conn->query($sqlNurseCount);

$sqlDoctorCount = "SELECT COUNT(*) AS doctor_count FROM patient_records WHERE status = 'Doctor'";
$resultDoctor = $conn->query($sqlDoctorCount);

$nurseCount = 0; // Initialize nurse count variable
$docCount = 0; // Initialize doctor count variable

if ($resultNurse && $resultNurse->num_rows > 0) {
    $rowNurse = $resultNurse->fetch_assoc();
    $nurseCount = $rowNurse["nurse_count"];
}

if ($resultDoctor && $resultDoctor->num_rows > 0) {
    $rowDoctor = $resultDoctor->fetch_assoc();
    $docCount = $rowDoctor["doctor_count"];
}
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
    <style>
        .notification {
            background-color: #f0f8fd;
            color: white;
            text-decoration: none;
            padding: 15px 0px;
            position: relative;
            display: inline-block;
            border-radius: 2px;
        }

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

<body style="width: auto; height:auto;">
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
                    <!-- <li><a href="doctorsoffice.php"><i class="fa-solid fa-user-doctor"></i> Doctor's Office</a></li>
			<li><a href="nurse.php"><i class="fa-solid fa-user-nurse"></i> Nurse's Station</a></li> -->
                    <!-- <li><a href=""><i class="fa fa-bullhorn"></i> Support</a></li> -->
                    <!-- <hr style="margin-left: -40px; margin-top: 40px;"> -->
                    <li><a href="#" class="notification"><span><i class="fa-solid fa-user-nurse"></i>Nurse's Queue</span><span class="badge"><?php echo $nurseCount; ?></span></a></li>
                    <?php
                    $room = "SELECT * FROM rooms WHERE Status = 'Active'";
                    $resultRooms = $conn->query($room);

                    if ($resultRooms->num_rows > 0) {
                        while ($rowRoom = $resultRooms->fetch_assoc()) {
                            $roomId = $rowRoom["id"];
                            $roomName = $rowRoom["RoomName"];

                            // Count doctors for each room
                            $doctorQuery = "SELECT COUNT(*) AS doctorCount FROM patient_records WHERE status = '$roomName'";
                            $resultDoctors = $conn->query($doctorQuery);

                            if ($resultDoctors && $resultDoctors->num_rows > 0) {
                                $rowDoctor = $resultDoctors->fetch_assoc();
                                $doctorCount = $rowDoctor["doctorCount"];
                            } else {
                                $doctorCount = 0; // If no doctors found for the room
                            }

                            echo '<li><a href="#" class="notification"><span><i class="fa-solid fa-user-doctor"></i>' . $roomName . '</span><span class="badge">' . $doctorCount . '</span></a></li>';
                        }
                    } else {
                        echo 'No active rooms found.';
                    }
                    ?>
                </ul>
                <br><br>
                <div class="fill" style="margin-top: -2px;">
                    <img src="../Assets/undraw_medicine_b1ol.png">
                </div>
            </div>
            <br>
            <div class="right-div">
                <div id="main">
                    <br>
                    <div class="head">
                        <div class="col-1">
                            <p class="nav-title">Home</p>

                            <div class="user-profile">
                                <div onclick="myFunction()" class="user"><i class="fa fa-user"></i> <?php echo $userName ?></div>
                                <div id="myDropdown" class="dropdown-content">
                                    <a href="#"><i class="fa-regular fa-address-card"></i> Profile</a>
                                    <a href="../logout.php"><i class="fa-solid fa-right-from-bracket"></i> Logout</a>
                                </div>


                            </div>
                        </div>
                        <br>
                        <br>
                        <!-- burner -->
                        <!-- <div class="banner">
                        <div class="banner-words">
                            <p>Welcome to 265 OutPatient Management System, your trusted OutPatient Electronic Medical Record management System</p>
                        </div>
                        <div class="banner-image">
                            <img src="../Assets/banner-image.png">
                        </div>
                    </div> -->
                        <!-- Tiles -->
                        <div class="services">

                            <a href="nurse.php" style="width: 300px; height:180px;">
                                <p>Nurse's Station</p>
                                <img src="../Assets/nurse.png" style="width: 30%;">
                                <p>Check For Vitals <i class="fa-solid fa-arrow-right"></i></p>
                            </a>

                            <a href="doctorsoffice.php" style="width: 300px;">
                                <p>Doctor's Office</p>
                                <img src="../Assets/Consult.png" style="width: 30%;">
                                <p>Diagnosise & Prescribe <i class="fa-solid fa-arrow-right"></i></p>
                            </a>
                        </div>
                        <?php
                        // Include database connection code here

                        // Define an associative array to store disease counts
                        $diseaseCounts = array(
                            "Cold & Flu" => 0,
                            "STIs" => 0,
                            "Bacterial Infections" => 0,
                            "Malaria" => 0,
                            "Other" => 0
                        );

                        // Define SQL queries for each disease category using LIKE operator
                        $queries = array(
                            "Cold & Flu" => "SELECT COUNT(*) AS count FROM diagnosis WHERE diagnosis LIKE '%cold%' OR diagnosis LIKE '%flu%'",
                            "STIs" => "SELECT COUNT(*) AS count FROM diagnosis WHERE diagnosis LIKE '%sti%' OR diagnosis LIKE '%sexually transmitted infection%'",
                            "Bacterial Infections" => "SELECT COUNT(*) AS count FROM diagnosis WHERE diagnosis LIKE '%bacterial infection%'",
                            "Malaria" => "SELECT COUNT(*) AS count FROM diagnosis WHERE diagnosis LIKE '%malaria%'"
                        );

                        // Execute each query and update the diseaseCounts array
                        foreach ($queries as $disease => $query) {
                            $result = mysqli_query($conn, $query);
                            if ($result && mysqli_num_rows($result) > 0) {
                                $row = mysqli_fetch_assoc($result);
                                $diseaseCounts[$disease] = intval($row['count']);
                            }
                        }

                        // Calculate total count for all diseases
                        $totalCount = array_sum($diseaseCounts);

                        // Calculate percentages for each disease
                        $diseasePercentages = array();
                        foreach ($diseaseCounts as $disease => $count) {
                            $percentage = ($totalCount > 0) ? ($count / $totalCount) * 100 : 0;
                            $diseasePercentages[$disease] = $percentage;
                        }
                        ?>


                        <div class="graph">
                            <h1>Yearly Disease Stats</h1>
                            <div class="simple-bar-chart">
                                <?php foreach ($diseasePercentages as $disease => $percentage) : ?>
                                    <div class="item" style="--clr: <?php echo getRandomColor(); ?>; --val: <?php echo $percentage; ?>">
                                        <div class="label"><?php echo $disease; ?></div>
                                        <div class="value"><?php echo number_format($percentage, 2); ?> %</div>
                                    </div>
                                <?php endforeach; ?>
                            </div>
                            <?php

                            mysqli_close($conn);

                            // Function to generate a random color for the bar chart items
                            function getRandomColor()
                            {
                                $colors = array('#f1c305', '#5EB344', '#F8821A', '#00008B', '#E0393E', '#3eaf43');
                                return $colors[array_rand($colors)];
                            }
                            ?>

                        </div>
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
