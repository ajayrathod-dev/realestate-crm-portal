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

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $imageData = $_POST['selfie'];
    $latitude = $_POST['latitude'];
    $longitude = $_POST['longitude'];
    $datetime = $_POST['datetime'];
    $desc=$_POST['desc'];

    // API URL
$url = "https://nominatim.openstreetmap.org/reverse?format=json&lat={$latitude}&lon={$longitude}&zoom=18&addressdetails=1";

// cURL request
$ch = curl_init();
curl_setopt($ch, CURLOPT_URL, $url);
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
curl_setopt($ch, CURLOPT_USERAGENT, 'PHP Reverse Geocoding'); // Required by Nominatim
$response = curl_exec($ch);
curl_close($ch);

// Decode JSON
$data = json_decode($response, true);

if (isset($data['display_name'])) {
     $ad=$data['display_name'];
} else {
     $ad= "Address not found.";
}

    $img_binary = base64_decode(preg_replace('#^data:image/\w+;base64,#i', '', $imageData));
    
     if($desc=="In")
            {
                mysqli_query($link,"UPDATE admin SET present_staff='In' WHERE user_id='$u_uname'");
            }
            else
            {

              mysqli_query($link,"UPDATE admin SET present_staff='Out' WHERE user_id='$u_uname'");

            }

    $stmt = $link->prepare("INSERT INTO selfie_log (image, latitude, longitude, datetime,`by`,description,`address`) VALUES (?, ?, ?, ?,?,?,?)");
    $stmt->bind_param("sssssss", $img_binary, $latitude, $longitude, $datetime,$u_uname,$desc,$ad);

    if ($stmt->execute()) {
        
         $p="Data Submitted Successfully";
             header("Location:selfie_log.php?success=$p");
       
    } else {
          $p="Data Not Submitted";
              header("Location:selfie_log.php?error=$p");
        
            }
    $stmt->close();
}
?>
