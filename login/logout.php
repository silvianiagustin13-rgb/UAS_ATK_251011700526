<?php
// Hapus session dan kembali ke halaman login
session_start();
session_destroy();
header("Location: /UAS_ATK/login.php?logout=1");
exit();
?>
