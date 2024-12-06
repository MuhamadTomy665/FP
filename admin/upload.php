<?php
// Menyertakan koneksi ke database
require_once __DIR__ . '/../service/database.php';

// Periksa apakah form sudah disubmit
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // Ambil data dari form
    $nama_konser = mysqli_real_escape_string($db, $_POST['nama_konser']);
    $harga_tiket = mysqli_real_escape_string($db, $_POST['harga_tiket']);
    $tanggal_konser = mysqli_real_escape_string($db, $_POST['tanggal_konser']);
    $lokasi = mysqli_real_escape_string($db, $_POST['lokasi']);
    $stok = mysqli_real_escape_string($db, $_POST['stok']);

    // Proses upload gambar
    $target_dir = "img/"; // Folder penyimpanan gambar

    // Cek apakah folder img ada, jika tidak buat folder tersebut
    if (!file_exists($target_dir)) {
        mkdir($target_dir,777, true); // Membuat folder dengan izin 0777
    }

    // Ambil nama file gambar yang diupload
    $image_name = basename($_FILES["img"]["name"]);
    $imageFileType = strtolower(pathinfo($image_name, PATHINFO_EXTENSION));

    // Buat nama file gambar yang unik untuk menghindari tabrakan nama file
    $unique_image_name = uniqid('konser_', true) . '.' . $imageFileType;

    // Path lengkap untuk menyimpan file
    $target_file = $target_dir . $unique_image_name;

    $upload_ok = 1;

    // Periksa apakah file gambar
    $check = getimagesize($_FILES["img"]["tmp_name"]);
    if ($check !== false) {
        $upload_ok = 1;
    } else {
        echo "File bukan gambar.";
        $upload_ok = 0;
    }

    // Periksa ekstensi file
    if (!in_array($imageFileType, ['jpg', 'png', 'jpeg', 'gif'])) {
        echo "Hanya file JPG, JPEG, PNG, & GIF yang diperbolehkan.";
        $upload_ok = 0;
    }

    // Periksa ukuran file (misalnya, batasi ukuran file hingga 5MB)
    if ($_FILES["img"]["size"] > 5000000) {
        echo "File terlalu besar. Maksimal 5MB.";
        $upload_ok = 0;
    }

    // Periksa apakah upload berhasil
    if ($upload_ok && move_uploaded_file($_FILES["img"]["tmp_name"], $target_file)) {
        // Simpan data ke tabel
        $sql = "INSERT INTO tbl_konser (nama_konser, harga_tiket, tanggal_konser, lokasi, stok, img) 
                VALUES ('$nama_konser', '$harga_tiket', '$tanggal_konser', '$lokasi', '$stok', '$target_file')";

        if (mysqli_query($db, $sql)) {
            echo "Data konser berhasil disimpan.";
        } else {
            echo "Error: " . $sql . "<br>" . mysqli_error($db);
        }
    } else {
        echo "Gagal mengupload file gambar. Error: " . $_FILES["img"]["error"];
    }
}

// Tutup koneksi
mysqli_close($db);
?>
