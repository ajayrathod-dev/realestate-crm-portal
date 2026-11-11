<?php
session_start();
require_once 'connection.php';

// Basic validation
if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    header('Location: property_staff.php?error=' . urlencode('Invalid request'));
    exit;
}

$propertyId = isset($_POST['property_id']) ? trim($_POST['property_id']) : '';
$action = isset($_POST['status']) ? trim($_POST['status']) : '';

if ($propertyId === '' || ($action !== 'Approve' && $action !== 'Reject')) {
    header('Location: property_staff.php?error=' . urlencode('Missing or invalid parameters'));
    exit;
}

// Perform action
if ($action === 'Reject') {
    // Delete the record
    $stmt = mysqli_prepare($link, 'DELETE FROM properties WHERE property_id = ?');
    mysqli_stmt_bind_param($stmt, 's', $propertyId);
    $ok = mysqli_stmt_execute($stmt);
    $affected = mysqli_stmt_affected_rows($stmt);
    mysqli_stmt_close($stmt);

    if ($ok && $affected > 0) {
        header('Location: property_staff.php?success=' . urlencode('Property rejected and deleted') . '&log=' . urlencode('property_id=' . $propertyId));
    } else {
        header('Location: property_staff.php?error=' . urlencode('Failed to delete property') . '&log=' . urlencode('property_id=' . $propertyId));
    }
    exit;
}

if ($action === 'Approve') {
    // Update moderation_status to approved
    $stmt = mysqli_prepare($link, 'UPDATE properties SET moderation_status = "approved" WHERE property_id = ?');
    mysqli_stmt_bind_param($stmt, 's', $propertyId);
    $ok = mysqli_stmt_execute($stmt);
    $affected = mysqli_stmt_affected_rows($stmt);
    mysqli_stmt_close($stmt);

    if ($ok && $affected > 0) {
        header('Location: property_staff.php?success=' . urlencode('Property approved') . '&log=' . urlencode('property_id=' . $propertyId));
    } else {
        header('Location: property_staff.php?error=' . urlencode('Failed to approve property') . '&log=' . urlencode('property_id=' . $propertyId));
    }
    exit;
}

// Fallback
header('Location: property_staff.php?error=' . urlencode('Unhandled action'));
exit;
?>

