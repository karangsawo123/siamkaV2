<?php
session_start();
include '../../config/database.php';
include '../../includes/functions.php';

$message = '';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $email = mysqli_real_escape_string($conn, $_POST['email']);
    $password = $_POST['password'];

    $result = $conn->query("SELECT * FROM users WHERE email='$email' AND status='aktif'");

    if ($result->num_rows > 0) {
        $user = $result->fetch_assoc();

        if (password_verify($password, $user['password'])) {
            // Simpan data user ke session
            $_SESSION['user_id'] = $user['id_user'];
            $_SESSION['role']    = $user['role'];
            $_SESSION['nama']    = $user['nama'];

            redirect('../../modules/dashboard/index.php');
            exit;
        } else {
            $message = "<div class='alert error'>⚠️ Password salah.</div>";
        }
    } else {
        $message = "<div class='alert error'>⚠️ Email tidak ditemukan atau akun tidak aktif.</div>";
    }
}
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Login - SIAMKA</title>
    <link rel="stylesheet" href="../../assets/css/auth.css">
</head>
<body>
    <div class="overlay"></div>
    <div class="login-container">
        <img src="../../assets/images/icons/logo.png" alt="Logo SIAMKA">
        <h1>SIAMKA</h1>
        <p>Sistem Aset Manajemen Kampus</p>

        <form method="POST">
            <?= $message ?>
            <input type="email" name="email" placeholder="Email" required>
            <input type="password" name="password" placeholder="Password" required>
            <button type="submit">Masuk</button>
        </form>

        <p style="text-align:center; margin-top:15px;">
            Belum punya akun?
            <a href="register.php" style="color:#007bff; text-decoration:none; font-weight:500;">
                Klik di sini untuk mendaftar
            </a>
        </p>

        <div class="footer">
            <p>© 2025 SIAMKA – Universitas Hogwarts</p>
        </div>
    </div>
</body>
</html>
