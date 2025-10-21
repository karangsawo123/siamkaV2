<?php
define("SECURE", true);

if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

include '../../includes/auth_check.php';
include '../../includes/role_check.php';
include '../../includes/notification_helper.php';
include '../../config/config.php';
include '../../config/database.php';

checkRole(['admin']);

// Pastikan id kategori ada
if (!isset($_GET['id'])) {
    die("❌ ID kategori tidak ditemukan.");
}

$id_kategori = $_GET['id'];

// Ambil data kategori
$stmt = $conn->prepare("SELECT * FROM categories WHERE id_kategori = ?");
$stmt->bind_param("i", $id_kategori);
$stmt->execute();
$result = $stmt->get_result();
$kategori = $result->fetch_assoc();

if (!$kategori) {
    die("❌ Data kategori tidak ditemukan.");
}

// === PROSES POST DITARUH SEBELUM OUTPUT ===
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $nama_kategori = trim($_POST['nama_kategori']);
    $deskripsi = trim($_POST['deskripsi']);

    // Cek nama unik
    $check = $conn->prepare("SELECT id_kategori FROM categories WHERE nama_kategori = ? AND id_kategori != ?");
    $check->bind_param("si", $nama_kategori, $id_kategori);
    $check->execute();
    $check_result = $check->get_result();

    if ($check_result->num_rows > 0) {
        set_notification('error', '❌ Nama kategori sudah digunakan oleh kategori lain.');
    } else {
        $update = $conn->prepare("UPDATE categories SET nama_kategori = ?, deskripsi = ? WHERE id_kategori = ?");
        $update->bind_param("ssi", $nama_kategori, $deskripsi, $id_kategori);

        if ($update->execute()) {
            set_notification('success', '✅ Data kategori berhasil diperbarui.');
        } else {
            set_notification('error', '❌ Gagal memperbarui data kategori.');
        }
    }

    // Redirect sebelum ada HTML
    header('Location: index.php');
    exit;
}

// === BARU MULAI OUTPUT HTML ===
include '../../includes/header.php';
include '../../includes/sidebar.php';
?>

<main class="main-content">
  <?php display_notification(); ?>

  <div class="card">
    <h1 class="page-title">Edit Kategori</h1>

    <form method="POST" class="form-container">
      <div class="form-group">
        <label>Nama Kategori</label>
        <input type="text" name="nama_kategori" value="<?= htmlspecialchars($kategori['nama_kategori']); ?>" required>
      </div>

      <div class="form-group">
        <label>Deskripsi</label>
        <textarea name="deskripsi" rows="3"><?= htmlspecialchars($kategori['deskripsi']); ?></textarea>
      </div>

      <div class="form-actions">
        <button type="submit" class="btn btn-primary">Simpan</button>
        <a href="index.php" class="btn btn-secondary">Batal</a>
      </div>
    </form>
  </div>
</main>

<?php include '../../includes/footer.php'; ?>
