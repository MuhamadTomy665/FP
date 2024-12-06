<?php
session_start();

// Periksa apakah admin telah login
if (!isset($_SESSION["is_login"]) || $_SESSION["role"] != 1) {
    header("location: login.php");
    exit();
}

// Koneksi ke database
$servername = "localhost";
$db_username = "root";
$db_password = "";
$dbname = "db user";

$conn = new mysqli($servername, $db_username, $db_password, $dbname);

// Periksa koneksi
if ($conn->connect_error) {
    die("Koneksi database gagal: " . $conn->connect_error);
}

// Mengambil total user unik dari database berdasarkan kolom username
$sql_users = "SELECT COUNT(DISTINCT username) AS total_users FROM tbl_ticket WHERE username IS NOT NULL AND username != ''";
$result_users = $conn->query($sql_users);
$total_users = $result_users->fetch_assoc()['total_users'] ?? 0;

// Mengambil total tiket (penjumlahan dari kolom ticket_quantity)
$sql_orders = "SELECT SUM(ticket_quantity) AS total_orders FROM tbl_ticket";
$result_orders = $conn->query($sql_orders);
$total_orders = $result_orders->fetch_assoc()['total_orders'] ?? 0;

// Mengambil data dari tabel tbl_konser
$sql_konser = "SELECT * FROM tbl_konser";
$result_konser = $conn->query($sql_konser);

// Logika untuk menghapus konser
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['delete_konser_id'])) {
    $konser_id = intval($_POST['delete_konser_id']);
    if ($konser_id > 0) {
        $sql_delete = "DELETE FROM tbl_konser WHERE konser_id = ?";
        $stmt = $conn->prepare($sql_delete);
        $stmt->bind_param('i', $konser_id);
        
        if ($stmt->execute()) {
            $_SESSION['success_message'] = "Konser berhasil dihapus.";
        } else {
            $_SESSION['error_message'] = "Gagal menghapus konser.";
        }
        $stmt->close();
        header("Location: " . $_SERVER['PHP_SELF']);
        exit();
    }
}

// Tutup koneksi database
$conn->close();
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard Admin</title>
    <style>
        /* Reset */
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
            font-family: Arial, sans-serif;
        }

        /* Body */
        body {
            display: flex;
            height: 100vh;
            background-color: #f4f4f9;
            color: #333;
        }

        /* Container */
        .container {
            display: flex;
            width: 100%;
            height: 100%;
        }

        /* Sidebar */
       

        /* Main Content */
        .main-content {
            flex: 1;
            padding: 20px;
            background-color: #ffffff;
            overflow-y: auto;
        }

        /* Header */
        .main-content header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 30px;
            border-bottom: 1px solid #ddd;
            padding-bottom: 10px;
        }

        .main-content header h1 {
            font-size: 1.8rem;
            color: #2c3e50;
        }

        .user-info {
            display: flex;
            align-items: center;
            gap: 15px;
        }

        .user-info span {
            font-size: 1rem;
            color: #2c3e50;
        }

        .user-info a {
            text-decoration: none;
            color: #e74c3c;
            font-weight: bold;
            transition: color 0.3s ease;
        }

        .user-info a:hover {
            color: #c0392b;
        }

        /* Stats Section */
        .stats {
            display: flex;
            gap: 20px;
            margin-bottom: 30px;
        }

        .stat-box {
            background-color: #ecf0f1;
            flex: 1;
            padding: 20px;
            text-align: center;
            border-radius: 8px;
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
        }

        .stat-box h3 {
            margin-bottom: 10px;
            font-size: 1.2rem;
            color: #34495e;
        }

        .stat-box p {
            font-size: 2rem;
            color: #2c3e50;
            font-weight: bold;
        }

        /* Table Section */
        .konser-data h2 {
            font-size: 1.5rem;
            color: #2c3e50;
            margin-bottom: 20px;
        }

        .konser-data table {
            width: 100%;
            border-collapse: collapse;
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
            background-color: #ffffff;
        }

        .konser-data table thead {
            background-color: #2c3e50;
            color: #ecf0f1;
        }

        .konser-data table thead th {
            padding: 15px;
            font-size: 1rem;
        }

        .konser-data table tbody td {
            padding: 15px;
            text-align: center;
            font-size: 0.9rem;
            color: #34495e;
            border-bottom: 1px solid #ddd;
        }

        .konser-data table tbody tr:nth-child(even) {
            background-color: #f4f4f9;
        }

        .konser-data table tbody tr:hover {
            background-color: #f1c40f;
            color: #2c3e50;
            transition: background 0.3s ease;
        }

        .btn-danger {
            background-color: #e74c3c;
            color: white;
            border: none;
            padding: 5px 10px;
            border-radius: 5px;
            cursor: pointer;
        }

        .btn-danger:hover {
            background-color: #c0392b;
        }
    </style>
</head>
<body>
    
    <?php
    include "sidebar.php";
    ?>
        <!-- Main Content -->
        <main class="main-content">
           

            <section class="stats">
                <div class="stat-box">
                    <h3>Total Users</h3>
                    <p><?= htmlspecialchars($total_users); ?></p>
                </div>
                <div class="stat-box">
                    <h3>Total Orders</h3>
                    <p><?= htmlspecialchars($total_orders); ?></p>
                </div>
            </section>

            <section class="konser-data">
                <h2>Data Konser</h2>

                <!-- Notifikasi sukses atau error -->
                <?php if (isset($_SESSION['success_message'])): ?>
                    <div class="alert alert-success">
                        <?= $_SESSION['success_message']; ?>
                    </div>
                    <?php unset($_SESSION['success_message']); ?>
                <?php endif; ?>

                <?php if (isset($_SESSION['error_message'])): ?>
                    <div class="alert alert-danger">
                        <?= $_SESSION['error_message']; ?>
                    </div>
                    <?php unset($_SESSION['error_message']); ?>
                <?php endif; ?>

                <table>
                    <thead>
                        <tr>
                            <th>ID Konser</th>
                            <th>Nama Konser</th>
                            <th>Tanggal</th>
                            <th>Stok</th>
                            <th>Harga Tiket</th>
                            <th>Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php if ($result_konser->num_rows > 0): ?>
                            <?php while ($row = $result_konser->fetch_assoc()): ?>
                                <tr>
                                    <td><?= htmlspecialchars($row['konser_id']); ?></td>
                                    <td><?= htmlspecialchars($row['nama_konser']); ?></td>
                                    <td><?= htmlspecialchars($row['tanggal_konser']); ?></td>
                                    <td><?= htmlspecialchars($row['stok']); ?></td>
                                    <td><?= htmlspecialchars(number_format($row['harga_tiket'], 0, ',', '.')); ?></td>
                                    <td>
                                        <!-- Form untuk menghapus konser -->
                                        <form method="POST" style="display:inline;">
                                            <input type="hidden" name="delete_konser_id" value="<?= $row['konser_id']; ?>">
                                            <button type="submit" class="btn btn-danger">Hapus</button>
                                        </form>
                                    </td>
                                </tr>
                            <?php endwhile; ?>
                        <?php else: ?>
                            <tr>
                                <td colspan="6">Tidak ada data konser tersedia.</td>
                            </tr>
                        <?php endif; ?>
                    </tbody>
                </table>
            </section>
        </main>
</body>
</html>
