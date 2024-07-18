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
  <meta charset="UTF-8" />
  <meta http-equiv="X-UA-Compatible" content="IE=edge" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title>265 OutPatient</title>
  <link rel="stylesheet" type="text/css" href="../style/clerk.css" />
  <link rel="stylesheet" href="../css/all.css" />
  <script src="../java.js"></script>
  <style>
    .patient-form {
      display: flex;
      flex-wrap: wrap;
      /* justify-content: space-between; */
      width: 100%;
      margin-left: 140px;
      margin-right: 60px
    }

    .patient-form-title {
      width: 100%;
      margin-left: 200px;
    }

    .patient-form-row {
      margin: 0 0 20px 0;
    }

    .patient-form-half {
      width: 50%;
    }

    input[type="text"],
    input[type="email"],
    input[type="number"],
    input[type="date"],
    select {
      width: 280px;
      box-sizing: border-box;
      height: 30px;
      border-radius: 5px;
      border: 2px;
      border-style: solid;
    }

    .group {
      display: inline-flex;
    }

    .patient-form-input {
      width: 100%;
      padding: 10px;
      margin: 5px 0;
      border: 1px solid #ccc;
      border-radius: 5px;
      font-size: 19px;
      line-height: 1.5;
    }

    .patient-form-label {
      font-size: 20px;
    }

    .patient-form-button {
      display: block;
      background-color: #6db2e7;
      color: white;
      border: none;
      border-radius: 5px;
      font-size: 16px;
      padding: 10px;
      cursor: pointer;
    }

    .background {
      background-color: #f0f8fd;
      margin-left: 40px;
      margin-right: 40px;
      margin-top: -70px;
      overflow: hidden;
      height: 90%;
    }

    .patient-form-button:hover {
      background-color: #efa632;
    }

    span {
      color: red;
    }
  </style>
</head>

