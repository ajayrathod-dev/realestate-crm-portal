<?php
include('connection.php');

$types = ['Agriculture', 'Plot'];

foreach ($types as $type) {
    if ($type == 'Agriculture') $prefix = 'AGRI';
    if ($type == 'Plot') $prefix = 'PLT';
    
    echo "Checking Type: $type (Prefix: $prefix)\n";

    $sql1 = "SELECT id, property_id FROM properties WHERE property_id LIKE '$prefix%' ORDER BY id DESC LIMIT 5";
    $res1 = mysqli_query($link, $sql1);
    
    echo "  Sort by ID DESC:\n";
    if ($res1) {
        while ($row = mysqli_fetch_assoc($res1)) {
            echo "    ID: " . $row['id'] . ", PropertyID: " . $row['property_id'] . "\n";
        }
    } else {
        echo "    Error: " . mysqli_error($link) . "\n";
    }

    $sql2 = "SELECT id, property_id FROM properties WHERE property_id LIKE '$prefix%' ORDER BY LENGTH(property_id) DESC, property_id DESC LIMIT 5";
    $res2 = mysqli_query($link, $sql2);

    echo "  Sort by Length/Value DESC:\n";
    if ($res2) {
        while ($row = mysqli_fetch_assoc($res2)) {
            echo "    ID: " . $row['id'] . ", PropertyID: " . $row['property_id'] . "\n";
        }
    } else {
        echo "    Error: " . mysqli_error($link) . "\n";
    }
    echo "\n";
}
?>
