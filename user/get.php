<?php
session_start();
include '../koneksi.php';

header('Content-Type: application/json');

if (!isset($_SESSION['status']) || $_SESSION['status'] != "login") {
    http_response_code(403);
    echo json_encode(['success' => false, 'message' => 'Unauthorized']);
    exit();
}

$id = intval($_GET['id'] ?? 0);
$res = mysqli_query($koneksi, "SELECT id, username, password FROM tb_users WHERE id=$id");
$row = mysqli_fetch_assoc($res);

if ($row) {
    echo json_encode(['success' => true, 'data' => $row]);
} else {
    echo json_encode(['success' => false, 'message' => 'User tidak ditemukan!']);
}
?>