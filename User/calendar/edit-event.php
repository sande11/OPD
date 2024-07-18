<?php
require_once "db.php";

$id = $_POST['id'];
$title = $_POST['title'];
$start = $_POST['start'];
$end = $_POST['end'];
$subject = $_POST['subject'];

$sqlUpdate = "UPDATE appointments SET title='" . $title . "',start='" . $start . "',end='" . $end . "',subject='" . $subject . "' WHERE id=" . $id;
mysqli_query($conn, $sqlUpdate);
mysqli_close($conn);

?>
