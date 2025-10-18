<?php
session_start();

// Cek apakah user sudah login
if (!isset($_SESSION['user_id'])) {
    header("Location: ../auth/login.php");
    exit;
}

// Fungsi untuk memeriksa role
function checkRole($allowed_roles = []) {
    if (!in_array($_SESSION['role'], $allowed_roles)) {
        echo "<h2>Akses ditolak!</h2>";
        echo "<p>Anda tidak memiliki izin untuk mengakses halaman ini.</p>";
        echo '<a href="../auth/logout.php">Kembali ke Login</a>';
        exit;
    }
}
?>
