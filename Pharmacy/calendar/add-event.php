<?php
require_once "db.php";

$title = $_POST['title'];
$start = $_POST['start'];
$end = $_POST['end'];
$subject = $_POST['subject'];
$patientName = $_POST['patientName'];
$details = $_POST['details'];
$medication_name = $_POST['medication_name'];
$dosage = $_POST['dosage'];
$contact_info = $_POST['contact_info'];

$stmt = $conn->prepare("INSERT INTO appointments (title, start, end, subject, patientName, details, medication_name, dosage, contact_info) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?)");
$stmt->bind_param('sssssssss', $title, $start, $end, $subject, $patientName, $details, $medication_name, $dosage, $contact_info);
$result = $stmt->execute();

if ($result) {
    echo "Event added successfully";
} else {
    echo "Error: " . $conn->error;
}

$stmt->close();
$conn->close();
?>
