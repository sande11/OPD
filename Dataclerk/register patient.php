<?php
// Include the connection file
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

// Check if form data is submitted
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Retrieve form data
    $id_type = $_POST["id_type"];
    $id_number = $_POST["id_number"];
    $name = $_POST["patient_name"];
    $dob = $_POST["dob"];
    $gender = $_POST["gender"];
    $nationality = $_POST["nationality"];
    $district = $_POST["district"];
    $area = $_POST["area"];
    $phone_number = $_POST["phone_number"];
    $email = $_POST["email"];
    $married = $_POST["married"];
    $spouse_name = $_POST["spouse_name"];
    $spouse_number = $_POST["spouse_number"];
    $spouse_email = $_POST["spouse_email"];
    $spouse_physical_address = $_POST["spouse_address"];
    $emergency_contact_name = $_POST["emergency_name"];
    $relationship_to_patient = $_POST["emergency_relationship"];
    $emergency_contact_phone_number = $_POST["emergency_number"];

    // Generate password
    $password = sprintf("%04d", rand(0, 9999));

    // Prepare the message
    $message = "You have been registered to 265 OutPatient Management System. Your password for our Android Health Passport app is $password.";

    // Send SMS to the patient
    sendPasswordSMS($phone_number, $message);

    // Hash the password
    $hashed_password = password_hash($password, PASSWORD_DEFAULT);

    // SQL query to insert data into the database
    $sql = "INSERT INTO patient_records (id_type, id_number, name, dob, gender, nationality, district, area, phone_number, email, married, spouse_name, spouse_number, spouse_email, spouse_physical_address, emergency_contact_name, relationship_to_patient, emergency_contact_phone_number, date_joined, hashed_password) 
            VALUES ('$id_type', '$id_number', '$name', '$dob', '$gender', '$nationality', '$district', '$area', '$phone_number', '$email', '$married', '$spouse_name', '$spouse_number', '$spouse_email', '$spouse_physical_address', '$emergency_contact_name', '$relationship_to_patient', '$emergency_contact_phone_number', CURRENT_DATE, '$hashed_password')";

    // Perform the query
    if (mysqli_query($conn, $sql)) {
        $alert = "Patient Registered Successfully";
    } else {
        $alert = "Error: " . mysqli_error($conn);
    }

    // Display the alert message and then redirect to the dataclerk.php page
    echo "<script>alert('$alert'); window.location.href = 'dataclerk.php';</script>";

    // Close the database connection
    mysqli_close($conn);
} else {
    // Redirect if accessed directly without form submission
    header("Location: dataclerk.php");
    exit();
}
?>
