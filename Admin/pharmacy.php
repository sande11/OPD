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
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>265 OutPatient</title>
    <link rel="stylesheet" type="text/css" href="../style/nurse.css">
    <link rel="stylesheet" href="../css/all.css">
    <script src="../java.js"></script>

    <style>
        form .view {
            display: inline-flexbox;
            float: right;
            padding: 10px;
            margin: 8px;
            border-radius: 5px;
            border: 1px;
            background-color: #f0a732;
        }
    </style>
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
                    <li><a href="admin-panel.php"><i class="fa-solid fa-chevron-left"></i>Admin panel</a></li> <!-- <li><a href=""><i class="fa fa-bullhorn"></i> Support</a></li> -->
                </ul>
                <br><br>
                <div class="fill" style="margin-top: 180px;">
                    <img src="../Assets/undraw_medicine_b1ol.png">
                </div>
            </div>
            <br>
            <div class="right-div">
                <div id="main">
                    <br>
                    <div class="head">
                        <div class="col-1">
                            <p class="nav-title">Pharmacy</p>

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
                        <div class="line"></div>

                        <!-- search button -->
                        <br>

                        <?php
                        // display data
                        include_once '../includes/conn.php';


                        // Fetch and display data
                        $sql = "SELECT * FROM patient_records WHERE status = 'Phamarcy'";

                        $result = mysqli_query($conn, $sql);

                        if (mysqli_num_rows($result) > 0) {
                            while ($row = mysqli_fetch_assoc($result)) {
                                $patient_id = $row['patient_id'];

                                echo '<div class="patient-block">';
                                echo '<p class="name">' . $row['name'] . ' </p>';
                                // echo '<form method="post" action="">';
                                // echo '<input type="hidden" name="patient_id" value="' . $row['patient_id'] . '">';
                                // echo '<input type="hidden" name="name" value="' . $row['name'] . '">';
                                // echo '<button type="submit" class="view">Proceed</button>';
                                // echo '<select name="patient_status" class="view">';
                                // echo '<option value="Doctor">See Doctor</option>';
                                // echo '<option value="Finish">Finish</option>';
                                // echo '</select>';
                                // echo '</form>';
                                // echo '<button class="view"><a href="./prescribe.php?patient_id=' . $row["patient_id"] . '">+ Medication</a></button>';

                                // echo '<button class="view" onclick="openEditForm(' . $row["patient_id"] . ')">View</button>';
                                echo '</div>';
                            }
                        } else {
                            echo '<h2 style="text-align: center;">No patients waiting</h2?';
                        }

                        // set status
                        if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST["patient_id"]) && isset($_POST["patient_status"])) {
                            $patient_id = $_POST["patient_id"];
                            $status = $_POST["patient_status"];

                            // Update status for the selected patient ID
                            $update_sql = "UPDATE patient_records SET status = '$status' WHERE patient_id = $patient_id";
                            $update_result = mysqli_query($conn, $update_sql);

                            if ($update_result) {
                                $alert = "Patient has received treatment successfully";

                                echo "<script>alert('$alert'); window.location.href = 'pharmacy dashboard.php';</script>";
                            } else {
                                echo "Error updating record: " . mysqli_error($conn);
                            }
                        }

                        ?>
