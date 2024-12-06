<?php
session_start();
require_once(__DIR__ . '/../service/database.php'); // Pastikan jalur ini benar

if (isset($_GET['ticket_id'])) { // Ganti dari 'email' menjadi 'id'
    $id = $_GET['ticket_id'];

    // Query untuk mengupdate status menjadi 1 (Sudah Bayar)
    $sql = "UPDATE tbl_ticket SET status = 1 WHERE ticket_id = ?";
    $stmt = $db->prepare($sql);
    $stmt->bind_param('i', $id); // 'i' karena tipe id adalah integer

    if ($stmt->execute()) {
        $_SESSION['success_message'] = "Pembayaran berhasil dikonfirmasi.";
    } else {
        $_SESSION['error_message'] = "Gagal mengonfirmasi pembayaran.";
    }
    $stmt->close();
} else {
    $_SESSION['error_message'] = "ID tiket tidak valid.";
}

// Redirect kembali ke halaman data tiket
header("Location: order_admin.php");
exit();
