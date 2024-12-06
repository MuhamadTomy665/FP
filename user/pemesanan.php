<?php
session_start(); // Memulai sesi

// Koneksi ke database
include('../service/database.php');

// Cek apakah pengguna sudah login
if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}

// Mengambil data konser dari database
$query = "SELECT * FROM tbl_konser";
$result = $db->query($query);
$concerts = $result->fetch_all(MYSQLI_ASSOC);

// Proses pemesanan tiket
if (isset($_POST['book_ticket'])) {
    // Ambil data dari form
    $user_id = $_SESSION['user_id'];
    $username = $_SESSION['username']; // Ambil username dari sesi
    $concert = $_POST['concert'];
    $email = $_POST['email'];
    $fullname = $_POST['fullname'];
    $nik = $_POST['nik'];
    $ticket_quantity = $_POST['ticket-quantity'];

    // Cari harga tiket konser dan stok tiket
    $stmt_concert = $db->prepare("SELECT harga_tiket, stok FROM tbl_konser WHERE nama_konser = ?");
    $stmt_concert->bind_param("s", $concert);
    $stmt_concert->execute();
    $stmt_concert->bind_result($ticket_price, $stok_tiket);
    $stmt_concert->fetch();
    $stmt_concert->close();

    // Cek apakah NIK sudah digunakan untuk konser yang sama
    $stmt_nik_check = $db->prepare("SELECT COUNT(*) FROM tbl_ticket WHERE nik = ? AND nama_konser = ?");
    $stmt_nik_check->bind_param("ss", $nik, $concert);
    $stmt_nik_check->execute();
    $stmt_nik_check->bind_result($nik_count);
    $stmt_nik_check->fetch();
    $stmt_nik_check->close();

    // Validasi stok tiket dan NIK
    if ($ticket_quantity > $stok_tiket) {
        echo "<script>alert('Stok tiket tidak mencukupi.');</script>";
    } elseif (strlen($nik) != 16 || !is_numeric($nik)) { // Validasi NIK
        echo "<script>alert('NIK harus terdiri dari 16 digit angka.');</script>";
    } elseif ($nik_count > 0) { // Cek apakah NIK sudah digunakan
        echo "<script>alert('NIK sudah digunakan untuk konser ini.');</script>";
    } else {
        // Hitung total harga
        $total_price = $ticket_price * $ticket_quantity;

        // Masukkan data ke tbl_ticket
        $stmt = $db->prepare("INSERT INTO tbl_ticket (username, nama_konser, email, fullname, nik, ticket_quantity, total_price) VALUES (?, ?, ?, ?, ?, ?, ?)");
        $stmt->bind_param("ssssiid", $username, $concert, $email, $fullname, $nik, $ticket_quantity, $total_price);

        if ($stmt->execute()) {
            // Kurangi stok tiket di tbl_konser
            $stmt_update_stock = $db->prepare("UPDATE tbl_konser SET stok = stok - ? WHERE nama_konser = ?");
            $stmt_update_stock->bind_param("is", $ticket_quantity, $concert);
            $stmt_update_stock->execute();
            $stmt_update_stock->close();

            // Jika pemesanan sukses, tampilkan popup dengan SweetAlert2
            echo "<script>
                    Swal.fire({
                        title: 'Sukses!',
                        text: 'Pemesanan tiket berhasil!',
                        icon: 'success',
                        confirmButtonText: 'OK'
                    }).then(function() {
                        window.location.href = 'my_tickets.php'; // Redirect ke halaman my_tickets.php setelah klik OK
                    });
                </script>";
        } else {
            // Tambahkan log error untuk debugging
            echo "<script>alert('Gagal memesan tiket. Error: " . htmlspecialchars($stmt->error) . "');</script>";
        }

        $stmt->close();
    }
}
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Pemesanan Tiket Konser</title>
    <link rel="stylesheet" href="css/home_pemesanan_tiket.css">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet"
        integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
    <!-- Menambahkan SweetAlert2 -->
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <style>
        /* General Style */
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: Arial, sans-serif;
            min-height: 100vh;
            display: flex;
            flex-direction: column;
        }

        /* Main Content Style */
        .main-content {
            flex: 1;
            padding: 20px;
            background-color: #f9f9f9;
        }

        header {
            margin-bottom: 20px;
        }

        header h1 {
            font-size: 1.8rem;
            color: #333;
        }

        /* Form Style */
        form {
            background-color: #fff;
            padding: 20px;
            border-radius: 8px;
            box-shadow: 0 2px 5px rgba(0, 0, 0, 0.1);
        }

        form label {
            display: block;
            margin-bottom: 5px;
            font-weight: bold;
        }

        form input,
        form select {
            width: 100%;
            padding: 10px;
            margin-bottom: 15px;
            border: 1px solid #ccc;
            border-radius: 5px;
            font-size: 1rem;
        }

        form button {
            width: 100%;
            padding: 10px;
            background-color: #007bff;
            color: #fff;
            border: none;
            border-radius: 5px;
            font-size: 1rem;
            cursor: pointer;
        }

        form button:hover {
            background-color: #0056b3;
        }
    </style>
</head>

<body>
    <?php include('navbar.php'); ?>

    <!-- Main Content -->
    <main class="main-content">
        <section class="ticket-booking">
            <h2>Pemesanan Tiket Konser</h2>
            <form method="post" action="">
                <label for="username">Username:</label>
                <input type="text" id="username" name="username" value="<?= htmlspecialchars($_SESSION['username']); ?>"
                    readonly required>
                
                <label for="concert">Pilih Konser:</label>
                <select id="concert" name="concert" required>
                    <option value="" disabled selected>Pilih konser</option>
                    <?php foreach ($concerts as $concert): ?>
                        <option value="<?= htmlspecialchars($concert['nama_konser']); ?>">
                            <?= htmlspecialchars($concert['nama_konser']); ?> - Rp
                            <?= number_format($concert['harga_tiket'], 0, ',', '.'); ?>
                        </option>
                    <?php endforeach; ?>
                </select>
                
                <label for="email">Email:</label>
                <input type="email" id="email" name="email" placeholder="Masukkan email Anda" required>
                
                <label for="fullname">Nama Lengkap:</label>
                <input type="text" id="fullname" name="fullname" placeholder="Masukkan nama lengkap Anda" required>
                
                <label for="nik">NIK:</label>
                <input type="text" id="nik" name="nik" placeholder="Masukkan NIK Anda" pattern="\d{16}" maxlength="16"
                    required>
                
                <label for="ticket-quantity">Jumlah Tiket:</label>
                <input type="number" id="ticket-quantity" name="ticket-quantity" min="1" required>
                
                <div>
                    <input type="checkbox" id="confirm-details" name="confirm-details" required>
                    <label for="confirm-details">Saya telah memastikan bahwa data sesuai dengan KTP saya.</label>
                </div>
                
                <button type="submit" name="book_ticket">Pesan Tiket</button>
            </form>
        </section>
    </main>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"
        integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz"
        crossorigin="anonymous"></script>

    <?php include "footer.php";?>
</body>
</html>
