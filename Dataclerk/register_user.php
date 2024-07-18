<?php
include_once '../includes/conn.php';

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

$department = $_POST["dept"];
$id_number = $_POST["id_number"];
$name = $_POST["name"];
$position = $_POST["position"];
$email = $_POST["email"];
$phone_number = $_POST["phone_number"];
$gender = $_POST["gender"];

// Generate password
$password = sprintf("%04d", rand(0, 9999));

// Prepare the message
$message = "You have been registered to 265 OutPatient Management System. Your password is $password.";

// Send SMS to the patient
sendPasswordSMS($phone_number, $message);

// Hash the password
$hashed_password = password_hash($password, PASSWORD_DEFAULT);

// check if user already exists
$check_user = "SELECT * FROM users WHERE id_number = '$id_number' AND name = '$name'";
$result = mysqli_query($conn, $check_user);

if (mysqli_num_rows($result) > 0) {
    // user already exists
    $alert = "User with the same ID number and name already exists.";
    echo "<script>alert('$alert'); window.location.href='staff.php';</script>";
} else {
    // user does not exist, insert new user
    $sql = "INSERT INTO users (id_number, name, position, email, phone_number, department, gender, hashed_password)
    VALUES ('$id_number', '$name', '$position', '$email', '$phone_number', '$department', '$gender', '$hashed_password')";

    if (mysqli_query($conn, $sql)) {
        $subject = "Your New Password";
        $message = "Your new password is: $password";

        // $send_mail = sendMail($email, $subject, $message);
        $alert = "User Registered Successfully";
        echo "<script>alert('$alert'); window.location.href='staff.php';</script>";
    } else {
        echo "ERROR:sorry $sql."
            . mysqli_error($conn);
    }
}

mysqli_close($conn);
// header('Location: staff.php');
