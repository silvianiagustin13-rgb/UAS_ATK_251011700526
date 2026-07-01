<?php
include '../koneksi.php';
session_start();

$username = $_POST['username'];
$password = $_POST['password'];

$query = "SELECT * FROM tb_users WHERE username=? AND password=?";
$stmt  = mysqli_prepare($koneksi, $query);
mysqli_stmt_bind_param($stmt, "ss", $username, $password);
mysqli_stmt_execute($stmt);
$result = mysqli_stmt_get_result($stmt);

if (mysqli_num_rows($result) > 0) {
    // Login berhasil -> simpan session
    $_SESSION['username'] = $username;
    $_SESSION['status']   = "login";
    header("Location: ../index.php");
    exit();
} else {
    header("Location: ../login.php?error=Username+atau+password+salah!");
    exit();
}
?>
