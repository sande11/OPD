<?php
    require_once "db.php";

    session_start(); 

    $userName = $_SESSION['name'];

    $json = array();
    $sqlQuery = "SELECT * FROM appointments WHERE subject = '$userName' ORDER BY id";

    $result = mysqli_query($conn, $sqlQuery);
    $eventArray = array();
    while ($row = mysqli_fetch_assoc($result)) {
        array_push($eventArray, $row);
    }
    mysqli_free_result($result);

    mysqli_close($conn);
    echo json_encode($eventArray);
?>