<body>
  <div class="overlay">
    <!-- left bar  -->
    <section>
      <div class="left">
        <div class="logo">
          <img src="../Assets/cardiogram 2.png" alt="logo" />
          <p class="logo-name"><span>265</span>OPMS</p>
        </div>
        <br />
        <div class="side-line"></div>
        <!-- nav -->
        <ul class="nav">
          <li>
            <a href="Home.php"><i class="fa fa-th-large"></i> Home</a>
          </li>
          <li>
            <a href="dataclerk.php"><i class="fa-solid fa-hospital-user"></i>Register Patient</a>
          </li>
          <li>
            <a href="OPR.php"><i class="fa fa-book"></i> OPR</a>
          </li>
          <li>
            <a href="staff.php"><i class="fa fa-user-doctor"></i>Register Staff</a>
          </li>
          <li>
            <a href="../calendar/index.php"><i class="fa fa-calendar-days"></i>Appointments</a>
          </li> <!-- <li>
              <a href=""><i class="fa fa-money-bill"></i> Bills</a>
            </li>
            <li>
              <a href=""><i class="fa fa-bullhorn"></i> Support</a>
            </li> -->
        </ul>
        <br /><br />
        <div class="fill" style="margin-top: 40px;">
          <img src="../Assets/undraw_medicine_b1ol.png" />
        </div>
      </div>
      <br />
      <div class="right-div">
        <div id="main">
          <br />
          <div class="head">
            <div class="col-1">
              <p class="nav-title">Data Clerk</p>

              <div class="user-profile">
                <div onclick="myFunction()" class="user">
                  <i class="fa fa-user"></i> <?php echo $userName ?>
                </div>
                <div id="myDropdown" class="dropdown-content">
                  <a href="#"><i class="fa-regular fa-address-card"></i> Profile</a>
                  <a href="../logout.php"><i class="fa-solid fa-right-from-bracket"></i> Logout</a>
                </div>
              </div>
            </div>
            <br />
            <br />
            <div class="line"></div>

            <!-- search button -->
            <br />
            <br />
            <br />
            <br />
            <div class="background">
              <form id="patient-form" action="register patient.php" method="post" class="patient-form" onsubmit="return validateForm()">
                <h2 class="patient-form-title">Patient Registration Form</h2>

                <div class="patient-form-row patient-form-row--half">
                  <div class="patient-form-half right-form">
                    <div class="form-row">
                      <label for="name" class="patient-form-label">Name:<span>*</span></label><br>
                      <input type="text" name="patient_name" id="patient_name" required>
                    </div>
                    <div class="form-row">
                      <label for="id_type" class="patient-form-label">ID Type:<span>*</span></label><br>
                      <select name="id_type" id="id_type" required>
                        <option value="National ID">National ID</option>
                        <option value="Drivers License">Drivers License</option>
                        <option value="Passport">Passport</option>
                      </select>
                    </div>

                    <div class="form-row">
                      <label for="nationality" class="patient-form-label">ID Number:<span>*</span></label><br>
                      <input type="text" name="id_number" id="id_number" required>
                    </div>

                    <div class="form-row">
                      <label for="dob" class="patient-form-label">Date of Birth:<span>*</span></label>
                      <input type="date" name="dob" id="dob" required>
                    </div>

                    <div class="form-row">
                      <label for="gender" class="patient-form-label">Gender:<span>*</span></label><br>
                      <div class="group">
                        <input type="radio" id="male" name="gender" value="male" required>
                        <label for="male">Male</label><br>
                      </div>
                      <div class="group">
                        <input type="radio" id="female" name="gender" value="female" required>
                        <label for="female">Female</label><br>
                      </div>
                    </div>

                    <div class="form-row">
                      <label for="nationality" class="patient-form-label">Nationality:<span>*</span></label><br>
                      <input type="text" name="nationality" id="nationality" required>
                    </div>

                    <div class="form-row">
                      <label for="district" class="patient-form-label">District:<span>*</span></label><br>
                      <select name="district" id="district" required>
                        <option value="">Select District</option>
                        <option value="Balaka">Balaka</option>
                        <option value="Blantyre">Blantyre</option>
                        <option value="Chikwawa">Chikwawa</option>
                        <option value="Chiradzulu">Chiradzulu</option>
                        <option value="Chitipa">Chitipa</option>
                        <option value="Dedza">Dedza</option>
                        <option value="Dowa">Dowa</option>
                        <option value="Karonga">Karonga</option>
                        <option value="Kasungu">Kasungu</option>
                        <option value="Likoma">Likoma</option>
                        <option value="Lilongwe">Lilongwe</option>
                        <option value="Machinga">Machinga</option>
                        <option value="Mangochi">Mangochi</option>
                        <option value="Mchinji">Mchinji</option>
                        <option value="Mulanje">Mulanje</option>
                        <option value="Mwanza">Mwanza</option>
                        <option value="Mzimba">Mzimba</option>
                        <option value="Neno">Neno</option>
                        <option value="Ntcheu">Ntcheu</option>
                        <option value="Nkhata Bay">Nkhata Bay</option>
                        <option value="Nkhotakota">Nkhotakota</option>
                        <option value="Nsanje">Nsanje</option>
                        <option value="Ntchisi">Ntchisi</option>
                        <option value="Phalombe">Phalombe</option>
                        <option value="Rumphi">Rumphi</option>
                        <option value="Salima">Salima</option>
                        <option value="Thyolo">Thyolo</option>
                        <option value="Zomba">Zomba</option>
                      </select>
                    </div>

                    <div class="form-row">
                      <label for="nationality" class="patient-form-label">Area of Residence:<span>*</span></label><br>
                      <input type="text" name="area" id="area" required>
                    </div>

                    <div class="form-row">
                      <label for="nationality" class="patient-form-label">Phone Number:<span>*</span></label><br>
                      <input type="text" name="phone_number" id="phone_number" required>
                    </div>
                  </div>
                </div>

                <div class="patient-form-half left-form">
                  <div class="form-row">
                    <label for="nationality" class="patient-form-label">Email:<span>*</span></label><br>
                    <input type="text" name="email" id="email" required>
                  </div>

                  <div class="form-row">
                    <label for="married" class="patient-form-label">Married:<span>*</span></label><br>
                    <div class="group">
                      <input type="radio" id="yes" name="married" value="Yes" onclick="toggleSpouseFields()" required>
                      <label for="yes">Yes</label><br>
                    </div>
                    <div class="group">
                      <input type="radio" id="no" name="married" value="No" onclick="toggleSpouseFields()" required>
                      <label for="no">No</label><br>
                    </div>
                  </div>

                  <div id="spouse-fields" style="display: none;">
                    <div class="form-row">
                      <label for="nationality" class="patient-form-label">Spouse's Name:<span>*</span></label><br>
                      <input type="text" name="spouse_name" id="spouse_name" required>
                    </div>

                    <div class="form-row">
                      <label for="nationality" class="patient-form-label">Spouse's Number:<span>*</span></label><br>
                      <input type="text" name="spouse_number" id="spouse_number" required>
                    </div>

                    <div class="form-row">
                      <label for="nationality" class="patient-form-label">Spouse's Email Address:<span>*</span></label><br>
                      <input type="text" name="spouse_email" id="spouse_email" required>
                    </div>

                    <div class="form-row">
                      <label for="nationality" class="patient-form-label">Spouse's Physical Address:<span>*</span></label><br>
                      <input type="text" name="spouse_address" id="spouse_address" required>
                    </div>
                  </div>

                  <div class="form-row">
                    <label for="nationality" class="patient-form-label">Emergency Contact Name:<span>*</span></label><br>
                    <input type="text" name="emergency_name" id="emergency_name" required>
                  </div>

                  <div class="form-row">
                    <label for="nationality" class="patient-form-label">Relationship to Patient:<span>*</span></label><br>
                    <input type="text" name="emergency_relationship" id="emergency_relationship" required>
                  </div>

                  <div class="form-row">
                    <label for="nationality" class="patient-form-label">Emergency Contact Phone Number:<span>*</span></label><br>
                    <input type="text" name="emergency_number" id="emergency_number" required>
                  </div>

                  <button type="submit" class="patient-form-button" style="margin-top:15px; width: 30%; left: 60%; margin-bottom: 20px; position: fixed; bottom: 10px; transform: translateX(-50%);">Submit</button>
                </div>
              </form>
            </div>
            <script>
              document.getElementById("patient-form").addEventListener("submit", function(event) {
                // check if the name field is empty
                var patient_name = document.getElementById("patient_name").value;
                if (/^[a-zA-Z\s]*$/.test(patient_name)) {
                  // do nothing
                } else {
                  alert("Name should only contain letters and spaces.");
                  event.preventDefault();
                  return;
                }

                // get the selected ID type
                var id_type = document.getElementById("id_type").value;

                // check the ID number length based on the selected ID type
                if (id_type === "National ID") {
                  if (document.getElementById("id_number").value.length !== 8) {
                    alert("Please enter a valid ID number. A Nation ID number must contain 8 characters");
                    event.preventDefault();
                    return;
                  }
                } else if (id_type === "Drivers License") {
                  var id_number = document.getElementById("id_number").value;
                  // check if the ID number contains only digits and is 14 characters long
                  if (!/^\d{14}$/.test(id_number)) {
                    alert("Please enter a valid ID number. A Driver's licence ID number must contain 14 digits");
                    event.preventDefault();
                    return;
                  }
                } else if (id_type === "Passport") {
                  if (document.getElementById("id_number").value.length !== 8) {
                    alert("Please enter a valid ID number. A Passport's ID number must contain 8 characters");
                    event.preventDefault();
                    return;
                  }
                } else {
                  alert("Please select a valid ID type.");
                  event.preventDefault();
                  return;
                }

                // get the date of birth value
                var dob = document.getElementById("dob").value;

                // check if the date of birth is not in the future
                if (new Date(dob) > new Date()) {
                  alert("Date of birth cannot be in the future.");
                  event.preventDefault();
                  return;
                }

                // check if the nationality field only allows text inputs without underscores
                if (!/^[a-zA-Z\s]*$/.test(document.getElementById("nationality").value)) {
                  alert("Nationality field should only contain letters and spaces.");
                  event.preventDefault();
                  return;
                }

                // check if the spouse_name field only allows text inputs without underscores
                if (!/^[a-zA-Z\s]*$/.test(document.getElementById("spouse_name").value)) {
                  alert("Spouse Name field should only contain letters and spaces.");
                  event.preventDefault();
                  return;
                }

                // check if the area of residence field only allows text inputs without underscores
                if (!/^[a-zA-Z0-9\s]*$/.test(document.getElementById("area").value)) {
                  alert("Area of residence field should only contain letters, numbers, and spaces.");
                  event.preventDefault();
                  return;
                }

                // check if the phone number field only allows text inputs without underscores
                if (!/^(08|09)[0-9]{8}$/.test(document.getElementById("phone_number").value)) {
                  alert("Phone number field should start with either 09 or 08 and should be 10 digits.");
                  event.preventDefault();
                  return;
                }
                // check if the spouse_address field only allows text inputs without underscores
                if (!/^[a-zA-Z\s0-9]*$/.test(document.getElementById("spouse_address").value)) {
                  alert("Spouse Address field should only contain letters, spaces, and numbers.");
                  event.preventDefault();
                  return;
                }

                // check if the emergency_name field only allows text inputs without underscores
                if (!/^[a-zA-Z\s]*$/.test(document.getElementById("emergency_name").value)) {
                  alert("Emergency Name field should only contain letters and spaces.");
                  event.preventDefault();
                  return;
                }

                // check if the emergency_relationship field only allows text inputs without underscores
                if (!/^[a-zA-Z\s]*$/.test(document.getElementById("emergency_relationship").value)) {
                  alert("Emergency Relationship field should only contain letters and spaces.");
                  event.preventDefault();
                  return;
                }
                if (!/^[\w-]+(\.[\w-]+)*@([\w-]+\.)+[a-zA-Z]{2,7}$/.test(document.getElementById("email").value)) {
                  alert("Please enter a valid email address.");
                  event.preventDefault();
                  return;
                }
                if (!/^[\w-]+(\.[\w-]+)*@([\w-]+\.)+[a-zA-Z]{2,7}$/.test(document.getElementById("spouse_email").value)) {
                  alert("Please enter a valid email address.");
                  event.preventDefault();
                  return;
                }

              });

              function toggleSpouseFields() {
                var marriedYes = document.getElementById('yes').checked;
                var spouseFields = document.getElementById('spouse-fields');

                if (marriedYes) {
                  spouseFields.style.display = 'block';
                } else {
                  spouseFields.style.display = 'none';
                }
              }
            </script>
