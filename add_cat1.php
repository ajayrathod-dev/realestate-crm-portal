<?php

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
$client_name=mysqli_real_escape_string($link, $_POST['name']);
$mobile=mysqli_real_escape_string($link, $_POST['mobile']);
$desg=mysqli_real_escape_string($link, $_POST['desg']);
$pwd=mysqli_real_escape_string($link, $_POST['pwd']);



             $sql = "INSERT INTO `admin`(`emp_name`, `user_id`, `designation`,`password`, `by`)
             VALUES ('$client_name','$mobile','$desg','$pwd','$u_uname')";
       
            if (mysqli_query($link, $sql)) {
              
            
         
            $p="Staff Added Successfully";
             header("Location:add_cat.php?success=$p");

            
            }
            else
            {
                $p="Client Not Created";
              header("Location:add_cat.php?error=$p");
             }
    









           
        
      
