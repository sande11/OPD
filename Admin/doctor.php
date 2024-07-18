<?php
session_start();

if (isset($_SESSION['name'])) {
    $userName = $_SESSION['name'];
    $departmentName = $_SESSION['department'];
} else {
    header("Location: ../Auth.php");
    exit();
}

include_once '../includes/conn.php';

// Check if a room has been selected
if (isset($_POST['roomSelect'])) {
    $_SESSION['selectedRoom'] = $_POST['roomSelect'];
}
// nurse count
$sqlcount = "SELECT COUNT(*) AS nurse_count FROM patient_records WHERE status = 'Doctor'";
$result = $conn->query($sqlcount);

if ($result->num_rows > 0) {
    // Output data of each row
    while ($row = $result->fetch_assoc()) {
        $nurseCount = $row["nurse_count"];
    }
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

        #roomSelect {
            padding: 10px;
            margin: 8px;
            border-radius: 5px;
            border: 1px;
            background-color: #f0a732;
            width: 150px;
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

        .notification {
            background-color: #f0f8fd;
            color: black;
            text-decoration: none;
            padding: 15px 0px;
            position: relative;
            display: inline-block;
            border-radius: 2px;
        }

        .notification:hover {
            background: red;
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

        table,
        th,
        td {
            border: 1px solid black;
            border-collapse: collapse;
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
                    <li><a href="admin-panel.php"><i class="fa-solid fa-chevron-left"></i>Admin panel</a></li>

                    <li style="margin-left: -90px;">
                        <form method="POST">
                            <select id="roomSelect" name="roomSelect" class="view" onchange="this.form.submit()">
                                <option>Select Room</option>

                                <?php
                                $room = "SELECT * FROM rooms WHERE Status = 'Active'";
                                $resultRooms = $conn->query($room);

                                if ($resultRooms->num_rows > 0) {
                                    while ($rowRoom = $resultRooms->fetch_assoc()) {
                                        $roomId = $rowRoom["id"];
                                        $roomName = $rowRoom["RoomName"];
                                        $selected = '';
                                        if (isset($_SESSION['selectedRoom']) && $_SESSION['selectedRoom'] == $roomName) {
                                            $selected = 'selected';
                                        }
                                        echo '<option value="' . $roomName . '" ' . $selected . '>' . $roomName . '</option>';
                                    }
                                } else {
                                    echo '<option disabled>No active rooms found</option>';
                                }
                                ?>
                            </select>
                        </form>
                    </li>
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
                            <p class="nav-title">Doctor's Office</p>

                            <div class="user-profile">
                                <div onclick="myFunction()" class="user"><i class="fa fa-user"></i> <?php echo  $userName ?></div>
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
                        if (isset($_SESSION['selectedRoom'])) {
                            $selectedRoomName = $_SESSION['selectedRoom'];

                            // Sanitize the input (assuming $conn is your database connection)
                            $selectedRoomName = $conn->real_escape_string($selectedRoomName);

                            // Prepare a statement to select patient records for the selected room with status 'Doctor'
                            $sql_patient = "SELECT * FROM patient_records WHERE status = '$selectedRoomName'";
                            $resultPatients = $conn->query($sql_patient);

                            $patient_id = "";
                            $patient_name = "";

                            if (mysqli_num_rows($resultPatients) > 0) {
                                while ($row = mysqli_fetch_assoc($resultPatients)) {
                                    $patient_id = $row['patient_id'];
                                    $patient_name = $row['name'];

                                    echo '<div class="patient-block" >';
                                    echo '<p class="name"> ' . $patient_name . '</p>';

                                    // echo '<form method="post" action="">';
                                    // echo '<input type="hidden" name="patient_id" value="' . $row['patient_id'] . '">';
                                    // echo '<input type="hidden" name="name" value="' . $row['name'] . '">';
                                    // echo '<button type="submit" class="view">Proceed</button>';
                                    // echo '<select name="patient_status" class="view">';
                                    // echo '<option value="Nurse">Nurse</option>';
                                    // echo '<option value="Lab">Lab</option>';
                                    // echo '<option value="Xray">Xray</option>';
                                    // echo '<option value="Cashier">Cashier</option>';
                                    // echo '<option value="Xray & Lab">Xray & Lab</option>';
                                    // echo '</select>';
                                    // echo '</form>';
                                    // echo '<button class="view" onclick="openTest()" id="testButton" >Order Test</button>';
                                    // echo '<button class="view" onclick="openForm()"><span style="width:20px">+ </span>Diagnosis</button>';
                                    // echo '<button class="view" onclick="openEditForm(' . $row["patient_id"] . ')">View</button>';
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
                                    // test orders
                                    echo '<div class="form-popup" id="testsForm" style="display:none;">';
                                    echo '<form action="testorders.php" class="form-container" method="POST">';
                                    echo '<h1>Order Test</h1>';
                                    echo '<input type="hidden" name="patient_id" value="' . $row['patient_id'] . '">'; // Hidden input field to pass patient_id
                                    echo '<label for=""><b>Lab Test Details</b></label>';
                                    echo '<input type="text" placeholder="Enter the details for the lab test selected" name="lab_test_orders" size="50">';
                                    echo '<label for=""><b>Xray Imaging Details</b></label>';
                                    echo '<input type="text" placeholder="Enter the details for the X-ray imaging selected" name="xray_imaging_orders" size="50">';
                                    echo '<button type="submit" class="btn">Submit</button>';
                                    echo '<button type="button" class="btn cancel" onclick="closeTest()">Close</button>';
                                    echo '</form>';
                                    echo '</div>';
                                    // Diagnosis form
                                    echo '<div class="form-popup" id="myForm" style="display:none;">';
                                    echo '<form action="diagnosissubmission.php" class="form-container" method="POST">';
                                    echo '<h1>Diagnosis</h1>';
                                    echo '<input type="hidden" name="patient_id" value="' . $row['patient_id'] . '">';
                                    echo '<label for="email"><b>Diagnosis</b></label>';
                                    echo '<input type="text" placeholder="Enter patient\'s diagnosis" name="diagnosis" required>';
                                    echo '<label><b>Prescription</b></label>';
                                    echo '<input type="text" id="drugFilter" placeholder="Search drugs">';
                                    echo '<div style="max-height: 150px; overflow-y: auto;">';


                                    // Fetch drug data from the database
                                    $sql_drugs = "SELECT medication_id, medication_name FROM drugs";
                                    $result_drugs = mysqli_query($conn, $sql_drugs);

                                    // Check if there are drugs available
                                    if (mysqli_num_rows($result_drugs) > 0) {
                                        $prescription = [];
                                        $quantity = [];

                                        // Start the drug list container
                                        echo '<div id="drugList">';

                                        while ($drug_row = mysqli_fetch_assoc($result_drugs)) {
                                            // Dynamically generate checkboxes and input fields for each drug
                                            echo '<label>';
                                            echo '<input type="checkbox" name="prescription[]" value="' . $drug_row['medication_name'] . '">';
                                            echo $drug_row['medication_name'];
                                            echo ' Quantity: <input type="text" name="quantity[' . $drug_row['medication_id'] . ']" placeholder="Quantity">';
                                            echo '</label>';

                                            // Add the medication ID to the prescription array if it is selected
                                            if (isset($_POST['prescription']) && in_array($drug_row['medication_id'], $_POST['prescription'])) {
                                                $prescription[] = $drug_row['medication_name'];
                                                $quantity[$drug_row['medication_id']] = $_POST['quantity'][$drug_row['medication_id']];
                                            }
                                        }

                                        // End the drug list container
                                        echo '</div>';
                                    } else {
                                        echo "No drugs available.";
                                    }
                                    echo '</div>';
                                    echo '<button type="submit" class="btn">Submit</button>';
                                    echo '<button type="button" class="btn cancel" onclick="closeForm()">Close</button>';
                                    echo '</form>';
                                    echo '</div>';

                                    echo '<script>
                document.getElementById("drugFilter").addEventListener("input", function() {
                    var filterValue = this.value.toLowerCase();
                    var drugLabels = document.querySelectorAll("#drugList label");

                    drugLabels.forEach(function(label) {
                        var drugName = label.textContent.toLowerCase();
                        if (drugName.includes(filterValue)) {
                            label.style.display = "block";
                        } else {
                            label.style.display = "none";
                        }
                    });
                });
                </script>';
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
                        } else {
                            echo "<h2 style=\"text-align: center;\"> Select a room on the sidebar</h2>";
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
                            function openEditForm(patientId) {
                                const url = `./view_page.php?patient_id=${patientId}`;
                                const width = 800;
                                const height = 500;
                                const left = (window.innerWidth / 2) - (width / 2);
                                const top = (window.innerHeight / 2) - (height / 2);
                                const features = `width=${width},height=${height},left=${left},top=${top}`;
                                const newWindow = window.open(url, 'Edit Patient Record', features);
                                newWindow.focus();
                            }
                        </script>
