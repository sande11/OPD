<?php
require_once "db.php";

$title = isset($_POST['title']) ? $_POST['title'] : "";
$start = isset($_POST['start']) ? $_POST['start'] : "";
$end = isset($_POST['end']) ? $_POST['end'] : "";
$subject = isset($_POST['subject']) ? $_POST['subject'] : "";

$sqlInsert = "INSERT INTO appointments (title,start,end,subject) VALUES ('".$title."','".$start."','".$end ."','".$subject."')";

$result = mysqli_query($conn, $sqlInsert);

if (! $result) {
    $result = mysqli_error($conn);
}
?>
