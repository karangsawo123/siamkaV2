<?php
define("SECURE", true);
session_start();

include '../../includes/auth_check.php';
include '../../includes/role_check.php';
include '../../config/config.php';
include '../../config/database.php';
include '../../includes/header.php';
include '../../includes/sidebar.php';

// Hanya pengguna yang bisa buka halaman ini
checkRole(['pengguna']);

// Validasi ID
if (!isset($_GET['id']) || !is_numeric($_GET['id'])) {
  echo "<script>alert('ID aset tidak ditemukan!'); window.location='available.php';</script>";
  exit;
}

$id = intval($_GET['id']);

// Ambil data aset
$query = "
    SELECT a.*, k.nama_kategori 
    FROM assets a
    LEFT JOIN categories k ON a.id_kategori = k.id_kategori
    WHERE a.id_aset = $id AND a.deleted_at IS NULL
";
$result = $conn->query($query);

if (!$result || $result->num_rows === 0) {
  echo "<script>alert('Data aset tidak ditemukan!'); window.location='available.php';</script>";
  exit;
}

$asset = $result->fetch_assoc();
?>

<main class="main-content">
  <div class="page-header">
    <h2>📋 Detail Aset</h2>
    <p class="text-muted">Informasi lengkap mengenai aset yang tersedia.</p>
  </div>

  <div class="card asset-detail-card shadow-sm">
    <div class="card-header">
      <h3><?= htmlspecialchars($asset['nama_aset']); ?></h3>
    </div>

    <div class="card-body asset-detail d-flex align-items-start gap-4 flex-wrap" style="gap:1.5rem;">
  <div class="asset-photo" style="flex:0 0 350px;">
    <?php if (!empty($asset['foto'])): ?>
      <img src="../../assets/uploads/assets/<?= htmlspecialchars($asset['foto']); ?>" 
           alt="<?= htmlspecialchars($asset['nama_aset']); ?>" 
           class="img-fluid rounded shadow-sm" 
           style="max-height:350px; object-fit:cover;">
    <?php else: ?>
      <img src="../../assets/no-image.png" alt="Tidak ada foto" 
           class="img-fluid rounded shadow-sm" 
           style="max-height:350px; object-fit:cover;">
    <?php endif; ?>
  </div>

  <div class="asset-info flex-grow-1" style="min-width:300px;">
    <table class="table table-bordered table-striped mb-3">
      <tr><th>Kode Aset</th><td><?= htmlspecialchars($asset['kode_aset']); ?></td></tr>
      <tr><th>Nama Aset</th><td><?= htmlspecialchars($asset['nama_aset']); ?></td></tr>
      <tr><th>Kategori</th><td><?= htmlspecialchars($asset['nama_kategori'] ?? '-'); ?></td></tr>
      <tr><th>Kondisi</th><td><?= htmlspecialchars($asset['kondisi'] ?? '-'); ?></td></tr>
      <tr><th>Status</th><td><?= htmlspecialchars($asset['status']); ?></td></tr>
      <tr><th>Lokasi</th><td><?= htmlspecialchars($asset['lokasi']); ?></td></tr>
      <tr><th>Harga</th><td>Rp <?= number_format($asset['harga'], 0, ',', '.'); ?></td></tr>
      <tr><th>Tanggal Perolehan</th><td><?= htmlspecialchars($asset['tanggal_perolehan']); ?></td></tr>
      <tr><th>Keterangan</th><td><?= nl2br(htmlspecialchars($asset['keterangan'] ?? '-')); ?></td></tr>
    </table>

    <div class="d-flex gap-2">
      <a href="request.php?id_aset=<?= $asset['id_aset']; ?>" class="btn btn-success">
        <i class="fa fa-box-arrow-in-right me-1"></i> Pinjam
      </a>
      <a href="available.php" class="btn btn-secondary">
        <i class="fa fa-arrow-left me-1"></i> Kembali
      </a>
    </div>
  </div>
</div>

</main>

<?php require_once '../../includes/footer.php'; ?>
