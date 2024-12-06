<?php
include "service/database.php";
session_start();
$login_message = "";

// Periksa jika ada pesan logout
if (isset($_SESSION['logout_message'])) {
    $login_message = $_SESSION['logout_message'];
    unset($_SESSION['logout_message']); // Hapus pesan setelah ditampilkan
}

if (isset($_POST['login'])) {
    $username = $_POST['username'];
    $password = $_POST['password'];
    $hash_password = hash('sha256', $password); // Enkripsi password dengan SHA-256

    // Query login untuk mencari user berdasarkan username dan password
    $stmt = $db->prepare("SELECT id, username, password, role FROM tbl_user WHERE username = ? AND password = ?");
    $stmt->bind_param("ss", $username, $hash_password);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        // Ambil data user yang ditemukan
        $data = $result->fetch_assoc();
        
        // Menyimpan informasi user ke dalam session
        $_SESSION["user_id"] = $data["id"];  // Menyimpan user_id ke session
        $_SESSION["username"] = $data["username"];
        $_SESSION["role"] = $data["role"];
        $_SESSION["is_login"] = true;

        // Redirect berdasarkan role
        if ($data["role"] == 0) {
            // Jika role user = 0, redirect ke dashboard user
            header("Location: index.php");
        } elseif ($data["role"] == 1) {
            // Jika role admin = 1, redirect ke dashboard admin
            header("Location: admin/home_admin.php");
        }
        exit();
    } else {
        $login_message = "Username atau password salah!";
    }

    $stmt->close();
    $db->close();
}
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Halaman Login - Konser</title>
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

        /* Login Container Styling */
        .login-container {
            background: rgba(0, 0, 0, 0.8);
            color: #fff;
            padding: 30px;
            border-radius: 10px;
            width: 100%;
            max-width: 400px;
            text-align: center;
            box-shadow: 0px 5px 15px rgba(0, 0, 0, 0.5);
        }

        .login-container h3 {
            font-family: 'Arial', sans-serif;
            font-weight: bold;
            margin-bottom: 20px;
            text-transform: uppercase;
            color: #ffc107;
        }

        .login-container input {
            width: 100%;
            padding: 10px;
            margin: 10px 0;
            border-radius: 5px;
            border: 1px solid #ccc;
        }

        .login-container .password-container {
            display: flex;
            align-items: center;
            position: relative;
        }

        .login-container .eye-icon {
            background: none;
            border: none;
            position: absolute;
            right: 10px;
            font-size: 16px;
            color: #ffc107;
            cursor: pointer;
        }

        .login-container .login-btn {
            width: 100%;
            padding: 10px;
            background: #ffc107;
            border: none;
            color: #000;
            border-radius: 5px;
            font-weight: bold;
            cursor: pointer;
            text-transform: uppercase;
        }

        .login-container .login-btn:hover {
            background: #ffa000;
        }

        .login-container .register-link {
            margin-top: 20px;
            color: #fff;
        }

        .login-container .register-link a {
            color: #ffc107;
            text-decoration: none;
        }

        .login-container .register-link a:hover {
            text-decoration: underline;
        }
    </style>
</head>

<body>
    <div class="login-container">
        <h3>Masuk ke Akun Anda</h3>
        <form action="login.php" method="POST">
            <input type="text" name="username" placeholder="Username" required>
            <div class="password-container">
                <input type="password" id="password" name="password" placeholder="Password" required>
                <button type="button" class="eye-icon" id="toggle-password">👁️</button>
            </div>
            <button type="submit" name="login" class="login-btn">Masuk Sekarang</button>
        </form>
        <p class="register-link">Belum punya akun? <a href="register.php">Daftar di sini</a></p>
    </div>

    <!-- JavaScript untuk toggle password -->
    <script>
        const togglePassword = document.getElementById('toggle-password');
        const passwordField = document.getElementById('password');

        togglePassword.addEventListener('click', function () {
            const type = passwordField.type === 'password' ? 'text' : 'password';
            passwordField.type = type;
            this.textContent = type === 'password' ? '👁️' : '🙈';
        });
    </script>
</body>

</html>
