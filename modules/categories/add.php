<?php
define("SECURE", true);

if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// Include helper & config, tapi JANGAN include header/sidebar dulu
include '../../includes/auth_check.php';
include '../../includes/role_check.php';
include '../../includes/notification_helper.php';
include '../../config/config.php';
include '../../config/database.php';

// Batasi akses hanya untuk admin
checkRole(['admin']);

// Proses tambah kategori (sebelum output HTML!)
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $nama = trim($_POST['nama_kategori']);
    $desc = trim($_POST['deskripsi']);

    if ($nama === '') {
        set_notification('error', '❌ Nama kategori wajib diisi.');
        header('Location: add.php');
        exit;
    } else {
        $stmt = $conn->prepare("INSERT INTO categories (nama_kategori, deskripsi) VALUES (?, ?)");
        $stmt->bind_param('ss', $nama, $desc);

        if ($stmt->execute()) {
            set_notification('success', '✅ Kategori baru berhasil ditambahkan!');
        } else {
            set_notification('error', '❌ Gagal menyimpan data kategori.');
        }

        header('Location: index.php');
        exit;
    }
}

// BARU include tampilan, setelah semua kemungkinan header selesai
include '../../includes/header.php';
include '../../includes/sidebar.php';
?>

<main class="main-content">

  <?php display_notification(); ?>

  <div class="card">
    <h1 class="page-title">Tambah Kategori</h1>

    <form method="POST" class="form-container">

      <div class="form-group">
        <label>Nama Kategori</label>
        <input type="text" name="nama_kategori" required>
      </div>

      <div class="form-group">
        <label>Deskripsi</label>
        <textarea name="deskripsi" rows="4"></textarea>
      </div>

      <div class="form-actions">
        <button type="submit" class="btn btn-primary">Simpan</button>
        <a href="index.php" class="btn btn-secondary">Batal</a>
      </div>

    </form>
  </div>
</main>

<?php include '../../includes/footer.php'; ?>
