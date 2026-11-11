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
    $rejection_reason = isset($_POST['rejection_reason']) ? trim($_POST['rejection_reason']) : '';
    $ok = false;
    $hasReason = false;
    $hasModeration = false;
    $r1 = mysqli_query($link, "SHOW COLUMNS FROM properties LIKE 'rejection_reason'");
    if ($r1 && mysqli_num_rows($r1) > 0) { $hasReason = true; }
    $r2 = mysqli_query($link, "SHOW COLUMNS FROM properties LIKE 'moderation_status'");
    if ($r2 && mysqli_num_rows($r2) > 0) { $hasModeration = true; }

    if ($hasModeration && $hasReason) {
        $stmt = mysqli_prepare($link, 'UPDATE properties SET moderation_status = "rejected", rejection_reason = ? WHERE property_id = ?');
        if ($stmt) {
            mysqli_stmt_bind_param($stmt, 'ss', $rejection_reason, $propertyId);
            $ok = mysqli_stmt_execute($stmt);
            mysqli_stmt_close($stmt);
        }
    } else if ($hasModeration) {
        $stmt = mysqli_prepare($link, 'UPDATE properties SET moderation_status = "rejected" WHERE property_id = ?');
        if ($stmt) {
            mysqli_stmt_bind_param($stmt, 's', $propertyId);
            $ok = mysqli_stmt_execute($stmt);
            mysqli_stmt_close($stmt);
        }
    } else {
        $stmt = mysqli_prepare($link, 'UPDATE properties SET status = "rejected" WHERE property_id = ?');
        if ($stmt) {
            mysqli_stmt_bind_param($stmt, 's', $propertyId);
            $ok = mysqli_stmt_execute($stmt);
            mysqli_stmt_close($stmt);
        }
    }

    if ($ok) {
        header('Location: property_staff.php?success=' . urlencode('Property rejected') . '&log=' . urlencode('property_id=' . $propertyId));
    } else {
        header('Location: property_staff.php?error=' . urlencode('Failed to reject property: ' . mysqli_error($link)) . '&log=' . urlencode('property_id=' . $propertyId));
    }
    exit;
}

if ($action === 'Approve') {
    $ok = false;
    $hasModeration = false;
    $r2 = mysqli_query($link, "SHOW COLUMNS FROM properties LIKE 'moderation_status'");
    if ($r2 && mysqli_num_rows($r2) > 0) { $hasModeration = true; }

    if ($hasModeration) {
        $stmt = mysqli_prepare($link, 'UPDATE properties SET moderation_status = "approved" WHERE property_id = ?');
        if ($stmt) {
            mysqli_stmt_bind_param($stmt, 's', $propertyId);
            $ok = mysqli_stmt_execute($stmt);
            mysqli_stmt_close($stmt);
        }
    } else {
        $stmt = mysqli_prepare($link, 'UPDATE properties SET status = "approved" WHERE property_id = ?');
        if ($stmt) {
            mysqli_stmt_bind_param($stmt, 's', $propertyId);
            $ok = mysqli_stmt_execute($stmt);
            mysqli_stmt_close($stmt);
        }
    }

    if ($ok) {
        header('Location: property_staff.php?success=' . urlencode('Property approved') . '&log=' . urlencode('property_id=' . $propertyId));
    } else {
        header('Location: property_staff.php?error=' . urlencode('Failed to approve property: ' . mysqli_error($link)) . '&log=' . urlencode('property_id=' . $propertyId));
    }
    exit;
}

// Fallback
header('Location: property_staff.php?error=' . urlencode('Unhandled action'));
exit;
?>

