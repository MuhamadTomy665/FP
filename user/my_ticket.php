<?php
session_start();

// Periksa apakah pengguna sudah login dan memiliki peran yang benar (misalnya user biasa dengan role 0)
if (!isset($_SESSION["is_login"]) || $_SESSION["role"] != 0) {
    header("location: login.php");
    exit();
}

include('../service/database.php');

// Kembalikan stok tiket yang belum dibayar setelah waktu habis dan hapus tiket
$updateStockBack = "UPDATE tbl_konser 
                    JOIN tbl_ticket ON tbl_ticket.nama_konser COLLATE utf8mb4_unicode_ci = tbl_konser.nama_konser COLLATE utf8mb4_unicode_ci
                    SET tbl_konser.stok = tbl_konser.stok + tbl_ticket.ticket_quantity
                    WHERE TIMESTAMPDIFF(SECOND, tbl_ticket.created_at, NOW()) > 3600
                    AND tbl_ticket.status = 0";
$db->query($updateStockBack);

$deleteExpiredTickets = "DELETE FROM tbl_ticket 
                         WHERE TIMESTAMPDIFF(SECOND, created_at, NOW()) > 3600 
                           AND status = 0";
$db->query($deleteExpiredTickets);

$username = $_SESSION["username"];

// Periksa apakah ada permintaan pembayaran
if (isset($_GET['ticket_id']) && isset($_GET['action']) && $_GET['action'] === 'bayar') {
    $ticket_id = $_GET['ticket_id'];

    // Update status pembayaran menjadi "sukses" (misalnya status=1)
    $updateStatus = "UPDATE tbl_ticket 
                     SET status = 1 
                     WHERE ticket_id = ? 
                       AND username = ?";
    $stmt = $db->prepare($updateStatus);
    $stmt->bind_param("ss", $ticket_id, $username);
    $stmt->execute();
    $stmt->close();

    // Kurangi stok di tbl_konser
    $reduceStock = "UPDATE tbl_konser 
                    JOIN tbl_ticket 
                    ON tbl_konser.nama_konser = tbl_ticket.nama_konser COLLATE utf8mb4_unicode_ci
                    SET tbl_konser.stok = tbl_konser.stok - tbl_ticket.ticket_quantity
                    WHERE tbl_ticket.ticket_id = ? AND tbl_ticket.status = 1";
    $stmt = $db->prepare($reduceStock);
    $stmt->bind_param("s", $ticket_id);
    if ($stmt->execute()) {
        // Pengurangan stok berhasil
    } else {
        die("Error memperbarui stok: " . $stmt->error);
    }
    $stmt->close();

    // Redirect ke halaman tiket saya
    header("Location: my_tickets.php");
    exit();
}

// Perbaiki query untuk mengambil tiket yang masih valid
$sql = "SELECT 
            tbl_ticket.ticket_id AS id,
            tbl_ticket.nama_konser, 
            tbl_ticket.fullname,
            tbl_ticket.email,
            tbl_ticket.ticket_quantity,
            (tbl_ticket.ticket_quantity * tbl_konser.harga_tiket) AS total_price,
            tbl_ticket.status,
            tbl_ticket.created_at,
            TIMESTAMPDIFF(SECOND, tbl_ticket.created_at, NOW()) AS time_elapsed
        FROM tbl_ticket
        JOIN tbl_konser 
        ON tbl_ticket.nama_konser COLLATE utf8mb4_unicode_ci = tbl_konser.nama_konser COLLATE utf8mb4_unicode_ci
        WHERE tbl_ticket.username = ? 
        ORDER BY tbl_ticket.created_at DESC";

$stmt = $db->prepare($sql);

if (!$stmt) {
    die("Error dalam persiapan kueri: " . $db->error);
}

$stmt->bind_param("s", $username);
$stmt->execute();
$result = $stmt->get_result();

$tickets = [];
if ($result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
        $tickets[] = $row;
    }
}

$stmt->close();
$db->close();
?>

<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Tiket Saya - Pemesanan Tiket Konser</title>
    <link rel="stylesheet" href="css/my_tickets.css">
    <link rel="stylesheet" href="css/user_dashboard.css">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet"
        integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
</head>

<body>
    <?php include('navbar.php'); ?>

    <main class="container my-4">
        <section class="my-tickets">
            <h2 class="text-center mb-4">Riwayat Tiket Anda</h2>
            <div class="table-responsive">
                <table class="table table-striped table-bordered">
                    <thead class="table-dark">
                        <tr>
                            <th>No.</th>
                            <th>Nama Konser</th>
                            <th>Nama Pemesan</th>
                            <th>Email</th>
                            <th>Jumlah Tiket</th>
                            <th>Total Harga</th>
                            <th>Status</th>
                            <th>Durasi Pembayaran</th>
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
                                    <td><?= htmlspecialchars($ticket['ticket_quantity']); ?></td>
                                    <td>Rp <?= number_format($ticket['total_price'], 0, ',', '.'); ?></td>
                                    <td class="<?= $ticket['status'] == 1 ? 'text-success fw-bold' : 'text-danger fw-bold'; ?>">
                                        <?= $ticket['status'] == 1 ? 'Sudah Bayar' : 'Belum Bayar'; ?>
                                    </td>
                                    <td id="countdown-<?= htmlspecialchars($ticket['id']); ?>">
                                        <?= $ticket['status'] == 0 ? '' : 'Sudah Bayar'; ?>
                                    </td>
                                    <td>
                                        <?php if ($ticket['status'] == 0): ?>
                                            <a href="pembayaran.php?ticket_id=<?= urlencode($ticket['id']); ?>&action=bayar" class="btn btn-primary">Bayar</a>
                                        <?php endif; ?>
                                    </td>
                                </tr>
                            <?php endforeach; ?>
                        <?php else: ?>
                            <tr>
                                <td colspan="9" class="text-center">Belum ada tiket yang dipesan atau dibayar.</td>
                            </tr>
                        <?php endif; ?>
                    </tbody>
                </table>
            </div>
        </section>
    </main>

    <script>
        const tickets = <?= json_encode($tickets); ?>;

        tickets.forEach(ticket => {
            const countdownElement = document.getElementById(`countdown-${ticket.id}`);
            if (ticket.status === 0) {
                const createdAt = new Date(ticket.created_at);
                const paymentDeadline = new Date(createdAt.getTime() + 3600 * 1000);

                function updateCountdown() {
                    const now = new Date();
                    const timeLeft = paymentDeadline - now;

                    if (timeLeft <= 0) {
                        countdownElement.innerText = "Waktu Habis";
                        countdownElement.closest('tr').style.display = 'none';
                    } else {
                        const minutes = Math.floor(timeLeft / 60000);
                        const seconds = Math.floor((timeLeft % 60000) / 1000);
                        countdownElement.innerText = `${minutes}:${seconds < 10 ? '0' : ''}${seconds}`;
                    }
                }

                updateCountdown();
                setInterval(updateCountdown, 1000);
            }
        });
    </script>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"
        integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz"
        crossorigin="anonymous"></script>
</body>

</html>
