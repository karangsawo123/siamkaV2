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
            $_SESSION['user_id'] = $user['id'];
            $_SESSION['role'] = $user['role'];
            $_SESSION['nama'] = $user['nama'];

            redirect('../../modules/dashboard/index.php');
        } else {
            $message = "<p class='error'>⚠️ Password salah.</p>";
        }
    } else {
        $message = "<p class='error'>⚠️ Email tidak ditemukan atau akun tidak aktif.</p>";
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
    <form method="POST">
        <h2>Login SIAMKA</h2>
        <?= $message ?>
        <label>Email</label>
        <input type="email" name="email" placeholder="Masukkan email" required>

        <label>Password</label>
        <input type="password" name="password" placeholder="Masukkan password" required>

        <button type="submit">Login</button>

        <p style="text-align:center; margin-top:10px;">
            Belum punya akun? 
            <a href="register.php">Daftar di sini</a>
        </p>
    </form>
</body>
</html>
