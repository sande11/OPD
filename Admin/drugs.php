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


        table {
            border-collapse: collapse;
            width: 100%;
        }

        th,
        td {
            text-align: left;
            padding: 8px;
        }

        tr:nth-child(even) {
            background-color: #f2f2f2
        }

        th {
            background-color: #6db2e7;
            color: white;
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
                    <li><a href="hospital.php"><i class="fa-solid fa-chevron-left"></i>Manage Hospital</a></li>
                </ul> <br><br>
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
                        <h2 style="text-align: center;">Drugs Available</h2>

                        <?php
                        // display data
                        include_once '../includes/conn.php';


                        // Fetch and display data
                        $sqlQuery = "SELECT * FROM drugs";
                        $result = mysqli_query($conn, $sqlQuery);

                        if ($result) {
                            echo "<div style='height: 500px; overflow-y: auto;'>";
                            echo "<table>";
                            echo "<tr>
                    <th>No.</th>
                    <th>Medication Name</th>
                    <th>Manufacturer</th>
                    <th>supplier</th>
                    <th>Batch Number</th>
                    <th>Quantity</th>
                    <th>Manufacture Date</th>
                    <th>Expiry Date</th>
                    <th>Cost</th>
                    <th>Remarks</th>
                  </tr>";

                            while ($row = mysqli_fetch_assoc($result)) {
                                echo "<tr>
                        <td>{$row['medication_id']}</td>
                        <td>{$row['medication_name']}</td>
                        <td>{$row['manufacturer_name']}</td>
                        <td>{$row['supplier_name']}</td>
                        <td>{$row['batch_number']}</td>
                        <td>{$row['quantity']}</td>
                        <td>{$row['manufacture_date']}</td>
                        <td>{$row['expiry_date']}</td>
                        <td>{$row['cost']}</td>
                        <td>{$row['remarks']}</td>
                      </tr>";
                            }

                            echo "</table>";
                            echo "</div>";
                        } else {
                            echo "Error: " . mysqli_error($conn);
                        }

                        mysqli_free_result($result);


                        mysqli_close($conn);
                        ?>
