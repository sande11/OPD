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

// Check if the form for entering the reset token is submitted
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['reset_token'])) {
    // Retrieve reset token from the form
    $reset_token_entered = $_POST['reset_token'];

    // Sanitize the reset token to prevent SQL injection
    $reset_token_entered = mysqli_real_escape_string($conn, $reset_token_entered);

    // Query to check if the entered reset token matches the one in the database for the specific user
    $check_token_sql = "SELECT * FROM users WHERE reset_token = '$reset_token_entered'";
    $result = mysqli_query($conn, $check_token_sql);

    // Check if the reset token matches
    if (mysqli_num_rows($result) == 1) {
        // If reset token matches, generate a new password
        $row = mysqli_fetch_assoc($result);
        $phone_number = $row['phone_number'];
        $new_password = sprintf("%04d", rand(0, 9999));

        // Hash the new password before storing it in the database
        $hashed_password = password_hash($new_password, PASSWORD_DEFAULT);

        // Update the user's password in the database and clear the reset token
        $update_password_sql = "UPDATE users SET hashed_password = '$hashed_password', reset_token = NULL WHERE reset_token = '$reset_token_entered'";
        mysqli_query($conn, $update_password_sql);

        sendPasswordSMS($phone_number, "Your new password is: $new_password");

        echo "<script>alert('Your password has been reset. Please check your messages for the new password.')</script>";
        echo "<script>window.location.href =  'Auth.php';</script>";
    } else {
        // If reset token does not match or user ID is incorrect
        echo "Invalid reset token or user ID. Please try again.";
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
                <label for="psw" class="form-words"><b>Reset Token</b></label>
                <input type="text" placeholder="Enter the reset token you received" name="reset_token" required>

                <button type="submit" name="submit">Submit</button>
                <a href="Auth.php">
                    <p class="form-words">Login</p>
                </a>
            </div>
        </form>
    </div>
</body>

</html>
