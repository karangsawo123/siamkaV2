<?php

require_once __DIR__ . '/../../config/database.php';
require_once __DIR__ . '/../../includes/functions.php';


$message = "";

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $nama = trim($_POST['nama']);
    $email = trim($_POST['email']);
    $password = trim($_POST['password']);
    $no_telp = trim($_POST['telepon']);
    $role = 'pengguna'; // 🔒 otomatis jadi pengguna

    if (empty($nama) || empty($email) || empty($password) || empty($no_telp)) {
        $message = "<div class='error'>Semua field wajib diisi!</div>";
    } else {
        // cek email sudah ada atau belum
        $stmt = $conn->prepare("SELECT * FROM users WHERE email = ?");
        $stmt->bind_param("s", $email);
        $stmt->execute();
        $result = $stmt->get_result();

        if ($result->num_rows > 0) {
            $message = "<div class='error'>Email sudah terdaftar!</div>";
        } else {
            $hashedPassword = password_hash($password, PASSWORD_DEFAULT);

            $stmt = $conn->prepare("INSERT INTO users (nama, email, password, no_telp, role) VALUES (?, ?, ?, ?, ?)");
            $stmt->bind_param("sssss", $nama, $email, $hashedPassword, $no_telp, $role);

            if ($stmt->execute()) {
                $message = "<div class='success'>Registrasi berhasil! Silakan <a href='login.php'>login</a>.</div>";
            } else {
                $message = "<div class='error'>Terjadi kesalahan: " . $stmt->error . "</div>";
            }
        }
    }
}
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Register - SIAMKA</title>
    <link rel="stylesheet" href="../../assets/css/auth.css">
</head>
<body>
    <form method="POST" action="">
        <h2>Registrasi Pengguna</h2>
        <?= $message ?>
        <label>Nama Lengkap</label>
        <input type="text" name="nama" placeholder="Masukkan nama lengkap" required>

        <label>Email</label>
        <input type="email" name="email" placeholder="Masukkan email" required>

        <label>No. Telepon</label>
        <input type="text" name="telepon" placeholder="Masukkan no telepon" required>

        <label>Password</label>
        <input type="password" name="password" placeholder="Masukkan password" required>

        <input type="hidden" name="role" value="pengguna">

        <input type="submit" value="Daftar">
    </form>
</body>
</html>
