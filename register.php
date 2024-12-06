<?php
include "service/database.php"; // Hubungkan ke database
session_start();
$register_message = "";

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    // Ambil data dari form
    $username = trim($_POST['username']);
    $password = $_POST['password'];
    $confirm_password = $_POST['confirmPassword'];

    // Validasi input
    if (empty($username) || empty($password) || empty($confirm_password)) {
        $register_message = "Semua field harus diisi!";
    } elseif ($password !== $confirm_password) {
        $register_message = "Password tidak cocok!";
    } else {
        // Hash password
        $hash_password = hash('sha256', $password);

        // Periksa apakah username sudah ada
        $stmt = $db->prepare("SELECT id FROM tbl_user WHERE username = ?");
        if (!$stmt) {
            die("Query gagal: " . $db->error);
        }

        $stmt->bind_param("s", $username);
        $stmt->execute();
        $stmt->store_result();

        if ($stmt->num_rows > 0) {
            $register_message = "Username sudah digunakan!";
        } else {
            // Masukkan data ke database
            $stmt = $db->prepare("INSERT INTO tbl_user (username, password, role) VALUES (?, ?, ?)");
            if (!$stmt) {
                die("Query gagal: " . $db->error);
            }

            $role = "user"; // Role default
            $stmt->bind_param("sss", $username, $hash_password, $role);

            if ($stmt->execute()) {
                // Registrasi berhasil
                $_SESSION["register_success"] = "Registrasi berhasil! Silakan login.";
                header("Location: login.php");
                exit();
            } else {
                $register_message = "Terjadi kesalahan, coba lagi!";
            }
        }
        $stmt->close();
    }
    $db->close();
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Form Registrasi - Konser</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha3/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        /* Background Styling */
        body {
            background: url('assets/bg.jpg') no-repeat center center/cover;
            min-height: 100vh;
            display: flex;
            justify-content: center;
            align-items: center;
            margin: 0;
        }

        /* Form Container Styling */
        .form-container {
            background: rgba(0, 0, 0, 0.8);
            color: #fff;
            padding: 30px;
            border-radius: 10px;
            width: 100%;
            max-width: 400px;
            text-align: center;
            box-shadow: 0px 5px 15px rgba(0, 0, 0, 0.5);
        }

        .form-container h2 {
            font-family: 'Arial', sans-serif;
            font-weight: bold;
            margin-bottom: 20px;
            text-transform: uppercase;
            color: #ffc107;
        }

        .form-group {
            margin-bottom: 15px;
            text-align: left;
        }

        .form-group label {
            display: block;
            margin-bottom: 5px;
            font-size: 14px;
            color: #ccc;
        }

        .form-group input {
            width: 100%;
            padding: 10px;
            border-radius: 5px;
            border: 1px solid #ccc;
            font-size: 14px;
        }

        button {
            width: 100%;
            padding: 10px;
            font-size: 16px;
            color: #000;
            background-color: #ffc107;
            border: none;
            border-radius: 5px;
            cursor: pointer;
            text-transform: uppercase;
        }

        button:hover {
            background-color: #ffa000;
        }

        .form-footer {
            margin-top: 15px;
            font-size: 14px;
        }

        .form-footer a {
            color: #ffc107;
            text-decoration: none;
        }

        .form-footer a:hover {
            text-decoration: underline;
        }

        .toggle-password-btn {
            width: 100%;
            margin-bottom: 20px;
            background-color: #f0f0f0;
            border: none;
            color: #333;
            cursor: pointer;
            font-size: 14px;
            padding: 10px;
            border-radius: 5px;
        }

        .toggle-password-btn:hover {
            background-color: #e0e0e0;
        }
    </style>
</head>

<body>
    <div class="form-container">
        <h2>Daftar Akun</h2>
        <?php if (!empty($register_message)): ?>
            <p style="color: red;"><?php echo htmlspecialchars($register_message); ?></p>
        <?php endif; ?>
        <form id="registrationForm" method="POST" action="">
            <div class="form-group">
                <label for="username">Username</label>
                <input type="text" id="username" name="username" placeholder="Username" required>
            </div>
            <div class="form-group">
                <label for="password">Password</label>
                <input type="password" id="password" name="password" placeholder="Password" required>
            </div>
            <div class="form-group">
                <label for="confirmPassword">Konfirmasi Password</label>
                <input type="password" id="confirmPassword" name="confirmPassword" placeholder="Konfirmasi Password" required>
            </div>
            <button type="button" class="toggle-password-btn" onclick="togglePasswords()">Lihat Password</button>
            <button type="submit" name="register">Daftar Sekarang</button>
        </form>
        <div class="form-footer">
            Sudah punya akun? <a href="login.php">Masuk di sini</a>
        </div>
    </div>

    <script>
        function togglePasswords() {
            const passwordField = document.getElementById('password');
            const confirmPasswordField = document.getElementById('confirmPassword');
            const toggleButton = document.querySelector('.toggle-password-btn');

            const isPasswordHidden = passwordField.type === 'password';
            passwordField.type = isPasswordHidden ? 'text' : 'password';
            confirmPasswordField.type = isPasswordHidden ? 'text' : 'password';

            toggleButton.textContent = isPasswordHidden ? 'Sembunyikan Password' : 'Lihat Password';
        }
    </script>
</body>

</html>
