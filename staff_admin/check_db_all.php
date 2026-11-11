<?php
include('connection.php');
$res = mysqli_query($link, "SELECT * FROM properties");
if ($res) {
    echo "Total Rows: " . mysqli_num_rows($res) . "\n";
    while ($row = mysqli_fetch_assoc($res)) {
        echo "PropertyID: " . $row['property_id'] . "\n";
    }
} else {
    echo "Error: " . mysqli_error($link);
}
?>
