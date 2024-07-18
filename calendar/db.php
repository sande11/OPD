<?php
$conn = mysqli_connect("localhost","root","","265_opd") ;

if (!$conn)
{
echo "Failed to connect to MySQL: " . mysqli_connect_error();
}
