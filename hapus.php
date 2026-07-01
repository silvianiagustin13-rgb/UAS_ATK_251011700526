<?php
include 'koneksi.php';
session_start();
if (!isset($_SESSION['status']) || $_SESSION['status'] != "login") {
    header("Location: login.php"); exit();
}

if (isset($_GET['id'])) {
    $id = mysqli_real_escape_string($koneksi, $_GET['id']);

    $res = mysqli_query($koneksi, "SELECT nama_barang, foto_barang FROM tb_pengadaan_atk WHERE id_pengadaan='$id'");
    $row = mysqli_fetch_assoc($res);

    if (!$row) {
        header("Location: pengadaan.php?error=Data+tidak+ditemukan!");
        exit();
    }

    $query = "DELETE FROM tb_pengadaan_atk WHERE id_pengadaan='$id'";
    if (mysqli_query($koneksi, $query)) {
        if ($row['foto_barang'] && file_exists("uploads/" . $row['foto_barang'])) {
            unlink("uploads/" . $row['foto_barang']);
        }
        $nama = urlencode('Data "' . $row['nama_barang'] . '" berhasil dihapus!');
        header("Location: pengadaan.php?success=$nama");
        exit();
    } else {
        header("Location: pengadaan.php?error=Gagal+menghapus+data!");
        exit();
    }
} else {
    header("Location: pengadaan.php?error=ID+tidak+valid!");
    exit();
}
?>
