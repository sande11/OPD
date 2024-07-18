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
    <link rel="stylesheet" type="text/css" href="../style/doctorsoffice.css">
    <link rel="stylesheet" href="../css/all.css">
    <script src="../java.js"></script>
    <script src="../jQuery/jquery.js"></script>
    <style>
        .active {
            background-color: #f0a732;
            /* Change background color to highlight */
            color: white;
            /* Change text color */
            /* Add any additional styling as needed */
        }

        form .view {
            display: flex;
            float: right;
            padding: 10px;
            margin: 8px;
            border-radius: 5px;
            border: 1px;
            background-color: #f0a732;
        }

        /* form, input[type="checkbox"] {
            display: inline;
            margin-right: 10px; 
        } */
        #author_bio_wrap {
            margin-top: 0px;
            margin-bottom: 30px;
            background: gray;
            width: auto;
            height: 50px;
        }

        #author_bio_wrap_toggle {
            display: block;
            width: 100%;
            height: 35px;
            line-height: 35px;
            background: #9E9E77;
            text-align: center;
            color: white;
            font-weight: bold;
            font-variant: small-caps;
            box-shadow: 2px 2px 3px #888888;
            text-decoration: none;
        }

        .patient-block .view {
            cursor: pointer;
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
                    <li><a href="lab dashboard.php"><i class="fa fa-th-large"></i> Home</a></li>
                    <!-- <li><a href=""><i class="fa-solid fa-calendar-days"></i> Schedule</a></li> -->
                    <li><a href="testresults.php"><i class="fa-solid fa-microscope"></i> Test Results</a></li>
                </ul>
                <br><br>
                <div class="fill" style="margin-top: 170px;">
                    <img src="../Assets/undraw_medicine_b1ol.png">
                </div>
            </div>
            <br>
            <div class="right-div">
                <div id="main">
                    <br>
                    <div class="head">
                        <div class="col-1">
                            <p class="nav-title">Laboratory</p>

                            <div class="user-profile">
                                <div onclick="myFunction()" class="user"><i class="fa fa-user"> </i><?php echo  $userName ?></div>
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
                        <table style="width:95%; margin-left: 30px; margin-top: -10px;">
                            <tr>
                                <th colspan="2"><span style="margin-left:-550px;">Patient Name</span></th>
                                <!-- <th style="width:8%;">Vitals & History</th>
                                <th style="width:11%;">Diagnose</th> -->
                                <th style="width:9%;">Tests<br> orders</th>
                                <th style="width:10%;">Add results</th>
                                <th style="width:12%;">Status</th>
                            </tr>
                        </table>


                        <!-- patients list -->
                        <!-- <p class="list-heading">Here is a list of patients currently being assisted</p> -->
                        <?php
                        // display data
                        include_once '../includes/conn.php';


                        // Fetch and display data

                        $current_date = date('Y-m-d');
                        $sql_patient = "SELECT patient_records.patient_id, patient_records.name, lab_test_orders.order_id 
        FROM patient_records 
        INNER JOIN lab_test_orders 
        ON patient_records.patient_id = lab_test_orders.patient_id 
        WHERE patient_records.status = 'Lab' 
        AND DATE(lab_test_orders.created_at) = '$current_date' 
        AND lab_test_orders.test_status != 'completed'";
                        $result = mysqli_query($conn, $sql_patient);

                        $patient_id = "";
                        $patient_name = "";

                        if (mysqli_num_rows($result) > 0) {
                            while ($row = mysqli_fetch_assoc($result)) {
                                $patient_id = $row['patient_id'];
                                $patient_name = $row['name'];

                                echo '<div class="patient-block" >';
                                echo '<p class="name"> ' . $patient_name . '</p>';

                                echo '<form method="post" action="" id="lab_status_form" onchange="submitForm()">';
                                echo '  <input type="hidden" name="patient_id" value="' . $row['patient_id'] . '">';
                                echo '<select name="lab_status" class="view">';
                                echo '<option value="Pending">Pending</option>';
                                echo '<option value="Processing">Processing</option>';
                                echo '<option value="Completed">Completed</option>';
                                echo '<option value="Cancelled">Cancelled</option>';
                                echo '</select>';
                                echo '</form>';
                                echo '<button class="view" onclick="openForm()"><span style="width:10px">+ </span>Results</button>';
                                echo '<button class="view" onclick="openEditForm(' . $row["patient_id"] . ')">View orders</button>';
                                echo '</div>';

                                // view patient
                                echo '<div class="form-popup" id="view" style="display:none;">';
                                echo '<form action="" class="form-container" method="POST">';
                                echo '<h1>Patient Details</h1>';
                                // echo '<p class="name">' . $patient_name. '</p>';

                                // Display vitals if available

                                $sql_vitals = "SELECT * FROM vital_records WHERE patient_id = '$patient_id' AND DATE(created_at) BETWEEN '$date_range_start' AND '$date_range_end'";
                                $result_vitals = mysqli_query($conn, $sql_vitals);
                                if (mysqli_num_rows($result_vitals) > 0) {
                                    echo '<h2>Vitals:</h2>';
                                    echo '<ul>';
                                    while ($row_vitals = mysqli_fetch_assoc($result_vitals)) {
                                        echo '<li>';
                                        echo 'Visit Date: ' . $row_vitals['visit_date'] . ', ';
                                        echo '</li>';
                                        echo '<li>';
                                        echo 'Weight: ' . $row_vitals['weight'] . ', ';
                                        echo '</li>';
                                        echo '<li>';
                                        echo 'Temperature: ' . $row_vitals['temperature'] . ', ';
                                        echo '</li>';
                                        echo '<li>';
                                        echo 'BP: ' . $row_vitals['blood_pressure'] . ', ';
                                        echo '</li>';
                                        echo '<li>';
                                        echo 'Heart Rate: ' . $row_vitals['heart_rate'] . ', ';
                                        echo '</li>';
                                        echo '<li>';
                                        echo 'Respiratory Rate: ' . $row_vitals['respiratory_rate'] . ', ';
                                        echo '</li>';
                                    }
                                    echo '</ul>';
                                } else {
                                    echo '<p>No vitals found for this patient.</p>';
                                }
                                echo '<button type="button" class="btn cancel" onclick="closeView()">Close</button>';
                                echo '</form>';
                                echo '</div>';

                                // add result form
                                echo '<div class="form-popup" id="myForm" style="display:none;">';
                                echo '<form action="" class="form-container" method="POST">';
                                echo '<h1>Test Results</h1>';
                                echo '<input type="hidden" name="order_id" value="' . $row['order_id'] . '">';
                                echo '<label for="email"><b>Result Details</b></label>';
                                echo '<input type="text" placeholder="Enter patient\'s results" name="results" style="height: 100px;outline:0; white-space: pre-wrap;   line-height: 150%;resize:vertical;">';
                                // echo '<label for="psw"><b>Prescribed Tests</b></label>';
                                echo '<div style="max-height: 150px; overflow-y: auto;">';

                                echo '</div>';
                                echo '<button type="submit" class="btn">Submit</button>';
                                echo '<button type="button" class="btn cancel" onclick="closeForm()">Close</button>';
                                echo '</form>';
                                echo '</div>';

                                echo '<div class="form-popup" id="testsForm" style="display:none;">';
                                echo '<form action="" class="form-container" method="POST">';
                                echo '<h1>Order Test</h1>';
                                echo '  <input type="hidden" name="patient_id" value="' . $row['patient_id'] . '">';
                                echo '<label for=""><b>Lab Test Details</b></label>';
                                echo '<input type="text" placeholder="Enter the details for the lab test selected" name="lab_test_orders" size="50">';

                                echo '<label for=""><b>Xray Imaging Details</b></label>';
                                echo '<input type="text" placeholder="Enter the details for the X-ray imaging selected" name="xray_imaging_orders" size="50">';

                                echo '<button type="submit" class="btn">Submit</button>';
                                echo '<button type="button" class="btn cancel" onclick="closeTest()">Close</button>';
                                echo '</form>';
                                echo '</div>';
                                //  update the diagnosis and prescription

                                if ($_SERVER["REQUEST_METHOD"] === "POST" && isset($_POST["patient_id"], $_POST["diagnosis"], $_POST["prescription"], $_POST["quantity"])) {
                                    $patient_id = mysqli_real_escape_string($conn, $_POST["patient_id"]);
                                    $diagnosis = mysqli_real_escape_string($conn, $_POST['diagnosis']);
                                    $prescription = $_POST['prescription'];
                                    $quantity = $_POST['quantity'];
                                    $diagnosis_date = date("Y-m-d");

                                    // Convert prescription and quantity arrays to JSON format
                                    $prescription_json = json_encode($prescription);
                                    $quantity_json = json_encode($quantity);

                                    // Construct and execute the SQL query to insert the diagnosis data
                                    $sql = "INSERT INTO diagnosis (patient_id, diagnosis_date, diagnosis, prescription, quantity)
                    VALUES ('$patient_id', '$diagnosis_date', '$diagnosis', '$prescription_json', '$quantity_json')";

                                    if (mysqli_query($conn, $sql)) {
                                        // Data inserted successfully
                                        $alert = "Diagnosis and Prescription updated successfully";
                                        echo "<script>alert('$alert')</script>";
                                    } else {
                                        // Error inserting data
                                        echo "Error: " . $sql . "<br>" . mysqli_error($conn);
                                    }
                                }
                            }
                        } else {
                            echo "<h2 style=\"text-align: center;\"> No Patients Waiting</h2>";
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

                        // test status 
                        if ($_SERVER["REQUEST_METHOD"] == "POST") {

                            if (isset($_POST['lab_status'])) {
                                $lab_status = $_POST['lab_status'];

                                $patient_id = $_POST['patient_id'];

                                // Update query
                                $update_query = "UPDATE lab_test_orders SET test_status = '$lab_status' WHERE patient_id = $patient_id";

                                // Execute the query
                                if (mysqli_query($conn, $update_query)) {
                                    // Data updated successfully
                                    echo "<script>alert('Test status updated successfully.'); window.location.href = 'lab dashboard.php';</script>";
                                } else {
                                    // Error occurred
                                    echo "Error: " . mysqli_error($conn);
                                }
                            }
                        }

                        if (isset($_POST['order_id']) && isset($_POST['results'])) {
                            $order_id = $_POST['order_id'];
                            $results = $_POST['results'];

                            $sql_update = "UPDATE lab_test_orders SET results = '$results' WHERE order_id = $order_id";
                            $result = mysqli_query($conn, $sql_update);

                            if ($result) {
                                echo "<script>alert('Results updated successfully.')</script>";
                            } else {
                                echo "Error: " . mysqli_error($conn);
                            }
                        }
                        ?>


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
                                const url = `test_orders.php?patient_id=${patientId}`;
                                const width = 400;
                                const height = 400;
                                const left = (window.innerWidth / 2) - (width / 2);
                                const top = (window.innerHeight / 2) - (height / 2);
                                const features = `width=${width},height=${height},left=${left},top=${top}`;
                                const newWindow = window.open(url, 'Edit Patient Record', features);
                                newWindow.focus();
                            }
                        </script>
