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


    $j_k = $_POST['j_k'];
    $c_name= $_POST['c_name'];
    $rec = $_POST['rec'];
    $address = $_POST['address'];
    $desc = $_POST['desc'];
    $send = $_POST['send'];
    $shera = $_POST['shera'];
    $of = $_POST['of'];
    $det = $_POST['det'];
    $branch = $_POST['branch'];
   
                $sql = "INSERT INTO `javak_reg`(`javak_id`, `rec_name`, `rec`, `address`, `desc`, `send_type`, `shera`, `outword_from`, `by`, `date`,`branch`)
                 VALUES ('$j_k','$c_name','$rec','$address','$desc','$send','$shera','$of','$u_uname','$det','$branch')";
       
            if (mysqli_query($link, $sql)) {
              
             
            $p="Data Submitted Successfully";
             header("Location:javak.php?success=$p");

            
            }
            else
            {
                $p="Data Not Submitted";
              header("Location:javak.php?error=$p");
             }
?>
