<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>265 OutPatient</title>
    <link rel="stylesheet" type="text/css" href="../style.css">
    <link rel="stylesheet" href="../css/all.css">
    <script src="../java.js"></script>
</head>

<body>
    <div class="overlay">
        <!-- left bar  -->
        <section>

            <div class="left">
                <div class="logo">
                    <img src="../Assets/cardiogram 2.png" alt="logo">
                    <p class="logo-name"><span>265</span>OPMS </p>
                </div>
                <br>
                <div class="side-line"></div>
                <!-- nav -->
                <ul class="nav">
                    <li><a href="Dashboard.html"><i class="fa fa-th-large"></i> Home</a></li>
                    <li><a href="patients.html"><i class="fa fa-user"></i> Patients</a></li>
                    <li><a href=""><i class="fa-solid fa-microscope"></i> Test Orders</a></li>
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
                            <p class="nav-title">Lab & X-Ray</p>

                            <div class="user-profile">
                                <div onclick="myFunction()" class="user"><i class="fa fa-user"></i> Kelvin Sande</div>
                                <div id="myDropdown" class="dropdown-content">
                                    <a href="#"><i class="fa-regular fa-address-card"></i> Profile</a>
                                    <a href="#"><i class="fa-solid fa-right-from-bracket"></i> Logout</a>
                                </div>
                            </div>
                        </div>
                        <br>
                        <br>
                        <div class="line"></div>
                        <!-- search button -->
                        <br>
                        <br>
                        <br>
                        <div class="search-div">
                            <div class="search">
                                <input type="text" class="searchTerm" placeholder="Enter Patient's ID number">
                                <button type="submit" class="searchButton">
                                    <i class="fa fa-search"></i>
                                </button>
                            </div>
                        </div>


                        <!-- patients list -->
                        <!-- <p class="list-heading">Here is a list of patients currently being assisted</p> -->
                        <div class="patient-block">
                            <p class="name">Kelvin Sande </p>
                            <button class="view" style="margin-left:250px ;">Add Results</button>
                            <button class="view" style="margin-top:-50px; margin-left:20px;">Finish Tests</button>
                        </div>

                        <div class="patient-block">
                            <p class="name">Kelvin Sande </p>
                            <button class="view" style="margin-left:250px ;">Add Results</button>
                            <button class="view" style="margin-top:-50px; margin-left:20px;">Finish Tests</button>
                        </div>

                        <div class="patient-block">
                            <p class="name">Kelvin Sande </p>
                            <button class="view" style="margin-left:250px ;">Add Results</button>
                            <button class="view" style="margin-top:-50px; margin-left:20px;">Finish Tests</button>
                        </div>
