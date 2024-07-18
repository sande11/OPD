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

        table,
        th,
        td {
            border: 2px solid #6db2e7;
            border-collapse: collapse;
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
                    <li><a href="xray_dashboard.php"><i class="fa fa-th-large"></i> Home</a></li>
                    <!-- <li><a href="xrayresults.php"><i class="fa-solid fa-x-ray"></i> Xray Results</a></li> -->
                    <!-- <li><a href=""><i class="fa fa-bullhorn"></i> Support</a></li> -->
                </ul>
                <br><br>
                <div class="fill" style="margin-top: 190px;">
                    <img src="../Assets/undraw_medicine_b1ol.png">
                </div>
            </div>
            <br>
            <div class="right-div">
                <div id="main">
                    <br>
                    <div class="head">
                        <div class="col-1">
                            <p class="nav-title">Xray</p>

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
                        <br>

                        <table style="width:95%; margin-left: 30px; margin-top: -10px;">
                            <tr>
                                <th colspan="2"><span style="margin-left:-550px;">Patient Name</span></th>
                                <!-- <th style="width:8%;">Vitals & History</th>
                                <th style="width:11%;">Diagnose</th> -->
                                <th style="width:9%;">Tests orders</th>
                                <th style="width:10%;">Refer To</th>
                                <th style="width:12%;">Confirm</th>
                            </tr>
                        </table>

                        <?php
                        // display data

                        include_once '../includes/conn.php';

                        $current_date = date('Y-m-d');
                        // Fetch and display data

                        $sql_patient = "
        SELECT pr.patient_id, pr.name, xto.order_id
        FROM patient_records pr
        INNER JOIN xray_test_orders xto ON pr.patient_id = xto.patient_id
        WHERE pr.status = 'Xray'
        AND pr.patient_id = xto.patient_id  -- Additional condition to match patient_id
        AND xto.test_status != 'completed'  -- Condition to exclude completed tests
    ";

                        $result = mysqli_query($conn, $sql_patient);

                        $patient_id = "";
                        $patient_name = "";

                        if (mysqli_num_rows($result) > 0) {
                            while ($row = mysqli_fetch_assoc($result)) {
                                $patient_id = $row['patient_id'];
                                $patient_name = $row['name'];
                                $order_id = $row['order_id'];

                                echo '<div class="patient-block" >';
                                echo '<p class="name"> ' . $patient_name . '</p>';

                                echo '<form method="post" action="" id="lab_status_form" onchange="submitForm()">';
                                echo '  <input type="hidden" name="patient_id" value="' . $row['patient_id'] . '">';
                                echo '<select name="test_status" class="view">';
                                echo '<option value="Pending">Pending</option>';
                                echo '<option value="Processing">Processing</option>';
                                echo '<option value="Completed">Completed</option>';
                                echo '<option value="Cancelled">Cancelled</option>';
                                echo '</select>';
                                echo '</form>';
                                echo '<button class="view" onclick="openForm(' . $order_id . ')"><span style="width:10px">+ </span>Results</button>';
                                echo '<button class="view" onclick="openEditForm(' . $row["patient_id"] . ')">View orders</button>';
                                echo '</div>';
                            }
                        } else {
                            echo "<h2 style=\"text-align: center;\"> No Patients Waiting</h2>";
                        }

                        // test status 
                        if ($_SERVER["REQUEST_METHOD"] == "POST") {

                            if (isset($_POST['test_status'])) {
                                $test_status = $_POST['test_status'];

                                $patient_id = $_POST['patient_id'];

                                // Update query
                                $update_query = "UPDATE xray_test_orders SET test_status = '$test_status' WHERE patient_id = '$patient_id' AND DATE(created_at)='$current_date'";

                                // Execute the query
                                if (mysqli_query($conn, $update_query)) {
                                    // Data updated successfully
                                    echo "<script>alert('Test status updated successfully.'); window.location.href = 'xray_dashboard.php';</script>";
                                } else {
                                    // Error occurred
                                    echo "Error: " . mysqli_error($conn);
                                }
                            }
                        }

                        // xray results// Check if the form is submitted via POST method
                        if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['order_id'])) {
                            // Get the order_id from the form
                            $order_id = $_POST['order_id'];

                            if (isset($_FILES['images'])) {
                                $upload_dir = "uploads/xray/"; // Adjust the upload directory as needed

                                // Check if the upload directory exists, if not, create it
                                if (!is_dir($upload_dir)) {
                                    if (!mkdir($upload_dir, 0777, true)) {
                                        die("Failed to create upload directory.");
                                    }
                                }

                                // Loop through each uploaded file
                                foreach ($_FILES['images']['tmp_name'] as $key => $tmp_name) {
                                    $file_name = $_FILES['images']['name'][$key];
                                    $file_temp = $_FILES['images']['tmp_name'][$key];
                                    $file_error = $_FILES['images']['error'][$key];

                                    // Check for file upload errors
                                    if ($file_error === 0) {
                                        $file_destination = $upload_dir . $file_name;

                                        // Move the uploaded file to the destination
                                        if (move_uploaded_file($file_temp, $file_destination)) {
                                            // Update image_data in xray_test_orders table
                                            $update_query = "UPDATE xray_test_orders SET image_data = ? WHERE order_id = ?";
                                            $stmt_update = mysqli_prepare($conn, $update_query);

                                            // Check if the prepared statement is valid
                                            if ($stmt_update) {
                                                mysqli_stmt_bind_param($stmt_update, "si", $file_destination, $order_id);
                                                if (mysqli_stmt_execute($stmt_update)) {
                                                    // Image data updated successfully
                                                    echo "<script>alert('Image data updated successfully.');</script>";
                                                } else {
                                                    // Error in updating image data
                                                    echo "<script>alert('Error in updating image data: " . mysqli_error($conn) . "');</script>";
                                                }
                                                mysqli_stmt_close($stmt_update); // Close the prepared statement
                                            } else {
                                                // Error in preparing the statement
                                                echo "<script>alert('Error in preparing the statement to update image data: " . mysqli_error($conn) . "');</script>";
                                            }
                                        } else {
                                            // Error moving file to destination
                                            echo "<script>alert('Error moving file to destination.');</script>";
                                        }
                                    } else {
                                        // Error uploading file
                                        echo "<script>alert('Error uploading file: $file_name');</script>";
                                    }
                                }

                                // All files processed successfully
                                echo "<script>alert('All files uploaded and image data updated successfully.');</script>";
                            } else {
                                // No files uploaded
                                echo "<script>alert('No files uploaded.');</script>";
                            }
                        }

                        // set status
                        if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST["patient_id"]) && isset($_POST["patient_status"])) {
                            $patient_id = $_POST["patient_id"];
                            $status = $_POST["patient_status"];

                            // Update status for the selected patient ID
                            $update_sql = "UPDATE patient_records SET status = '$status' WHERE patient_id = $patient_id";
                            $update_result = mysqli_query($conn, $update_sql);

                            if ($update_result) {
                                $alert = "Patient referred to $status successfully";

                                echo "<script>alert('$alert'); window.location.href = 'doctorsoffice.php';</script>";
                            } else {
                                echo "Error updating record: " . mysqli_error($conn);
                            }
                        }

                        ?>
                        <!-- add xray results form  -->
                        <div class="form-popup" id="myForm">
                            <form action="" class="form-container" method="POST" enctype="multipart/form-data">
                                <h1>Xray results</h1>
                                <input type="hidden" name="order_id" value="<?php echo $order_id; ?>">
                                <label for="email" style="padding: 10px;"><b>Add Xray Images</b></label>
                                <input type="file" name="images[]" accept="image/*" style="padding: 15px;" multiple required> <br><br>
                                <button type="submit" class="btn" onclick="closeForm()">Submit</button>
                                <button type="button" class="btn cancel" onclick="closeForm()">Close</button>
                            </form>




                            <script>
                                // test
                                function openTest() {
                                    document.getElementById("testsForm").style.display = "block";
                                }

                                function closeTest() {
                                    document.getElementById("testsForm").style.display = "none";
                                }
                                // view
                                function openView() {
                                    document.getElementById("view").style.display = "block";
                                }

                                function closeView() {
                                    document.getElementById("view").style.display = "none";
                                }
                            </script>



                            <script>
                                function submitForm() {
                                    document.getElementById("lab_status_form").submit();
                                }

                                function openEditForm(patientId) {
                                    const url = `xray_orders.php?patient_id=${patientId}`;
                                    const width = 400;
                                    const height = 400;
                                    const left = (window.innerWidth / 2) - (width / 2);
                                    const top = (window.innerHeight / 2) - (height / 2);
                                    const features = `width=${width},height=${height},left=${left},top=${top}`;
                                    const newWindow = window.open(url, 'Edit Patient Record', features);
                                    newWindow.focus();
                                }
                            </script>
