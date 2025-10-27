<?php
define("SECURE", true); // ✅ wajib sebelum include file lain
session_start();
include '../../includes/auth_check.php';
include '../../includes/role_check.php';
include '../../config/config.php';
include '../../includes/header.php';
include '../../includes/sidebar.php';
?>

<main class="main-content">
  <h1>Dashboard Manajemen Kampus</h1>
  <p>Selamat datang, <b><?= $_SESSION['nama']; ?></b>! Anda login sebagai <b>Manajemen Kampus</b>.</p>
  <div class="card">
    <p>Halaman ini masih kosong — nanti akan menampilkan laporan aset dan statistik kampus.</p>
  </div>
</main>

<?php include '../../includes/footer.php'; ?>
