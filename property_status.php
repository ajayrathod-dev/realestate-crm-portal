<?php
require_once 'connection.php';

$action = isset($_GET['action']) ? $_GET['action'] : '';
$id = isset($_GET['id']) ? $_GET['id'] : '';

if ($id === '' || !in_array($action, ['verify','approve','reject'])) {
    header('Location: property_add.php?error=' . urlencode('Invalid action or ID'));
    exit;
}

$map = [
    'verify' => 'verified',
    'approve' => 'approved',
    'reject' => 'rejected',
];
$new_status = $map[$action];

$stmt = mysqli_prepare($link, "UPDATE properties SET moderation_status = ? WHERE property_id = ?");
if ($stmt) {
    mysqli_stmt_bind_param($stmt, 'ss', $new_status, $id);
    $ok = mysqli_stmt_execute($stmt);
    if ($ok) {
        header('Location: property_add.php?success=' . urlencode('Updated status to ' . $new_status));
        exit;
    }
}

$err = 'Update failed: ' . mysqli_error($link);
header('Location: property_add.php?error=' . urlencode($err));
exit;
?>

