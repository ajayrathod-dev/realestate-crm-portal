<?php
require_once 'connection.php';
session_start();

function ensure_column_exists($link, $table, $column, $definition) {
    $table = mysqli_real_escape_string($link, $table);
    $column = mysqli_real_escape_string($link, $column);
    $checkSql = "SELECT COLUMN_NAME FROM INFORMATION_SCHEMA.COLUMNS WHERE TABLE_SCHEMA = DATABASE() AND TABLE_NAME = '$table' AND COLUMN_NAME = '$column' LIMIT 1";
    $res = mysqli_query($link, $checkSql);
    if (!$res || mysqli_num_rows($res) === 0) {
        @mysqli_query($link, "ALTER TABLE `$table` ADD COLUMN `$column` $definition");
    }
}

function prefix_for_type($type) {
    switch ($type) {
        case 'Agriculture': return 'AGRI';
        case 'Plot': return 'PLT';
        case 'Flat': return 'FLT';
        case 'Bungalow': return 'BGL';
        case 'Commercial': return 'CMR';
        case 'Residential Rent': return 'RRP';
        case 'Commercial Rent': return 'RCML';
        default: return '';
    }
}

function next_id_for_type($link, $type) {
    $prefix = prefix_for_type($type);
    if ($prefix === '') return '';
    $sql = "SELECT property_id FROM properties WHERE property_id LIKE '" . mysqli_real_escape_string($link, $prefix) . "%' ORDER BY LENGTH(property_id) DESC, property_id DESC LIMIT 1";
    $res = mysqli_query($link, $sql);
    $lastNum = 0;
    if ($res && mysqli_num_rows($res) > 0) {
        $row = mysqli_fetch_assoc($res);
        $last = $row['property_id'];
        if (preg_match('/^' . preg_quote($prefix, '/') . '([0-9]+)$/', $last, $m)) {
            $lastNum = intval($m[1]);
        }
    }
    $next = max(1001, $lastNum + 1);
    return $prefix . (string)$next;
}

// Collect POST
$type = $_POST['type'] ?? '';
$id_mode = $_POST['id_mode'] ?? 'new';
$property_id = trim($_POST['property_id'] ?? '');
$created_by = $_POST['created_by'] ?? (isset($_SESSION['uname']) ? $_SESSION['uname'] : 'staff');

// Units and values
$unit_1 = $_POST['unit_1'] ?? null;
$value_1 = $_POST['value_1'] ?? null;
$unit_2 = $_POST['unit_2'] ?? null;
$value_2 = $_POST['value_2'] ?? null;

// Address
$village = $_POST['village'] ?? null;
$taluka = $_POST['taluka'] ?? null;
$district = $_POST['district'] ?? null;
$landmark = $_POST['landmark'] ?? null;
// Fields REMOVED from form: full_address, promotion_invoice_generated, promotion_date, main_photo_index
// We will just set them to default/null values to keep DB happy if columns still exist
$full_address = ''; // REMOVED
$google_map_link = $_POST['google_map_link'] ?? null;

// Rates
$rate_unit = $_POST['rate_unit'] ?? null;
$rate_value = $_POST['rate_value'] ?? null;
$price_option = $_POST['price_option'] ?? null;
$total_rupees_amount = isset($_POST['total_rupees_amount']) ? $_POST['total_rupees_amount'] : null;

// Owner
$owner_name = $_POST['owner_name'] ?? '';
$owner_contact = $_POST['owner_contact'] ?? '';
$alt_contact = $_POST['alt_contact'] ?? null;
$is_direct_owner = isset($_POST['is_direct_owner']) ? intval($_POST['is_direct_owner']) : 0;

// Promotion & Invoice - REMOVED from form, setting defaults
$promotion_tag = 'New Arrival'; // Default
$promotion_date = NULL; // REMOVED
$promotion_invoice_generated = 0; // REMOVED

// Media
// Main Photo Index - REMOVED from form, defaulting to 1
$main_photo_index = 1;
$video_link = $_POST['video_link'] ?? null;
$location_note = $_POST['location_note'] ?? null;

