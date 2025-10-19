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
  <h1>Dashboard Pengguna</h1>
  <p>Halo, <b><?= $_SESSION['nama']; ?></b>! Anda login sebagai <b>Pengguna</b>.</p>
  <div class="card">
    <p>Halaman ini masih kosong — nanti akan berisi daftar aset dan riwayat peminjaman Anda.</p>
  </div>
</main>

<?php include '../../includes/footer.php'; ?>
