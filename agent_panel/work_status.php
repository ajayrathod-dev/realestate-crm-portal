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


    $id= $_POST['id'];
    $status= $_POST['status'];
    
            $sql = "UPDATE work_assign SET status='$status' WHERE id='$id'";
            if (mysqli_query($link, $sql)) {
              
             
            $p="Data Submitted Successfully";
             header("Location:my_work.php?success=$p");

            
            }
            else
            {
                $p="Data Not Submitted";
              header("Location:my_work.php.php?error=$p");
             }
?>
