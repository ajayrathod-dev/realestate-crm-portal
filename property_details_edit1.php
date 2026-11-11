<?php
session_start();
include("connection.php");

// Auth
if (isset($_COOKIE['uuid'])) {
    $u_uname = mysqli_real_escape_string($link, $_COOKIE['uuid']);
} else {
    $u_uname = '';
}
$sql4 = "SELECT * FROM ods WHERE uuid='$u_uname' AND status='verified'";
$result4 = mysqli_query($link, $sql4);
if (!($result4 && $result4->num_rows > 0)) {
    header("location:logout.php");
    exit;
}

$id = intval($_POST['id'] ?? 0);
if ($id <= 0) {
    header("Location: property_details.php?error=Invalid%20ID");
    exit;
}

$property_id   = mysqli_real_escape_string($link, $_POST['property_id'] ?? '');
$details       = mysqli_real_escape_string($link, $_POST['details'] ?? '');
$price         = mysqli_real_escape_string($link, $_POST['price'] ?? '');
$address       = mysqli_real_escape_string($link, $_POST['address'] ?? '');
$owner_name    = mysqli_real_escape_string($link, $_POST['owner_name'] ?? '');
$owner_contact = mysqli_real_escape_string($link, $_POST['owner_contact'] ?? '');
$broker        = mysqli_real_escape_string($link, $_POST['broker'] ?? '');
$website       = mysqli_real_escape_string($link, $_POST['website'] ?? '');
$video_link    = mysqli_real_escape_string($link, $_POST['video_link'] ?? '');
$promotion     = mysqli_real_escape_string($link, $_POST['promotion'] ?? '');
$size          = mysqli_real_escape_string($link, $_POST['size'] ?? '');
$existing_photo= mysqli_real_escape_string($link, $_POST['existing_photo'] ?? '');

$photo_path = $existing_photo;
if (isset($_FILES['photo']) && isset($_FILES['photo']['tmp_name']) && is_uploaded_file($_FILES['photo']['tmp_name'])) {
    $ext = pathinfo($_FILES['photo']['name'], PATHINFO_EXTENSION);
    $safe_ext = preg_replace('/[^a-zA-Z0-9]/', '', $ext);
    $new_name = date('YmdHis') . '_' . rand(1000, 9999) . ($safe_ext ? ('.' . $safe_ext) : '');
    $target_dir = __DIR__ . '/images/';
    if (!is_dir($target_dir)) {
        @mkdir($target_dir, 0755, true);
    }
    $target_path = $target_dir . $new_name;
    if (move_uploaded_file($_FILES['photo']['tmp_name'], $target_path)) {
        $photo_path = 'images/' . $new_name;
    }
}

$sql = "UPDATE `property_details` SET 
    `property_id`='$property_id',
    `details`='$details',
    `price`='$price',
    `address`='$address',
    `owner_name`='$owner_name',
    `owner_contact`='$owner_contact',
    `broker`='$broker',
    `website`='$website',
    `photo_path`='$photo_path',
    `video_link`='$video_link',
    `promotion`='$promotion',
    `size`='$size'
    WHERE `id`=$id";

if (mysqli_query($link, $sql)) {
    header("Location: property_details.php?success=Updated");
} else {
    header("Location: property_details.php?error=Update%20failed");
}
?>