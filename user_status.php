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

$lev = $_POST['lev'];
    $id= $_POST['id'];
   
   
   
                $sql = "UPDATE `upload`SET  `status`='$lev' WHERE id='$id'";
       
            if (mysqli_query($link, $sql)) {
              
             
            $p="Status Changed Successfully";
             header("Location:spaff_add_cus.php?success=$p");

            
            }
            else
            {
                $p="Status Not Changed";
              header("Location:spaff_add_cus.php?error=$p");
             }