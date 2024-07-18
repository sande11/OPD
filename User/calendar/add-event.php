<?php
require_once "db.php";

$title = $_POST['title'];
$start = $_POST['start'];
$end = $_POST['end'];
$subject = $_POST['subject'];
$patientName = $_POST['patientName'];
$details = $_POST['details'];

$stmt = $conn->prepare("INSERT INTO appointments (title, start, end, subject, patientName, details) VALUES (?, ?, ?, ?, ?, ?)");
$stmt->bind_param('ssssss', $title, $start, $end, $subject, $patientName, $details);
$result = $stmt->execute();

if ($result) {
    echo "Event added successfully";
} else {
    echo "Error: " . $conn->error;
}

$stmt->close();
$conn->close();
