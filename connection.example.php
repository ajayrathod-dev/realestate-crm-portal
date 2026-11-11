<?php
// Example Database Configuration
date_default_timezone_set("Asia/Kolkata");

$DB_SERVER = "localhost";
$DB_NAME   = "property";
$username  = "root";      // Change to your MySQL username
$password  = "";          // Change to your MySQL password

$link = mysqli_connect($DB_SERVER, $username, $password, $DB_NAME);

if ($link === false) {
    die("ERROR: Could not connect to database. " . mysqli_connect_error());
}
?>
