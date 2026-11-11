<?php
session_start();

include("connection.php");
date_default_timezone_set('Asia/Kolkata');
$today = date("Y-m-d h:i:s");

if (isset($_COOKIE['uuid'])) {

	$u_uname = mysqli_real_escape_string($link, $_COOKIE['uuid']);

} else {

	$u_uname = '';

}
 $sql4 = "SELECT * FROM ods WHERE uuid='$u_uname' AND status='verified'";

$result4 = mysqli_query($link, $sql4);

if ($result4->num_rows > 0) {

	while ($row4 = $result4->fetch_assoc()) {

		$u_uname = mysqli_real_escape_string($link, $row4['user_id']);

		$br_id = mysqli_real_escape_string($link, $row4['user_id']);

	}

} else {

	header("location:logout.php");

	exit;

}


    $v_name = $_POST['v_name'];
    $address= $_POST['address'];
    $mob = $_POST['mob'];
    $wm = $_POST['wm'];
    $res = $_POST['res'];
    $det = $_POST['det'];
    $branch = $_POST['branch'];
    $sql = "INSERT INTO add_visitors(name, mobile_no, address, whoose_meet, reasion, `by`,`date`,`branch`)
                 VALUES ('$v_name','$address','$mob','$wm','$res','$u_uname','$det','$branch')";
       
            if (mysqli_query($link, $sql)) {
              
             
            $p="Data Submitted Successfully";
             header("Location:add_visitor.php?success=$p");

            
            }
            else
            {
                $p="Data Not Submitted";
              header("Location:add_visitor.php?error=$p");
             }
?>
