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

$id = isset($_GET['id']) ? intval($_GET['id']) : 0;
if ($id <= 0) {
    header("Location: property_details.php?error=Invalid%20ID");
    exit;
}

// Optionally remove photo file
$res = mysqli_query($link, "SELECT photo_path FROM property_details WHERE id=$id");
if ($res && $res->num_rows > 0) {
    $row = mysqli_fetch_assoc($res);
    $photo = $row['photo_path'];
    if ($photo) {
        $full = __DIR__ . '/' . $photo;
        if (is_file($full)) {
            @unlink($full);
        }
    }
}

// Delete row
if (mysqli_query($link, "DELETE FROM property_details WHERE id=$id")) {
    header("Location: property_details.php?success=Deleted");
} else {
    header("Location: property_details.php?error=Delete%20failed");
}
?>