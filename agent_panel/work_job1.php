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

// Get form data
$customer_name = mysqli_real_escape_string($link, $_POST['customer_name']);
$village = mysqli_real_escape_string($link, $_POST['village'] ?? '');
$business = mysqli_real_escape_string($link, $_POST['business']);
    $address_line = trim($_POST['address_line'] ?? '');
    $address_street = trim($_POST['address_street'] ?? '');
    $address_landmark = trim($_POST['address_landmark'] ?? '');
    $address_city = trim($_POST['address_city'] ?? '');
    $address_taluka = trim($_POST['address_taluka'] ?? '');
    $address_district = trim($_POST['address_district'] ?? '');

    $address_parts = array_filter([
        $address_line,
        $address_street,
        $address_landmark,
        $address_city,
        $address_taluka,
        $address_district
    ], function($v) { return $v !== ''; });
    $address = mysqli_real_escape_string($link, implode(' | ', $address_parts));
$contact_no = mysqli_real_escape_string($link, $_POST['contact_no']);
$customer_email = mysqli_real_escape_string($link, $_POST['customer_email'] ?? '');
$birth_date = mysqli_real_escape_string($link, $_POST['birth_date'] ?? '');
$client_id = mysqli_real_escape_string($link, $_POST['client_id']);
// Property type can be multiple (checkbox array). Join safely.
if (isset($_POST['property_type'])) {
    $propertyTypes = $_POST['property_type'];
    if (is_array($propertyTypes)) {
        $sanitized = array_map(function($v) use ($link) { return mysqli_real_escape_string($link, $v); }, $propertyTypes);
        $property_type = mysqli_real_escape_string($link, implode(', ', $sanitized));
    } else {
        $property_type = mysqli_real_escape_string($link, $propertyTypes);
    }
} else {
    $property_type = '';
}
$sale_rent = mysqli_real_escape_string($link, $_POST['sale_rent']);
$deposit = mysqli_real_escape_string($link, $_POST['deposit'] ?? '');
$expected_rent = mysqli_real_escape_string($link, $_POST['expected_rent']);
$location_area = mysqli_real_escape_string($link, $_POST['location_area']);
$specification = mysqli_real_escape_string($link, $_POST['specification']);

// Map property_type to selected Property Type when explicit field not used
if ($property_type === '' && $location_area !== '') {
    $property_type = $location_area;
}

// New optional fields for city-based and agriculture selections
$city_location = mysqli_real_escape_string($link, $_POST['city_location'] ?? '');
$city_area = mysqli_real_escape_string($link, $_POST['city_area'] ?? '');
$agri_district = mysqli_real_escape_string($link, $_POST['agri_district'] ?? '');
$agri_taluka = mysqli_real_escape_string($link, $_POST['agri_taluka'] ?? '');
$agri_property_size = mysqli_real_escape_string($link, $_POST['agri_property_size'] ?? '');
$land_property_type = mysqli_real_escape_string($link, $_POST['land_property_type'] ?? '');

// Fallback: parse combined requirement if hidden fields are empty
if ((empty($sale_rent) || empty($location_area)) && isset($_POST['requirement_combo'])) {
    $combo = $_POST['requirement_combo'];
    $parts = explode('|', $combo);
    $sale_rent = mysqli_real_escape_string($link, $parts[0] ?? '');
    $location_area = mysqli_real_escape_string($link, $parts[1] ?? '');
}

