<?php
session_start();
require_once(__DIR__ . '/../service/database.php'); // Sesuaikan jalur ini



// Kunci privat untuk enkripsi dan dekripsi
define('ENCRYPTION_KEY', 'kunci_tersembunyi');

// Fungsi enkripsi dan dekripsi
function encrypt($data) {
    return openssl_encrypt($data, 'aes-128-cbc', ENCRYPTION_KEY, 0, substr(ENCRYPTION_KEY, 0, 16));
}

function decrypt($data) {
    return openssl_decrypt($data, 'aes-128-cbc', ENCRYPTION_KEY, 0, substr(ENCRYPTION_KEY, 0, 16));
}

// Query untuk mengambil data dari tabel tbl_tiket
$sql = "SELECT * FROM tbl_ticket";
$result = $db->query($sql);

// Array untuk menyimpan data tiket
$tickets = [];
if ($result && $result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
        $tickets[] = $row;
    }
}

// Proses konfirmasi baris
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['confirm_row'])) {
    $index = intval($_POST['index']);
    if (isset($tickets[$index])) {
        $_SESSION['confirmed_rows'][] = $index; // Simpan indeks baris yang dikonfirmasi dalam session
        $_SESSION['success_message'] = "Baris ke-" . ($index + 1) . " berhasil dikonfirmasi.";
    } else {
        $_SESSION['error_message'] = "Data tidak ditemukan.";
    }
    header("Location: " . $_SERVER['PHP_SELF']);
    exit;
}

// Simpan baris yang dikonfirmasi (jika ada)
if (!isset($_SESSION['confirmed_rows'])) {
    $_SESSION['confirmed_rows'] = [];
}
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin - Data User</title>

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
        .status-pending {
            color: orange;
            font-weight: bold;
        }
        .status-paid {
            color: green;
            font-weight: bold;
        }
        .status-unknown {
            color: red;
            font-weight: bold;
        }
        .row-confirmed {
            background-color: #d4edda !important;
        }
    </style>
</head>
<body>
    <!-- Sidebar -->
    <?php
    include "sidebar.php";
    ?>

    <!-- Main Content -->
    <div class="main-content">
        <h1>Data User</h1>

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

        <!-- Kotak Pencarian -->
        <div class="mb-3">
            <input type="text" id="searchInput" class="form-control" placeholder="Cari data berdasarkan NIK...">
        </div>

        <div class="table-container">
            <table class="table table-striped table-bordered" id="dataTable">
                <thead class="table-dark">
                    <tr>
                        <th>No</th>
                        <th>Nama Konser</th>
                        <th>Nama Lengkap</th>
                        <th>Username</th>
                        <th>Jumlah Tiket</th>
                        <th>Total Harga</th>
                        <th>Status</th>
                        <th>NIK</th>
                        <th>Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if (count($tickets) > 0): ?>
                        <?php foreach ($tickets as $index => $ticket): ?>
                            <tr class="<?= in_array($index, $_SESSION['confirmed_rows']) ? 'row-confirmed' : ''; ?>">
                                <td><?= htmlspecialchars($index + 1); ?></td>
                                <td><?= htmlspecialchars($ticket['nama_konser']); ?></td>
                                <td><?= htmlspecialchars($ticket['fullname']); ?></td>
                                <td><?= htmlspecialchars($ticket['username']); ?></td>
                                <td><?= htmlspecialchars($ticket['ticket_quantity']); ?></td>
                                <td>Rp <?= number_format($ticket['total_price'], 0, ',', '.'); ?></td>
                                <td class="<?php 
                                    echo $ticket['status'] == 0 ? 'status-pending' :
                                         ($ticket['status'] == 1 ? 'status-paid' : 'status-unknown'); ?>">
                                    <?= $ticket['status'] == 0 ? 'Belum Bayar' :
                                         ($ticket['status'] == 1 ? 'Sudah Bayar' : 'Status Tidak Dikenal'); ?>
                                </td>
                                <td><?= htmlspecialchars($ticket['nik']); ?></td>
                                <td>
                                    <form method="POST" style="display: inline;">
                                        <input type="hidden" name="index" value="<?= $index; ?>">
                                        <button type="submit" name="confirm_row" class="btn btn-sm btn-success">Konfirmasi</button>
                                    </form>
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

    <!-- Skrip Pencarian -->
    <script>
        document.getElementById('searchInput').addEventListener('input', function() {
            const filter = this.value.toLowerCase();
            const rows = document.querySelectorAll('#dataTable tbody tr');

            rows.forEach(row => {
                const nik = row.cells[7].textContent.toLowerCase(); // Kolom NIK

                if (nik.includes(filter)) {
                    row.style.display = '';
                } else {
                    row.style.display = 'none';
                }
            });
        });
    </script>
</body>
</html>
