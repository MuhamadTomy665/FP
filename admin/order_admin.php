<?php
session_start();
require_once(__DIR__ . '/../service/database.php'); // Sesuaikan jalur ini

// Jika ada permintaan konfirmasi pembayaran
if (isset($_GET['ticket_id'])) {
    $ticket_id = intval($_GET['ticket_id']);

    // Query untuk mengupdate status menjadi 1 (Sudah Bayar)
    $sql = "UPDATE tbl_ticket SET status = 1 WHERE ticket_id = ?";
    $stmt = $db->prepare($sql);
    $stmt->bind_param('i', $ticket_id);

    if ($stmt->execute()) {
        $_SESSION['success_message'] = "Pembayaran berhasil dikonfirmasi.";
    } else {
        $_SESSION['error_message'] = "Gagal mengonfirmasi pembayaran.";
    }
    $stmt->close();

    // Redirect agar menghindari pengulangan aksi ketika halaman di-refresh
    header("Location: " . $_SERVER['PHP_SELF']);
    exit();
}

$sql = "SELECT * FROM tbl_ticket";
$result = $db->query($sql);

// Array untuk menyimpan data tiket
$tickets = [];
if ($result && $result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
        $tickets[] = $row;
    }
}
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin - Data Tiket</title>

    <!-- Tambahkan Bootstrap -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body {
            display: flex;
            min-height: 100vh;
            font-family: Arial, sans-serif;
            background-color: #f8f9fa;
        }

        .sidebar {
            width: 250px;
            background-color: #2c3e50;
            color: #ecf0f1;
            display: flex;
            flex-direction: column;
            padding: 20px;
        }

        .sidebar-header {
            font-size: 1.5rem;
            font-weight: bold;
            text-align: center;
            margin-bottom: 20px;
        }

        .sidebar nav ul {
            list-style: none;
        }

        .sidebar nav ul li {
            margin: 10px 0;
        }

        .sidebar nav ul li a {
            text-decoration: none;
            color: #ecf0f1;
            padding: 10px 15px;
            border-radius: 5px;
            display: block;
        }

        .sidebar nav ul li a:hover {
            background-color: #34495e;
        }

        .main-content {
            flex: 1;
            padding: 20px;
        }

        h1 {
            text-align: center;
            margin-bottom: 20px;
            color: #343a40;
        }

        .table-container {
            margin-top: 20px;
        }

        /* Status styling */
        .status-pending {
            color: red;  /* Merah untuk 'Belum Bayar' */
            font-weight: bold;
        }

        .status-paid {
            color: green;  /* Hijau untuk 'Sudah Bayar' */
            font-weight: bold;
        }

        .status-unknown {
            color: gray;  /* Warna abu-abu untuk status yang tidak dikenali */
            font-weight: bold;
        }
    </style>
</head>
<body>
   <?php
   include "sidebar.php";
   ?>
    <!-- Main Content -->
    <div class="main-content">
        <h1>Data Tiket</h1>

        <!-- Notifikasi -->
        <?php if (isset($_SESSION['success_message'])): ?>
            <div class="alert alert-success" role="alert">
                <?= $_SESSION['success_message']; ?>
            </div>
            <?php unset($_SESSION['success_message']); ?>
        <?php endif; ?>

        <?php if (isset($_SESSION['error_message'])): ?>
            <div class="alert alert-danger" role="alert">
                <?= $_SESSION['error_message']; ?>
            </div>
            <?php unset($_SESSION['error_message']); ?>
        <?php endif; ?>

        <div class="table-container">
            <table class="table table-striped table-bordered">
                <thead class="table-dark">
                    <tr>
                        <th>No</th>
                        <th>Nama Konser</th>
                        <th>Nama Lengkap</th>
                        <th>Email</th>
                        <th>Username</th>
                        <th>Jumlah Tiket</th>
                        <th>Total Harga</th>
                        <th>Status</th>
                        <th>Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if (count($tickets) > 0): ?>
                        <?php foreach ($tickets as $index => $ticket): ?>
                            <tr>
                                <td><?= htmlspecialchars($index + 1); ?></td>
                                <td><?= htmlspecialchars($ticket['nama_konser']); ?></td>
                                <td><?= htmlspecialchars($ticket['fullname']); ?></td>
                                <td><?= htmlspecialchars($ticket['email']); ?></td>
                                <td><?= htmlspecialchars($ticket['username']); ?></td>
                                <td><?= htmlspecialchars($ticket['ticket_quantity']); ?></td>
                                <td>Rp <?= number_format($ticket['total_price'], 0, ',', '.'); ?></td>
                                <td class="<?php 
                                    echo $ticket['status'] == 0 ? 'status-pending' :
                                         ($ticket['status'] == 1 ? 'status-paid' : 'status-unknown'); ?>">
                                    <?= $ticket['status'] == 0 ? 'Belum Bayar' :
                                         ($ticket['status'] == 1 ? 'Sudah Bayar' : 'Status Tidak Dikenal'); ?>
                                </td>
                                <td>
                                    <?php if ($ticket['status'] == 0): ?>
                                        <a href="?ticket_id=<?= urlencode($ticket['ticket_id']); ?>" 
                                           class="btn btn-success btn-sm" 
                                           onclick="return confirm('Konfirmasi pembayaran untuk tiket ini?')">
                                           Konfirmasi Pembayaran
                                        </a>
                                    <?php else: ?>
                                        <span class="text-muted">Tidak Ada Aksi</span>
                                    <?php endif; ?>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    <?php else: ?>
                        <tr>
                            <td colspan="9" class="text-center">Tidak ada data tiket yang tersedia.</td>
                        </tr>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
    </div>

    <!-- Tambahkan Bootstrap JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
