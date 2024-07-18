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
    <link rel="stylesheet" type="text/css" href="../style/doctorsoffice.css">
    <link rel="stylesheet" href="../css/all.css">
    <script src="../java.js"></script>
    <script src="../jQuery/jquery.js"></script>
    <style>
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
                    <li><a href="doctorsoffice.php"><i class="fa-solid fa-chevron-left"></i> Doctor's Office</a></li>
                    <li><a href="digitalresults.php"><i class="fa-solid fa-microscope"></i> Digital lab results</a></li>
                    <li><a href="xray.php"><i class="fa-solid fa-x-ray"></i> Xray results</a></li>
                </ul>
                <br><br>
                <div class="fill" style="margin-top: 140px;">
                    <img src="../Assets/undraw_medicine_b1ol.png">
                </div>
            </div>
            <br>
            <div class="right-div">
                <div id="main">
                    <br>
                    <div class="head">
                        <div class="col-1">
                            <p class="nav-title">Lab Results</p>

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
                        <br>
                        <table style="width:95%; margin-left: 30px; margin-top: -10px;">
                            <tr>
                                <th colspan="2"><span style="margin-left:-380px;">Patient Name</span></th>
                                <th style="width:8%;">Results</th>
                                <th style="width:11%;">Diagnose</th>
                                <th style="width:10%;">Test Status</th>
                                <th style="width:10%;">Refer To</th>
                                <th style="width:8%;">Confirm</th>
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
                INNER JOIN lab_test_orders ON patient_records.patient_id = lab_test_orders.patient_id
                WHERE patient_records.status IN ('Lab') AND DATE(lab_test_orders.created_at) = '$current_date'";
                        $result = mysqli_query($conn, $sql_patient);

                        $patient_id = "";
                        $patient_name = "";

                        if (mysqli_num_rows($result) > 0) {
                            while ($row = mysqli_fetch_assoc($result)) {
                                $patient_id = $row['patient_id'];
                                $patient_name = $row['name'];
                                // $order_id = $row['order_id'];

                                $sql_status = "SELECT test_status FROM lab_test_orders WHERE patient_id = $patient_id";
                                $result_status = mysqli_query($conn, $sql_status);
                                $row_status = mysqli_fetch_assoc($result_status);
                                $patient_status = $row_status['test_status'] ?? 'Doctor';

                                echo '<div class="patient-block" >';
                                echo '<p class="name"> ' . $patient_name . '</p>';

                                echo '<form method="post" action="">';
                                echo '<input type="hidden" name="patient_id" value="' . $row['patient_id'] . '">';
                                echo '<input type="hidden" name="name" value="' . $row['name'] . '">';
                                echo '<button type="submit" class="view">Proceed</button>';
                                echo '<select name="patient_status" class="view">';
                                // echo '<option value="Nurse">Nurse</option>';
                                echo '<option value="Cashier">Cashier</option>';
                                echo '</select>';
                                echo '</form>';
                                echo '<button class="view" onclick="openTest()" id="testButton" >' . ucfirst($patient_status) . '</button>';
                                echo '<button class="view" onclick="openForm()"><span style="width:20px">+ </span>Diagnosis</button>';
                                echo '<button class="view" onclick="openEditForm(' . $row['order_id'] . ')">View</button>';
                                echo '</div>';
                                // view patient
                                echo '<div class="form-popup" id="view" style="display:none;">';
                                echo '<form action="" class="form-container" method="POST">';
                                echo '<h1>Patient Details</h1>';

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

                                // Diagnosis form
                                echo '<div class="form-popup" id="myForm" style="display:none;">';
                                echo '<form action="" class="form-container" method="POST">';
                                echo '<h1>Diagnosis</h1>';
                                echo '<input type="hidden" name="patient_id" value="' . $row['patient_id'] . '">';
                                echo '<label for="email"><b>Diagnosis</b></label>';
                                echo '<input type="text" placeholder="Enter patient\'s diagnosis" name="diagnosis" required>';
                                echo '<label for="psw"><b>Prescription</b></label>';
                                echo '<div style="max-height: 150px; overflow-y: auto;">';

                                // Fetch drug data from the database
                                $sql_drugs = "SELECT medication_id, medication_name FROM drugs";
                                $result_drugs = mysqli_query($conn, $sql_drugs);

                                // Check if there are drugs available
                                if (mysqli_num_rows($result_drugs) > 0) {
                                    $prescription = [];
                                    $quantity = [];
                                    while ($drug_row = mysqli_fetch_assoc($result_drugs)) {
                                        // Dynamically generate checkboxes and input fields for each drug
                                        echo '<label>';
                                        echo '<input type="checkbox" name="prescription[]" value="' . $drug_row['medication_name'] . '">';
                                        echo $drug_row['medication_name'];
                                        echo '<br>';
                                        echo ' Quantity or Dose duration: <input type="text" name="quantity[' . $drug_row['medication_id'] . ']" placeholder="Quantity or dose duration">';
                                        echo '</label>';

                                        // Add the medication ID to the prescription array if it is selected
                                        if (isset($_POST['prescription']) && in_array($drug_row['medication_id'], $_POST['prescription'])) {
                                            $prescription[] = $drug_row['medication_name'];
                                            $quantity[$drug_row['medication_id']] = $_POST['quantity'][$drug_row['medication_id']];
                                        }
                                    }
                                } else {
                                    echo "No drugs available.";
                                }

                                echo '</div>';
                                echo '<button type="submit" class="btn">Submit</button>';
                                echo '<button type="button" class="btn cancel" onclick="closeForm()">Close</button>';
                                echo '</form>';
                                echo '</div>';
                            }
                        } else {
                            echo "<h2 style=\"text-align: center;\"> No Patients Waiting</h2>";
                        }


                        if ($_SERVER["REQUEST_METHOD"] === "POST" && isset($_POST["patient_id"], $_POST["diagnosis"], $_POST["prescription"], $_POST["quantity"])) {
                            $patient_id = mysqli_real_escape_string($conn, $_POST["patient_id"]);
                            $diagnosis = mysqli_real_escape_string($conn, $_POST['diagnosis']);
                            $prescription = $_POST['prescription'];
                            $quantity = $_POST['quantity'];
                            $diagnosis_date = date("Y-m-d");

                            // Convert prescription and quantity arrays to JSON format
                            $prescription_json = json_encode($prescription);

                            // Filter out empty quantity values and encode quantity array to JSON format
                            $non_empty_quantities = array_filter($quantity, function ($value) {
                                return !empty($value);
                            });
                            $quantity_json = json_encode(array_values($non_empty_quantities));

                            // Construct and execute the SQL query to insert the diagnosis data
                            $sql = "INSERT INTO diagnosis (patient_id, diagnosis_date, diagnosis, prescription, quantity)
                                            VALUES ('$patient_id', '$diagnosis_date', '$diagnosis', '$prescription_json', '$quantity_json')";

                            if (mysqli_query($conn, $sql)) {
                                // Data inserted successfully
                                $alert = "Diagnosis and Prescription updated successfully";
                                echo "<script>alert('$alert')</script>";
                                echo "<script>window.location.href = 'testresults.php';</script>"; // Redirect back to the doctor's office page
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

                                echo "<script>alert('$alert'); window.location.href = 'testresults.php';</script>";
                            } else {
                                echo "Error updating record: " . mysqli_error($conn);
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
                            function openEditForm(orderId) {
                                const url = `./updatedresults.php?order_id=${orderId}`;
                                const width = 400;
                                const height = 400;
                                const left = (window.innerWidth / 2) - (width / 2);
                                const top = (window.innerHeight / 2) - (height / 2);
                                const features = `width=${width},height=${height},left=${left},top=${top}`;
                                const newWindow = window.open(url, 'Edit Patient Record', features);
                                newWindow.focus();
                            }
                        </script>