$action = $_POST['action'] ?? 'draft';
$status = ($action === 'publish') ? 'published' : 'draft';
$moderation_status = 'pending';

// Validate minimal required
if ($type === '' || $owner_name === '' || $owner_contact === '') {
    $err = 'Type, Owner Name, and Owner Contact are required.';
    header('Location: property_add.php?error=' . urlencode($err));
    exit;
}

// Check for existing owner contact - REMOVED per request to allow multiple properties per phone number
/*
$checkContactSql = "SELECT id FROM properties WHERE owner_contact = '" . mysqli_real_escape_string($link, $owner_contact) . "' LIMIT 1";
$checkContactRes = mysqli_query($link, $checkContactSql);
if ($checkContactRes && mysqli_num_rows($checkContactRes) > 0) {
    $err = 'Owner Contact number already exists in the database. Please use a different number.';
    header('Location: property_add.php?error=' . urlencode($err));
    exit;
}
*/

// Determine property_id
if ($id_mode === 'new' || $property_id === '') {
    $property_id = next_id_for_type($link, $type);
    $is_old_property = 0;
} else {
    $is_old_property = 1;
}

// Map new semantic fields
$area_type = $unit_1;
$area_size = $value_1;

// Ensure new columns exist (non-breaking, optional)
ensure_column_exists($link, 'properties', 'area_type', 'VARCHAR(64) NULL');
ensure_column_exists($link, 'properties', 'area_size', 'DECIMAL(18,2) NULL');
ensure_column_exists($link, 'properties', 'total_rupees_amount', 'DECIMAL(18,2) NULL');

// Handle photos upload
$is_update = isset($_POST['is_update']) && $_POST['is_update'] == '1';
$original_property_id = $_POST['original_property_id'] ?? $property_id;

$upload_dir = __DIR__ . DIRECTORY_SEPARATOR . 'images' . DIRECTORY_SEPARATOR . 'property_uploads';
if (!is_dir($upload_dir)) {
    @mkdir($upload_dir, 0777, true);
}
$photos = [];
if (isset($_FILES['photos']) && is_array($_FILES['photos']['name'])) {
    $count = count($_FILES['photos']['name']);
    for ($i = 0; $i < $count && count($photos) < 10; $i++) {
        if ($_FILES['photos']['error'][$i] === UPLOAD_ERR_OK) {
            $ext = pathinfo($_FILES['photos']['name'][$i], PATHINFO_EXTENSION);
            $safe = preg_replace('/[^a-zA-Z0-9_-]+/', '_', pathinfo($_FILES['photos']['name'][$i], PATHINFO_FILENAME));
            $filename = $property_id . '_' . time() . '_' . $i . '.' . $ext;
            $target = $upload_dir . DIRECTORY_SEPARATOR . $filename;
            if (move_uploaded_file($_FILES['photos']['tmp_name'][$i], $target)) {
                $photos[] = 'images/property_uploads/' . $filename;
            }
        }
    }
}

if ($is_update) {
    // Fetch existing photos to merge
    $stmt_ph = mysqli_prepare($link, "SELECT photos_json FROM properties WHERE property_id = ?");
    mysqli_stmt_bind_param($stmt_ph, 's', $original_property_id);
    mysqli_stmt_execute($stmt_ph);
    $res_ph = mysqli_stmt_get_result($stmt_ph);
    if ($row_ph = mysqli_fetch_assoc($res_ph)) {
        $existing = json_decode($row_ph['photos_json'], true);
        if (is_array($existing)) {
            $photos = array_merge($existing, $photos);
        }
    }
    mysqli_stmt_close($stmt_ph);
    
    $moderation_status = 'pending'; // Reset to pending on edit
}

// Enforce min photos when publishing
if ($status === 'published' && count($photos) < 3) {
    $err = 'Publishing requires at least 3 photos.';
    header('Location: property_add.php?error=' . urlencode($err) . '&log=' . urlencode('Uploaded: ' . count($photos)));
    exit;
}

