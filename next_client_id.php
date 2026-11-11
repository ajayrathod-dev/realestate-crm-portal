<?php
header('Content-Type: application/json');
require_once 'connection.php';

$type = isset($_GET['type']) ? $_GET['type'] : '';
$type = trim($type);

$prefix = '';
$base = 0;
if ($type === 'Purchase') {
    $prefix = 'PK';
    $base = 51; // start from PK051
} elseif ($type === 'Rent') {
    $prefix = 'PPC';
    $base = 20; // start from PPC020
}

if ($prefix === '') {
    echo json_encode(['id' => null]);
    exit;
}

$lastId = '';
$sql = "SELECT client_id FROM upload WHERE client_id LIKE '" . mysqli_real_escape_string($link, $prefix) . "%' ORDER BY client_id DESC LIMIT 1";
$res = mysqli_query($link, $sql);
if ($res && $res->num_rows > 0) {
    $row = $res->fetch_assoc();
    $lastId = $row['client_id'];
}

$lastNum = 0;
if ($lastId) {
    if (preg_match('/^' . preg_quote($prefix, '/') . '([0-9]+)/', $lastId, $m)) {
        $lastNum = intval($m[1]);
    }
}

$nextNum = max($base, $lastNum + 1);
$nextId = $prefix . str_pad($nextNum, 3, '0', STR_PAD_LEFT);

echo json_encode(['id' => $nextId]);
