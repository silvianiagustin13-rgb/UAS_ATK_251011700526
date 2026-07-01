<?php
session_start();
include '../koneksi.php';

header('Content-Type: application/json');

if (!isset($_SESSION['status']) || $_SESSION['status'] != "login") {
    http_response_code(403);
    echo json_encode(['success' => false, 'message' => 'Unauthorized']);
    exit();
}

$username = trim($_POST['username'] ?? '');
$password = trim($_POST['password'] ?? '');

if ($username === '' || $password === '') {
    echo json_encode(['success' => false, 'message' => 'Username dan password wajib diisi!']);
    exit();
}

$cek = mysqli_query($koneksi, "SELECT id FROM tb_users WHERE username='" . mysqli_real_escape_string($koneksi, $username) . "'");
if (mysqli_num_rows($cek) > 0) {
    echo json_encode(['success' => false, 'message' => 'Username sudah digunakan!']);
    exit();
}

$query = "INSERT INTO tb_users (username, password) VALUES (?, ?)";
$stmt  = mysqli_prepare($koneksi, $query);
mysqli_stmt_bind_param($stmt, "ss", $username, $password);

if (mysqli_stmt_execute($stmt)) {
    echo json_encode(['success' => true, 'message' => 'User berhasil ditambahkan!']);
} else {
    echo json_encode(['success' => false, 'message' => 'Gagal menambahkan user: ' . mysqli_error($koneksi)]);
}
?>