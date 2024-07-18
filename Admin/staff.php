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
    <!-- <link rel="stylesheet" type="text/css" href="../style/style.css"> -->
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

        table,
        th,
        td {
            border: 2px solid #6db2e7;
            border-collapse: collapse;
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
                <br><br>
                <div class="fill" style="margin-top: -0px;">
                    <img src="../Assets/undraw_medicine_b1ol.png">
                </div>
            </div>
            <br>
            <div class="right-div">
                <div id="main">
                    <br>
                    <div class="head">
                        <div class="col-1">
                            <p class="nav-title">Doctor's Office</p>

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


                        <table style="width:95%; margin-left: 30px; margin-top: 10px; ">
                            <tr>
                                <th colspan="2"><span style="margin-left:-470px;">Employee Name</span></th>
                                <th style="width:15%;">Department</th>
                                <th style="width:14%;">Employee Status</th>
                                <th style="width:12%;">Actions</th>
                            </tr>
                        </table>

                        <?php

                        include_once '../includes/conn.php';
                        $limit = 8; // Number of records per page
                        $page = isset($_GET['page']) ? $_GET['page'] : 1; // Current page number, default is 1
                        $start = ($page - 1) * $limit; // Offset for SQL query

                        $sql = "SELECT * FROM users LIMIT $start, $limit";
                        $result = mysqli_query($conn, $sql);

                        while ($row = mysqli_fetch_assoc($result)) {
                            echo '<div class="patient-block">';
                            echo '<p class="name">' . $row['name'] . '</p>';
                            echo '<form method="post" action="" id="status_form' . $row['user_id'] . '">';
                            echo '<input type="hidden" name="user_id" value="' . $row['user_id'] . '">';
                            echo '<select name="status" class="view"onchange="submitForm(' . $row['user_id'] . ')">';
                            echo '<option value="Active" ' . ($row['status'] == 'Active' ? 'selected' : '') . '>Deactivate</option>';
                            echo '<option value="Inactive" ' . ($row['status'] == 'Inactive' ? 'selected' : '') . '>Activate</option>';
                            // echo '<option value="Nurse" ' . ($row['status'] == 'Nurse' ? 'selected' : '') . '>Archive</option>';
                            echo '</select>';
                            echo '</form>';
                            echo '<button class="view" style="background-color: #6db2e7; width: 130px; font-size: 15px;">' . $row['status'] . '</button>';
                            echo '<button class="view" style="background-color: #6db2e7; font-size: 15px; justify-content:center; width: 130px;">' . $row['department'] . '</button>';
                            echo '</div>';
                        }


                        if ($_SERVER["REQUEST_METHOD"] == "POST") {
                            // Check if the form was submitted
                            $user_id = $_POST['user_id'];
                            $new_status = $_POST['status'];

                            // Update the status in the database
                            $update_query = "UPDATE users SET status='$new_status' WHERE user_id='$user_id'";
                            $update_result = mysqli_query($conn, $update_query);

                            if ($update_result) {
                                // Display success message using JavaScript alert
                                echo '<script>alert("Status updated successfully.");</script>';
                                // Reload the page
                                echo '<script>window.location.href = window.location.href;</script>';
                                exit(); // Stop further execution to prevent duplicate alerts
                            } else {
                                echo "Error updating status: " . mysqli_error($conn);
                            }
                        }
                        // Pagination links
                        $sqlTotal = "SELECT COUNT(*) as total FROM users";
                        $resultTotal = mysqli_query($conn, $sqlTotal);
                        $rowTotal = mysqli_fetch_assoc($resultTotal);
                        $totalPages = ceil($rowTotal['total'] / $limit);

                        // echo '<div style="margin-left: 30px; margin-top: 10px;">';
                        echo '<div class="pagination">';

                        for ($i = 1; $i <= $totalPages; $i++) {
                            echo '<a href="?page=' . $i . '"> ' . $i . '</a>';
                        }
                        echo '</div>';
                        mysqli_close($conn);
                        ?>

                        <script>
                            function submitForm(userId) {
                                document.getElementById("status_form" + userId).submit();
                            }
                        </script>
