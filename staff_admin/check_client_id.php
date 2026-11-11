<?php
header('Content-Type: application/json');
require_once 'connection.php';

$id = isset($_GET['id']) ? trim($_GET['id']) : '';
if ($id === '') {
    echo json_encode(['exists' => false]);
    exit;
}

$idEsc = mysqli_real_escape_string($link, $id);
$sql = "SELECT 1 FROM upload WHERE client_id='" . $idEsc . "' LIMIT 1";
$res = mysqli_query($link, $sql);
$exists = ($res && $res->num_rows > 0);

echo json_encode(['exists' => $exists]);
