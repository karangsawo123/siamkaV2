<?php
define("SECURE", true);
if (session_status() === PHP_SESSION_NONE) session_start();

require_once '../../includes/auth_check.php';
require_once '../../includes/role_check.php';
require_once '../../config/config.php';
require_once '../../config/database.php';
require_once '../../includes/notification_helper.php';

checkRole(['admin', 'manajemen']);

// --- FILTER ---
$status_filter = $_GET['status'] ?? '';
$asset_filter = $_GET['id_aset'] ?? '';

// Query utama
$query = "
  SELECT dr.*, a.nama_aset, a.kode_aset, u.nama
  FROM damage_reports dr
  LEFT JOIN assets a ON dr.id_aset = a.id_aset
  LEFT JOIN users u ON dr.id_user = u.id_user
  WHERE 1=1
";

if (!empty($status_filter)) {
  $query .= " AND dr.status = '" . $conn->real_escape_string($status_filter) . "'";
}
if (!empty($asset_filter)) {
  $query .= " AND dr.id_aset = " . intval($asset_filter);
}

$query .= " ORDER BY dr.tanggal_lapor DESC";
$result = $conn->query($query);

// Daftar aset untuk filter
$assets = $conn->query("SELECT id_aset, nama_aset FROM assets ORDER BY nama_aset ASC");

include '../../includes/header.php';
include '../../includes/sidebar.php';
?>

<main class="main-content">
  <div class="page-header d-flex justify-content-between align-items-center">
    <h1 class="page-title"><i class="fa-solid fa-screwdriver-wrench me-2"></i>Manajemen Laporan Kerusakan</h1>
  </div>

  <div class="card shadow-sm mt-3">
    <div class="card-body">
      <?php display_notification(); ?>

      <!-- Filter Section -->
      <form method="GET" class="row g-3 mb-4">
        <div class="col-md-4">
          <label for="status" class="form-label fw-semibold">Filter Status</label>
          <select name="status" id="status" class="form-select">
            <option value="">Semua Status</option>
            <option value="baru" <?= $status_filter === 'baru' ? 'selected' : '' ?>>Baru</option>
            <option value="diproses" <?= $status_filter === 'diproses' ? 'selected' : '' ?>>Diproses</option>
            <option value="selesai" <?= $status_filter === 'selesai' ? 'selected' : '' ?>>Selesai</option>
          </select>
        </div>

        <div class="col-md-4">
          <label for="id_aset" class="form-label fw-semibold">Filter Aset</label>
          <select name="id_aset" id="id_aset" class="form-select">
            <option value="">Semua Aset</option>
            <?php while ($row = $assets->fetch_assoc()): ?>
              <option value="<?= $row['id_aset'] ?>" <?= $asset_filter == $row['id_aset'] ? 'selected' : '' ?>>
                <?= htmlspecialchars($row['nama_aset']) ?>
              </option>
            <?php endwhile; ?>
          </select>
        </div>

        <div class="col-md-4 d-flex align-items-end">
          <button type="submit" class="btn btn-primary me-2">
            <i class="fa-solid fa-filter me-1"></i> Terapkan Filter
          </button>
          <a href="index.php" class="btn btn-outline-secondary">
            <i class="fa-solid fa-rotate-left me-1"></i> Reset
          </a>
        </div>
      </form>

      <!-- Table Section -->
      <div class="table-responsive">
        <table class="table table-bordered table-striped align-middle text-center">
          <thead class="table-dark align-middle">
            <tr>
              <th style="width: 5%;">No</th>
              <th style="width: 10%;">Kode Aset</th>
              <th style="width: 15%;">Nama Aset</th>
              <th style="width: 15%;">Pelapor</th>
              <th style="width: 12%;">Tanggal Lapor</th>
              <th style="width: 25%;">Deskripsi</th>
              <th style="width: 10%;">Status</th>
              <th style="width: 8%;">Aksi</th>
            </tr>
          </thead>
          <tbody>
            <?php if ($result->num_rows > 0): ?>
              <?php $no = 1; while ($row = $result->fetch_assoc()): ?>
                <?php
                $status_class = [
                  'baru' => 'warning',
                  'diproses' => 'primary',
                  'selesai' => 'success'
                ][$row['status']] ?? 'secondary';
                ?>
                <tr>
                  <td><?= $no++ ?></td>
                  <td class="fw-semibold"><?= htmlspecialchars($row['kode_aset']) ?></td>
                  <td><?= htmlspecialchars($row['nama_aset']) ?></td>
                  <td><?= htmlspecialchars($row['nama']) ?></td>
                  <td><?= htmlspecialchars($row['tanggal_lapor']) ?></td>
                  <td class="text-start"><?= nl2br(htmlspecialchars($row['deskripsi'])) ?></td>
                  <td><span class="badge bg-<?= $status_class ?> px-3 py-2"><?= ucfirst($row['status']) ?></span></td>
                  <td>
                    <a href="update_status.php?id=<?= $row['id_laporan'] ?>" class="btn btn-sm btn-outline-primary">
                      <i class="fa-solid fa-pen-to-square"></i>
                    </a>
                  </td>
                </tr>
              <?php endwhile; ?>
            <?php else: ?>
              <tr>
                <td colspan="8" class="text-center text-muted py-4">
                  <i class="fa-solid fa-circle-info me-2"></i> Belum ada laporan kerusakan.
                </td>
              </tr>
            <?php endif; ?>
          </tbody>
        </table>
      </div>
    </div>
  </div>
</main>

<?php include '../../includes/footer.php'; ?>
