<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard Admin</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha3/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body {
            display: flex;
            min-height: 100vh;
            font-family: Arial, sans-serif;
            background-color: #f8f9fa;
        }

        .main-content {
            flex: 1;
            padding: 20px;
        }

        .form-label {
            font-weight: bold;
        }

        .card {
            border: 1px solid #ddd;
        }
    </style>
</head>

<body>
    <!-- Panggil Sidebar -->
    <?php include "sidebar.php"; ?>

    <!-- Main Content -->
    <div class="main-content">
        <h2 class="mb-4">Kelola Konser</h2>

        <!-- Form Tambah Konser -->
        <div class="card mb-4">
            <div class="card-header">Tambah Konser Baru</div>
            <div class="card-body">
                <form action="upload.php" method="POST" enctype="multipart/form-data">
                    <div class="mb-3">
                        <label for="nama_konser" class="form-label">Nama Konser</label>
                        <input type="text" name="nama_konser" id="nama_konser" class="form-control" required>
                    </div>
                    
                    <!-- Tambahkan Form Harga Tiket -->
                    <div class="mb-3">
                        <label for="harga_tiket" class="form-label">Harga Tiket</label>
                        <input type="number" name="harga_tiket" id="harga_tiket" class="form-control" required>
                    </div>

                    <div class="mb-3">
                        <label for="lokasi" class="form-label">Lokasi</label>
                        <input type="text" name="lokasi" id="lokasi" class="form-control" required>
                    </div>
                    <div class="mb-3">
                        <label for="tanggal_konser" class="form-label">Tanggal Konser</label>
                        <input type="date" name="tanggal_konser" id="tanggal_konser" class="form-control" required>
                    </div>
                    <div class="mb-3">
                        <label for="stok" class="form-label">Stok Tiket</label>
                        <input type="number" name="stok" id="stok" class="form-control" required>
                    </div>
                    <div class="mb-3">
                        <label for="img" class="form-label">Gambar Konser</label>
                        <input type="file" name="img" id="img" class="form-control" required>
                    </div>
                    <button type="submit" name="add_konser" class="btn btn-success">Tambah Konser</button>
                </form>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha3/dist/js/bootstrap.bundle.min.js"></script>
</body>

</html>
