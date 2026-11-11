<?php
session_start();
include 'connection.php';

$id = isset($_GET['id']) ? (int)$_GET['id'] : 0;
if ($id > 0) {
$sql = "DELETE FROM property_advarticement0 WHERE id=$id";
    if (mysqli_query($link, $sql)) {
        header('Location: property_advarticement0.php?msg=Deleted');
        exit;
    } else {
        $err = urlencode('Error: '.mysqli_error($link));
        header('Location: property_advarticement0.php?msg='.$err);
        exit;
    }
}

header('Location: property_advarticement0.php?msg=Invalid ID');
exit;
?>