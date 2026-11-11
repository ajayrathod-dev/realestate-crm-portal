<?php
include('connection.php');

// Insert a fake property
$prefix = 'AGRI';
$fakeId = 'AGRI1001';
mysqli_query($link, "INSERT INTO properties (property_id, type) VALUES ('$fakeId', 'Agriculture')");

echo "Inserted $fakeId. Checking next ID...\n";

// Run the query logic from next_property_id.php
$sql = "SELECT property_id FROM properties WHERE property_id LIKE '" . mysqli_real_escape_string($link, $prefix) . "%' ORDER BY LENGTH(property_id) DESC, property_id DESC LIMIT 1";
$res = mysqli_query($link, $sql);
$lastNum = 0;
if ($res && mysqli_num_rows($res) > 0) {
    $row = mysqli_fetch_assoc($res);
    $last = $row['property_id'];
    echo "Found fake last ID: $last\n";
    if (preg_match('/^' . preg_quote($prefix, '/') . '([0-9]+)$/', $last, $m)) {
        $lastNum = intval($m[1]);
    }
}
$next = max(1001, $lastNum + 1);
$nextId = $prefix . str_pad((string)$next, 4, '0', STR_PAD_LEFT);

echo "Calculated Next ID: $nextId\n";

if ($nextId == 'AGRI1002') {
    echo "SUCCESS: Logic works.\n";
} else {
    echo "FAILURE: Expected AGRI1002.\n";
}

// Cleanup
mysqli_query($link, "DELETE FROM properties WHERE property_id='$fakeId'");
?>
