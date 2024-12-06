<?php
session_start(); // Memulai sesi

// Koneksi ke database
include "service/database.php";

// Ambil data konser dari database
$query = "SELECT * FROM tbl_konser";
$result = $db->query($query);
?>
<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard User - Home</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css" rel="stylesheet">
    <style>
        /* Carousel */
        .carousel-inner img {
            max-height: 500px;
            object-fit: cover;
        }

        /* Event Cards */
        .event-card {
            margin: 15px;
            border: 1px solid #ddd;
            border-radius: 10px;
            overflow: hidden;
            transition: transform 0.3s ease;
        }

        .event-card:hover {
            transform: scale(1.05);
            box-shadow: 0 4px 10px rgba(0, 0, 0, 0.2);
        }

        .event-card img {
            width: 100%;
            height: 200px;
            object-fit: cover;
        }

        .event-card-body {
            padding: 15px;
            text-align: center;
        }

        .event-card-body h5 {
            font-size: 1.2rem;
            font-weight: bold;
        }

        .card-container {
            display: grid;
            grid-template-columns: repeat(auto-fill, minmax(250px, 1fr));
            gap: 20px;
            padding: 20px;
        }

        /* Footer */
        .footer {
            background-color: #2c3e50;
            color: #ecf0f1;
            padding: 20px 0;
        }

        .footer p {
            margin: 0;
            font-size: 0.9rem;
        }

        /* Sold Out Badge */
        .sold-out-badge {
            display: inline-block;
            padding: 5px 10px;
            background-color: #e74c3c;
            color: white;
            font-weight: bold;
            border-radius: 5px;
            margin-top: 10px;
        }
    </style>
</head>

<body>
    <!-- Menggunakan navbar.php -->
   
<nav class="navbar navbar-expand-lg navbar-dark bg-dark shadow-sm">
    <div class="container">
        <a class="navbar-brand d-flex align-items-center" href="#">
            <img src="assets/logo.png" alt="Logo" width="30" height="30" class="me-2">
            <span class="fw-bold">Ticket</span>
        </a>
        <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav"
            aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
            <span class="navbar-toggler-icon"></span>
        </button>
        <div class="collapse navbar-collapse" id="navbarNav">
            <ul class="navbar-nav me-auto">
                <li class="nav-item">
                    <a class="nav-link <?= basename($_SERVER['PHP_SELF']) == 'index.php' ? 'active' : ''; ?>" href="index.php">Home</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link <?= basename($_SERVER['PHP_SELF']) == 'user/pemesanan.php' ? 'active' : ''; ?>" href="user/pemesanan.php">Pemesanan Tiket</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link <?= basename($_SERVER['PHP_SELF']) == 'user/my_ticket.php' ? 'active' : ''; ?>" href="user/my_ticket.php">Riwayat Pemesanan</a>
                </li>
            </ul>
            <ul class="navbar-nav">
                <?php if (isset($_SESSION['user_id'])): ?>
                    <li class="nav-item">
                        <a class="nav-link text-danger" href="logout.php">Logout</a>
                    </li>
                <?php else: ?>
                    <li class="nav-item">
                        <a class="nav-link text-success" href="login.php">Login</a>
                    </li>
                <?php endif; ?>
            </ul>
        </div>
    </div>
</nav>


    <!-- Carousel -->
    <div id="carouselExample" class="carousel slide">
        <div class="carousel-inner">
            <?php
            $isFirst = true;
            $result->data_seek(0);
            while ($row = $result->fetch_assoc()) {
                $imgPath = htmlspecialchars($row['img']);
                echo '<div class="carousel-item ' . ($isFirst ? 'active' : '') . '">';
                echo '<img src="admin/' . $imgPath . '" class="d-block w-100" alt="' . htmlspecialchars($row['nama_konser']) . '">';
                echo '<div class="carousel-caption d-none d-md-block">';
                echo '<h5>' . htmlspecialchars($row['nama_konser']) . '</h5>';
                echo '<p>' . htmlspecialchars($row['lokasi']) . ' - ' . date('d M Y', strtotime($row['tanggal_konser'])) . '</p>';
                echo '</div></div>';
                $isFirst = false;
            }
            ?>
        </div>
        <button class="carousel-control-prev" type="button" data-bs-target="#carouselExample" data-bs-slide="prev">
            <span class="carousel-control-prev-icon" aria-hidden="true"></span>
            <span class="visually-hidden">Previous</span>
        </button>
        <button class="carousel-control-next" type="button" data-bs-target="#carouselExample" data-bs-slide="next">
            <span class="carousel-control-next-icon" aria-hidden="true"></span>
            <span class="visually-hidden">Next</span>
        </button>
    </div>

    <!-- Event Cards -->
    <div class="card-container">
        <?php
        $result->data_seek(0);
        while ($row = $result->fetch_assoc()) {
            $imgPath = htmlspecialchars($row['img']);
            $stokTiket = htmlspecialchars($row['stok']);
            $isSoldOut = $stokTiket == 0; // Cek apakah tiket habis
            echo '<div class="event-card">';
            echo '<img src="admin/' . $imgPath . '" alt="' . htmlspecialchars($row['nama_konser']) . '">';
            echo '<div class="event-card-body">';
            echo '<h5>' . htmlspecialchars($row['nama_konser']) . '</h5>';
            echo '<p>Tanggal: ' . date('d M Y', strtotime($row['tanggal_konser'])) . '</p>';
            echo '<p>Lokasi: ' . htmlspecialchars($row['lokasi']) . '</p>';
            echo '<p>Stok Tiket: ' . ($isSoldOut ? 'Sold Out' : $stokTiket . ' tiket tersedia') . '</p>';

            if ($isSoldOut) {
                // Jika tiket habis, tampilkan "Sold Out" badge
                echo '<span class="sold-out-badge">Sold Out</span>';
            } else {
                // Jika pengguna sudah login, tampilkan tombol pemesanan tiket
                if (isset($_SESSION['user_id'])) {
                    echo '<a href="user/pemesanan.php?event=' . urlencode($row['konser_id']) . '" class="btn btn-primary">Pesan Tiket</a>';
                } else {
                    echo '<a href="login.php" class="btn btn-warning">Login untuk Pesan Tiket</a>';
                }
            }
            echo '</div></div>';
        }
        ?>
    </div>

    <!-- Footer -->
   <?php include "user/footer.php";?>
</body>

</html>
