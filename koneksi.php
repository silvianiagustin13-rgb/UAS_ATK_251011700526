<?php
$server   = getenv('DB_HOST') ?: "localhost";
$port     = getenv('DB_PORT') ?: 3306;
$username = getenv('DB_USER') ?: "root";
$password = getenv('DB_PASS') ?: "";
$db       = getenv('DB_NAME') ?: "db_pengadaan_atk";
$use_ssl  = filter_var(getenv('DB_SSL') ?: false, FILTER_VALIDATE_BOOLEAN);

if ($use_ssl) {
    $koneksi = mysqli_init();
    mysqli_ssl_set($koneksi, null, null, null, null, null);
    $connected = mysqli_real_connect(
        $koneksi,
        $server,
        $username,
        $password,
        $db,
        $port,
        null,
        MYSQLI_CLIENT_SSL | MYSQLI_CLIENT_SSL_DONT_VERIFY_SERVER_CERT
    );
    if (!$connected) {
        die("Koneksi gagal (SSL): " . mysqli_connect_error());
    }
} else {
    $koneksi = mysqli_connect($server, $username, $password, $db, $port);
    if (!$koneksi) {
        die("Koneksi gagal: " . mysqli_connect_error());
    }
}

mysqli_set_charset($koneksi, "utf8mb4");

$protocol = (!empty($_SERVER['HTTPS']) && $_SERVER['HTTPS'] !== 'off') ? "https" : "http";
$host     = $_SERVER['HTTP_HOST'];
$script   = $_SERVER['SCRIPT_NAME'];
$base     = implode('/', array_slice(explode('/', $script), 0, -1));
if (in_array(basename(dirname($script)), ['login','partials','user'])) {
    $base = implode('/', array_slice(explode('/', $base), 0, -1));
}
define('BASE_URL', $protocol . '://' . $host . $base);
?>