// Safety: auto-generate client_id if missing based on sale_rent
if (empty($client_id) && !empty($sale_rent)) {
    $prefix = '';
    $base = 0;
    if ($sale_rent === 'Purchase') {
        $prefix = 'PK';
        $base = 51; // PK051
    } elseif ($sale_rent === 'Rent') {
        $prefix = 'PPC';
        $base = 20; // PPC020
    }
    if ($prefix !== '') {
        $prefixEsc = mysqli_real_escape_string($link, $prefix);
        $sqlLast = "SELECT client_id FROM upload WHERE client_id LIKE '{$prefixEsc}%' ORDER BY client_id DESC LIMIT 1";
        $resLast = mysqli_query($link, $sqlLast);
        $lastId = '';
        if ($resLast && ($rowLast = mysqli_fetch_assoc($resLast))) {
            $lastId = $rowLast['client_id'];
        }
        $lastNum = 0;
        if ($lastId && preg_match('/^' . preg_quote($prefix, '/') . '([0-9]+)/', $lastId, $m)) {
            $lastNum = (int)$m[1];
        }
        $nextNum = max($base, $lastNum + 1);
        $client_id = $prefix . str_pad($nextNum, 3, '0', STR_PAD_LEFT);
    }
}

// Server-side duplicate CLIENT ID check (prevents duplicate IDs on manual entry)
if (!empty($client_id)) {
    $cidEsc = mysqli_real_escape_string($link, $client_id);
    $sqlDup = "SELECT id FROM upload WHERE client_id='$cidEsc' LIMIT 1";
    $resDup = mysqli_query($link, $sqlDup);
    if ($resDup && mysqli_fetch_assoc($resDup)) {
        $p = "Client ID already exists";
        header("Location:work_job.php?error=$p");
        exit;
    }
}

// Build INSERT dynamically based on existing columns and auto-migrate minimal schema differences
$existingCols = [];
$resCols = mysqli_query($link, "SHOW COLUMNS FROM upload");
if ($resCols) {
    while ($colRow = mysqli_fetch_assoc($resCols)) {
        if (!empty($colRow['Field'])) $existingCols[$colRow['Field']] = true;
    }
}

// Ensure required columns exist (customer_email, birth_date)
$addedCols = [];
$columnsToEnsure = [
    'customer_email' => 'varchar(255) DEFAULT NULL',
    'birth_date' => 'date DEFAULT NULL'
];
foreach ($columnsToEnsure as $colName => $definition) {
    if (!isset($existingCols[$colName])) {
        $sqlAlter = "ALTER TABLE upload ADD COLUMN `{$colName}` {$definition}";
        if (mysqli_query($link, $sqlAlter)) {
            $existingCols[$colName] = true;
            $addedCols[] = $colName;
        }
    }
}

// Prepare all data fields
$allData = [
    'customer_name' => $customer_name,
    'village' => $village,
    'business' => $business,
    'address' => $address,
    'contact_no' => $contact_no,
    'customer_email' => $customer_email,
    'birth_date' => $birth_date,
    'client_id' => $client_id,
    'property_type' => $property_type,
    'sale_rent' => $sale_rent,
    'expected_rent' => $expected_rent,
    'deposit' => $deposit,
    'location_area' => $location_area,
    'city_location' => $city_location,
    'city_area' => $city_area,
    'agri_district' => $agri_district,
    'agri_taluka' => $agri_taluka,
    'agri_property_size' => $agri_property_size,
    'land_property_type' => $land_property_type,
    'specification' => $specification,
    'by' => $u_uname
];

// Filter to only columns that exist
$cols = [];
$vals = [];
foreach ($allData as $k => $v) {
    if (isset($existingCols[$k])) {
        $cols[] = "`$k`";
        $vals[] = "'" . mysqli_real_escape_string($link, $v) . "'";
    }
}

// If no columns matched, fail with clear error
if (empty($cols)) {
    $p = "Property Not Created: No valid columns to insert";
    header("Location:work_job.php?error=" . urlencode($p));
    exit;
}

$sql = "INSERT INTO `upload` (" . implode(', ', $cols) . ") VALUES (" . implode(', ', $vals) . ")";

if (mysqli_query($link, $sql)) {
    $p = "Property Added Successfully";
    $log = '';
    if (!empty($addedCols)) {
        $log = 'Added columns: ' . implode(', ', $addedCols);
    }
    header("Location:work_job.php?success=" . urlencode($p) . (!empty($log) ? "&log=" . urlencode($log) : ''));
} else {
    $p = "Property Not Created: " . mysqli_error($link);
    header("Location:work_job.php?error=" . urlencode($p));
}
?>
