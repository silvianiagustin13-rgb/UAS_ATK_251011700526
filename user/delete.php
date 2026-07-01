<?php
session_start();
include '../koneksi.php';

header('Content-Type: application/json');

if (!isset($_SESSION['status']) || $_SESSION['status'] != "login") {
    http_response_code(403);
    echo json_encode(['success' => false, 'message' => 'Unauthorized']);
    exit();
}

$id = intval($_POST['id'] ?? 0);

if ($id <= 0) {
    echo json_encode(['success' => false, 'message' => 'ID tidak valid!']);
    exit();
}

$res = mysqli_query($koneksi, "SELECT username FROM tb_users WHERE id=$id");
$row = mysqli_fetch_assoc($res);
if ($row && $row['username'] === $_SESSION['username']) {
    echo json_encode(['success' => false, 'message' => 'Tidak dapat menghapus akun yang sedang login!']);
    exit();
}

$total = mysqli_fetch_row(mysqli_query($koneksi, "SELECT COUNT(*) FROM tb_users"))[0];
if ($total <= 1) {
    echo json_encode(['success' => false, 'message' => 'Tidak dapat menghapus, minimal harus ada 1 akun user!']);
    exit();
}

$query = "DELETE FROM tb_users WHERE id=$id";
if (mysqli_query($koneksi, $query)) {
    echo json_encode(['success' => true, 'message' => 'User berhasil dihapus!']);
} else {
    echo json_encode(['success' => false, 'message' => 'Gagal menghapus user!']);
}
?>