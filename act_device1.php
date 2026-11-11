<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

session_start();
include("connection.php");




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




$client_name=mysqli_real_escape_string($link, $_POST['client_name']);
$start_date=mysqli_real_escape_string($link, $_POST['start_date']);
$end_date=mysqli_real_escape_string($link, $_POST['end_date']);
$device_id=mysqli_real_escape_string($link, $_POST['device_id']);
$amount=mysqli_real_escape_string($link, $_POST['amount']);


             $sql = "INSERT INTO `activate_status`(`client_id`,`sub_start_date`,`sub_end_date`,`device_id`,`amount`,`by`)
             VALUES ('$client_name','$start_date','$end_date','$device_id','$amount','$u_uname')";
       
            if (mysqli_query($link, $sql)) {
              
            
         
            $p="Device Activated Successfully";
             header("Location:act_device.php?success=$p");

            
            }
            else
            {
                $p="Device Not Activated";
              header("Location:act_device.php?error=$p");
             }
    


?>






           
        
      
