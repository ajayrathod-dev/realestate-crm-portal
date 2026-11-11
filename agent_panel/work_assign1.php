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


    $work_reg_by= $_POST['work_reg_by'];
    $work_assign_to= $_POST['work_assign_to'];
    $work_description = $_POST['work_description'];
    $edc = $_POST['edc'];
    $det = $_POST['det'];
    $win = $_POST['win'];
    $about_work=$_POST['work_about'];
                $sql = "INSERT INTO `work_assign`(`work_registered_by`, `work_assigned_to`, `work_description`, `expected_date_of_completion`, `by`, `date`,`work_in_no`,`about_work`)
                 VALUES ('$work_reg_by','$work_assign_to','$work_description','$edc','$u_uname','$det','$win','$about_work')";
       
            if (mysqli_query($link, $sql)) {
              
             
            $p="Data Submitted Successfully";
             header("Location:work_assign.php?success=$p");

            
            }
            else
            {
                $p="Data Not Submitted";
              header("Location:work_assign.php?error=$p");
             }
?>
