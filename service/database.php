<?php
// Koneksi ke database
$host = "localhost";
$user = "root";
$password = "";
$dbname = "db user"; // Ganti dengan nama database Anda

// Buat koneksi
$db = new mysqli($host, $user, $password, $dbname);

// Periksa koneksi
if ($db->connect_error) {
    die("Koneksi gagal: " . $db->connect_error);
}
?>
