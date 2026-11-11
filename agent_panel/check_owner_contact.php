<?php
require_once 'connection.php';

if (isset($_POST['owner_contact'])) {
    $owner_contact = trim($_POST['owner_contact']);
    
    // Basic sanitization
    $owner_contact = mysqli_real_escape_string($link, $owner_contact);

    $sql = "SELECT id FROM properties WHERE owner_contact = '$owner_contact' LIMIT 1";
    $result = mysqli_query($link, $sql);

    if ($result && mysqli_num_rows($result) > 0) {
        echo 'exists';
    } else {
        echo 'not_exists';
    }
}
?>
