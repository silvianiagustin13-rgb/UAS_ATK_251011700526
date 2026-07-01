<?php
session_start();
include '../koneksi.php';

if (!isset($_SESSION['status']) || $_SESSION['status'] != "login") {
    http_response_code(403);
    echo json_encode(['error' => 'Unauthorized']);
    exit();
}

$result = mysqli_query($koneksi, "SELECT id, username, password, created_at FROM tb_users ORDER BY id ASC");
$data = [];
while ($row = mysqli_fetch_assoc($result)) {
    $data[] = $row;
}

header('Content-Type: application/json');
echo json_encode(['data' => $data]);
?>