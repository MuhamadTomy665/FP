<?php
session_start();

// Hapus semua variabel sesi
session_unset();
session_destroy();

// Pesan untuk login kembali
session_start();
$_SESSION['logout_message'] = "Anda telah logout. Silakan login kembali.";

// Redirect ke halaman login
header("Location: login.php");
exit();
?>
