<?php

include_once 'includes/conn.php';

// function for sending SMSs
function sendPasswordSMS($phone, $message)
{
  $curl = curl_init();

  curl_setopt_array($curl, array(
    CURLOPT_URL => 'https://telcomw.com/api-v2/send',
    CURLOPT_RETURNTRANSFER => true,
    CURLOPT_ENCODING => '',
    CURLOPT_MAXREDIRS => 10,
    CURLOPT_TIMEOUT => 0,
    CURLOPT_FOLLOWLOCATION => true,
    CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
    CURLOPT_CUSTOMREQUEST => 'POST',
    CURLOPT_POSTFIELDS => array(
      'api_key' => 'b1Xvj5CWS78RWDP3HZBl ',
      'password' => 'God1stGod',
      'text' => $message,
      'numbers' => $phone,
      'from' => 'WGIT'
    ),
  ));

  $response = curl_exec($curl);
  curl_close($curl);
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
  // Retrieve phone number and staff ID from the form
  $phone_number = $_POST['phone_number'];
  $staff_id = $_POST['staff_id'];

  // Check if phone number and staff ID are provided
  if (!empty($phone_number) && !empty($staff_id)) {

    // Query to check if the provided phone number and staff ID exist in the users table
    $sql = "SELECT * FROM users WHERE phone_number = '$phone_number' AND id_number = '$staff_id'";
    $result = mysqli_query($conn, $sql);

    // Check if the user exists
    if (mysqli_num_rows($result) == 1) {
      // Get the user's ID from the result
      $row = mysqli_fetch_assoc($result);
      $user_id = $row['user_id'];

      // Generate a reset token
      $token = sprintf("%04d", rand(0, 9999));

      // Store the reset token in the database using user_id
      $update_token_sql = "UPDATE users SET reset_token = '$token' WHERE user_id = '$user_id'";
      mysqli_query($conn, $update_token_sql);

      // Prepare the message
      $message = "Your reset token is: $token";

      // Send SMS to the user
      sendPasswordSMS($phone_number, $message);

      // Close the database connection
      mysqli_close($conn);

      // Display a success message
      echo "<script>alert('A reset token has been sent to your phone number. Please check your messages.')</script>";

      // Redirect to the reset password page
      echo "<script>window.location.href =  'reset_password.php';</script>";
    } else {
      // If the user doesn't exist with the provided phone number and staff ID
      echo "<script>alert('Invalid phone number or staff ID. Please try again.')</script>";
    }
  } else {
    // If phone number or staff ID is not provided
    echo "<script>alert('Please provide both phone number and staff ID.')</script>";
  }
}

// Function to send SMS
?>

<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>265 OutPatient</title>
  <link rel="stylesheet" type="text/css" href="style/login.css">
</head>

<body style="width: 100%; height:auto;">
  <div class="overlay">
    <form method="post">
      <div class="imgcontainer">
        <img src="Assets/cardiogram.png" alt="Avatar" class="avatar">
        <p class="imgwords" style="font-size: 25px;"><span style="font-style: italic; color: #f0a732">265</span>OPMS</p>
      </div>

      <div class="container">
        <label for="psw" class="form-words"><b>Phone Number</b></label>
        <input type="text" placeholder="Enter the phone number used for registration" name="phone_number" required>

        <label for="psw" class="form-words"><b>Staff ID</b></label>
        <input type="text" placeholder="Enter Your staff ID Number" name="staff_id" required>

        <button type="submit" name="submit">Submit</button>
        <a href="Auth.php">
          <p class="form-words">Login</p>
        </a>
      </div>
    </form>
  </div>
  <script>
    document.addEventListener("DOMContentLoaded", function() {
      document.querySelector("form").addEventListener("submit", function(event) {
        var phoneNumberInput = document.querySelector("input[name='phone_number']");
        var phoneNumber = phoneNumberInput.value.trim();
        var phoneNumberRegex = /^(08|09)\d{8}$/;

        if (!phoneNumberRegex.test(phoneNumber)) {
          alert("Phone number must be 10 digits starting with 08 or 09");
          event.preventDefault(); // Prevent form submission
        }
      });
    });
  </script>
</body>

</html>
