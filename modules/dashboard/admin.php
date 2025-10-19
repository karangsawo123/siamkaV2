<?php
// Izinkan akses ke file middleware
define("SECURE", true);

// Pastikan session aktif
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// Include file penting
require_once '../../includes/auth_check.php';
require_once '../../includes/role_check.php';
require_once '../../config/config.php';
require_once '../../includes/header.php';
require_once '../../includes/sidebar.php';

// Cek role user (hanya Admin)
checkRole(['admin']);
?>

<main class="main-content">
  <h1>Dashboard Admin</h1>
  <p>Selamat datang, <b><?= htmlspecialchars($_SESSION['nama']); ?></b>! Anda login sebagai <b>Admin</b>.</p>

  <div class="grid-container">
    <!-- Card Total Aset -->
    <div class="card">
      <h3>Total Aset</h3>
      <p><b>124</b> aset terdaftar</p>
    </div>

    <!-- Card Aset Rusak -->
    <div class="card">
      <h3>Aset Rusak</h3>
      <p><b>8</b> aset sedang dalam perbaikan</p>
    </div>

    <!-- Card Pengguna -->
    <div class="card">
      <h3>Jumlah Pengguna</h3>
      <p><b>32</b> pengguna aktif</p>
    </div>

    <!-- Card Aktivitas Terbaru -->
    <div class="card">
      <h3>Aktivitas Terbaru</h3>
      <p>Terdapat 3 aset baru yang ditambahkan hari ini.</p>
    </div>
  </div>

  <div class="card">
    <h3>Informasi Sistem</h3>
    <p>Halaman ini masih dalam tahap pengembangan. Fitur-fitur admin akan ditambahkan di Sprint berikutnya, seperti:
      <ul>
        <li>Manajemen data aset</li>
        <li>Pengelolaan akun pengguna</li>
        <li>Laporan aset bulanan</li>
      </ul>
    </p>
  </div>
</main>

<?php require_once '../../includes/footer.php'; ?>
