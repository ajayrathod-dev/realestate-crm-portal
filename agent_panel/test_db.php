<?php
include('connection.php');
$sql = "SELECT price_option FROM properties LIMIT 1";
$res = mysqli_query($link, $sql);
if ($res) {
    echo "Column exists. Row count: " . mysqli_num_rows($res) . "\n";
    $row = mysqli_fetch_assoc($res);
    echo "Value: " . var_export($row['price_option'], true);
} else {
    echo "Error: " . mysqli_error($link);
}
?>