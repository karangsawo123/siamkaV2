<?php
define("SECURE", true);
if (session_status() === PHP_SESSION_NONE) session_start();

require_once '../../includes/auth_check.php';
require_once '../../includes/role_check.php';
require_once '../../config/config.php';
require_once '../../config/database.php';
require_once '../../includes/notification_helper.php';

checkRole(['admin', 'manajemen']);

$id_laporan = $_GET['id'] ?? null;

if (!$id_laporan) {
  header("Location: index.php");
  exit();
}

// Ambil data laporan
$query = "
  SELECT dr.*, a.nama_aset, a.kode_aset, u.nama
  FROM damage_reports dr
  LEFT JOIN assets a ON dr.id_aset = a.id_aset
  LEFT JOIN users u ON dr.id_user = u.id_user
  WHERE dr.id_laporan = " . intval($id_laporan) . "
";
$result = $conn->query($query);
$data = $result->fetch_assoc();

if (!$data) {
  $_SESSION['error'] = "Data laporan tidak ditemukan.";
  header("Location: index.php");
  exit();
}

// Update status jika form disubmit
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
  $status = $_POST['status'] ?? '';

  if (in_array($status, ['baru', 'diproses', 'selesai'])) {
    $update = $conn->prepare("UPDATE damage_reports SET status = ? WHERE id_laporan = ?");
    $update->bind_param("si", $status, $id_laporan);

    if ($update->execute()) {
      $_SESSION['success'] = "Status laporan berhasil diperbarui!";
    } else {
      $_SESSION['error'] = "Gagal memperbarui status laporan.";
    }
  } else {
    $_SESSION['error'] = "Status tidak valid.";
  }

  header("Location: index.php");
  exit();
}

include '../../includes/header.php';
include '../../includes/sidebar.php';
?>

<main class="main-content">
  <div class="page-header d-flex justify-content-between align-items-center">
    <h1 class="page-title"><i class="fa-solid fa-pen-to-square me-2"></i>Ubah Status Laporan</h1>
  </div>

  <div class="card shadow-sm mt-3">
    <div class="card-body">
      <?php display_notification(); ?>

      <form method="POST">
        <div class="row mb-3">
          <label class="col-sm-3 col-form-label fw-semibold">Kode Aset</label>
          <div class="col-sm-9">
            <input type="text" class="form-control" value="<?= htmlspecialchars($data['kode_aset']) ?>" readonly>
          </div>
        </div>

        <div class="row mb-3">
          <label class="col-sm-3 col-form-label fw-semibold">Nama Aset</label>
          <div class="col-sm-9">
            <input type="text" class="form-control" value="<?= htmlspecialchars($data['nama_aset']) ?>" readonly>
          </div>
        </div>

        <div class="row mb-3">
          <label class="col-sm-3 col-form-label fw-semibold">Pelapor</label>
          <div class="col-sm-9">
            <input type="text" class="form-control" value="<?= htmlspecialchars($data['nama']) ?>" readonly>
          </div>
        </div>

        <div class="row mb-3">
          <label class="col-sm-3 col-form-label fw-semibold">Tanggal Lapor</label>
          <div class="col-sm-9">
            <input type="text" class="form-control" value="<?= htmlspecialchars($data['tanggal_lapor']) ?>" readonly>
          </div>
        </div>

        <div class="row mb-3">
          <label class="col-sm-3 col-form-label fw-semibold">Deskripsi Kerusakan</label>
          <div class="col-sm-9">
            <textarea class="form-control" rows="4" readonly><?= htmlspecialchars($data['deskripsi']) ?></textarea>
          </div>
        </div>

        <div class="row mb-4">
          <label class="col-sm-3 col-form-label fw-semibold">Status Laporan</label>
          <div class="col-sm-9">
            <select name="status" class="form-select" required>
              <option value="baru" <?= $data['status'] === 'baru' ? 'selected' : '' ?>>Baru</option>
              <option value="diproses" <?= $data['status'] === 'diproses' ? 'selected' : '' ?>>Diproses</option>
              <option value="selesai" <?= $data['status'] === 'selesai' ? 'selected' : '' ?>>Selesai</option>
            </select>
          </div>
        </div>

        <div class="text-end">
          <a href="index.php" class="btn btn-secondary me-2">
            <i class="fa-solid fa-arrow-left me-1"></i> Kembali
          </a>
          <button type="submit" class="btn btn-primary">
            <i class="fa-solid fa-save me-1"></i> Simpan Perubahan
          </button>
        </div>
      </form>
    </div>
  </div>
</main>

<?php include '../../includes/footer.php'; ?>
