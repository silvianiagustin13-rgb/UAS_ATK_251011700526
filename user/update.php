<?php
session_start();
include '../koneksi.php';

header('Content-Type: application/json');

if (!isset($_SESSION['status']) || $_SESSION['status'] != "login") {
    http_response_code(403);
    echo json_encode(['success' => false, 'message' => 'Unauthorized']);
    exit();
}

$id       = intval($_POST['user_id'] ?? 0);
$username = trim($_POST['username'] ?? '');
$password = trim($_POST['password'] ?? '');

if ($id <= 0 || $username === '' || $password === '') {
    echo json_encode(['success' => false, 'message' => 'Data tidak lengkap!']);
    exit();
}

$cek = mysqli_query($koneksi, "SELECT id FROM tb_users WHERE username='" . mysqli_real_escape_string($koneksi, $username) . "' AND id != $id");
if (mysqli_num_rows($cek) > 0) {
    echo json_encode(['success' => false, 'message' => 'Username sudah digunakan user lain!']);
    exit();
}

$query = "UPDATE tb_users SET username=?, password=? WHERE id=?";
$stmt  = mysqli_prepare($koneksi, $query);
mysqli_stmt_bind_param($stmt, "ssi", $username, $password, $id);

if (mysqli_stmt_execute($stmt)) {
    echo json_encode(['success' => true, 'message' => 'User berhasil diperbarui!']);
} else {
    echo json_encode(['success' => false, 'message' => 'Gagal memperbarui user: ' . mysqli_error($koneksi)]);
}
?>