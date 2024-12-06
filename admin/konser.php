<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Form Tambah Konser</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha3/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body {
            background-color: #f8f9fa;
        }

        .form-container {
            background-color: #ffffff;
            padding: 30px;
            margin: 50px auto;
            border-radius: 10px;
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
            max-width: 600px;
        }

        .form-container h3 {
            font-family: 'Arial', sans-serif;
            font-weight: bold;
            margin-bottom: 20px;
            text-transform: uppercase;
            color: #007bff;
        }

        .btn-primary {
            background-color: #007bff;
            border: none;
        }

        .btn-primary:hover {
            background-color: #0056b3;
        }

        .form-control {
            margin-bottom: 15px;
        }
    </style>
</head>

<body>
    <div class="container">
        <div class="form-container">
            <h3>Tambah Data Konser</h3>
            <form action="upload.php" method="POST" enctype="multipart/form-data">
                <div class="mb-3">
                    <label for="nama_konser" class="form-label">Nama Konser</label>
                    <input type="text" class="form-control" id="nama_konser" name="nama_konser" placeholder="Masukkan Nama Konser" required>
                </div>
                <div class="mb-3">
                    <label for="harga_tiket" class="form-label">Harga Tiket</label>
                    <input type="number" class="form-control" id="harga_tiket" name="harga_tiket" placeholder="Masukkan Harga Tiket" required>
                </div>
                <div class="mb-3">
                    <label for="tanggal_konser" class="form-label">Tanggal Konser</label>
                    <input type="date" class="form-control" id="tanggal_konser" name="tanggal_konser" required>
                </div>
                <div class="mb-3">
                    <label for="lokasi" class="form-label">Lokasi Konser</label>
                    <input type="text" class="form-control" id="lokasi" name="lokasi" placeholder="Masukkan Lokasi Konser" required>
                </div>
                <div class="mb-3">
                    <label for="stok_tiket" class="form-label">Stok Tiket</label>
                    <input type="number" class="form-control" id="stok" name="stok" placeholder="Masukkan Stok Tiket" required>
                </div>
                <div class="mb-3">
                    <label for="gambar_konser" class="form-label">Gambar Konser</label>
                    <input type="file" class="form-control" id="img" name="img" accept="image/*" required>
                </div>
                <button type="submit" class="btn btn-primary w-100">Simpan Data Konser</button>
            </form>
        </div>
    </div>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha3/dist/js/bootstrap.bundle.min.js"></script>
</body>

</html>
