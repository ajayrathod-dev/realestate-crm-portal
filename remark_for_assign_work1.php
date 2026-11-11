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
    $remark= $_POST['remark'];
    $det = $_POST['det'];
    

   
                $sql = "INSERT INTO `remark_for_my_work`(`assign_work_id`, `remark`, `by`, `date`)
                 VALUES ('$id','$remark','$u_uname','$det')";
       
            if (mysqli_query($link, $sql)) {
              
             
            $p="Data Submitted Successfully";
             header("Location:remark_for_assign_work.php?success=$p&id=$id");

            
            }
            else
            {
                $p="Data Not Submitted";
              header("Location:remark_for_assign_work.php?error=$p");
             }
?>