// Set main photo
$main_photo = null;
if (count($photos) > 0) {
    $idx = min(count($photos), max(1, $main_photo_index)) - 1;
    $main_photo = $photos[$idx];
}

// Keep all new submissions in pending until staff acts

$photos_json = count($photos) ? json_encode($photos) : null;

if ($is_update) {
    // Update Query
    $sql = "UPDATE properties SET
        property_id=?, type=?, is_old_property=?,
        unit_1=?, value_1=?, unit_2=?, value_2=?,
        village=?, taluka=?, district=?, landmark=?, full_address=?, google_map_link=?,
        rate_unit=?, rate_value=?, price_option=?,
        owner_name=?, owner_contact=?, alt_contact=?,
        photos_json=?, main_photo=?, video_link=?, location_note=?,
        status=?, moderation_status=?, created_by=?, moderated_by=?,
        area_type=?, area_size=?, total_rupees_amount=?
        WHERE property_id=?";
    
    $stmt = mysqli_prepare($link, $sql);
    if (!$stmt) {
        $err = 'Database Prepare Error (Update): ' . mysqli_error($link);
        header('Location: property_add.php?error=' . urlencode($err));
        exit;
    }

    $types = str_repeat('s', 31);
    mysqli_stmt_bind_param($stmt, $types,
        $property_id, $type, $is_old_property,
        $unit_1, $value_1, $unit_2, $value_2,
        $village, $taluka, $district, $landmark, $full_address, $google_map_link,
        $rate_unit, $rate_value, $price_option,
        $owner_name, $owner_contact, $alt_contact,
        $photos_json, $main_photo, $video_link, $location_note,
        $status, $moderation_status, $created_by, $created_by,
        $area_type, $area_size, $total_rupees_amount,
        $original_property_id
    );
} else {
    // Insert Query
    $sql = "INSERT INTO properties (
        property_id, type, is_old_property,
        unit_1, value_1, unit_2, value_2,
        village, taluka, district, landmark, full_address, google_map_link,
        rate_unit, rate_value, price_option,
        owner_name, owner_contact, alt_contact,
        photos_json, main_photo, video_link, location_note,
        status, moderation_status, created_by, moderated_by,
        area_type, area_size, total_rupees_amount
    ) VALUES (" . substr(str_repeat('?,', 30), 0, -1) . ")";

    $stmt = mysqli_prepare($link, $sql);

    if (!$stmt) {
        $err = 'Database Prepare Error (Insert): ' . mysqli_error($link);
        header('Location: property_add.php?error=' . urlencode($err));
        exit;
    }

    $types = str_repeat('s', 30);
    mysqli_stmt_bind_param($stmt, $types,
        $property_id, $type, $is_old_property,
        $unit_1, $value_1, $unit_2, $value_2,
        $village, $taluka, $district, $landmark, $full_address, $google_map_link,
        $rate_unit, $rate_value, $price_option,
        $owner_name, $owner_contact, $alt_contact,
        $photos_json, $main_photo, $video_link, $location_note,
        $status, $moderation_status, $created_by, $created_by,
        $area_type, $area_size, $total_rupees_amount
    );
}

$ok = false;
if ($stmt) {
    $ok = mysqli_stmt_execute($stmt);
}

if ($ok) {
    $log = 'Saved property ' . $property_id . ' (' . $type . ")\n";
    $log_units = 'Units: ';
    if ($unit_1) {
        $log_units .= $unit_1 . '=' . ($value_1 !== null ? $value_1 : '');
    } else {
        $log_units .= '-';
    }
    if ($unit_2) {
        $log_units .= ', ' . $unit_2 . '=' . ($value_2 !== null ? $value_2 : '');
    }
    $log .= $log_units . "\n";
    $log .= 'Photos: ' . count($photos) . "; Moderation: " . $moderation_status;
    header('Location: property_add.php?success=' . urlencode('Property saved') . '&log=' . urlencode($log));
} else {
    $err = 'Insert failed: ' . mysqli_error($link);
    header('Location: property_add.php?error=' . urlencode($err));
}
exit;
?>
