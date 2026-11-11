<?php
require_once 'connection.php';
header("Cache-Control: no-store, no-cache, must-revalidate, max-age=0");
header("Cache-Control: post-check=0, pre-check=0", false);
header("Pragma: no-cache");

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

$type = isset($_GET['type']) ? $_GET['type'] : '';
$prefix = prefix_for_type($type);
if ($prefix === '') { echo ''; exit; }

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
echo $prefix . str_pad((string)$next, 4, '0', STR_PAD_LEFT);
?>
