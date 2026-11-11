<?php
date_default_timezone_set("Asia/Kolkata");

$DB_SERVER = "localhost";
$DB_NAME   = "property";
$DB_USER   = "property";
$DB_PASS   = "property@878889";

$link = mysqli_connect($DB_SERVER, $DB_USER, $DB_PASS, $DB_NAME);

if (!$link) {
    die("Connection failed: " . mysqli_connect_error());
}

// connection successful
?>
