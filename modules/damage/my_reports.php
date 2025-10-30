<?php
define("SECURE", true);
if (session_status() === PHP_SESSION_NONE) session_start();

require_once '../../includes/auth_check.php';
require_once '../../includes/role_check.php';
require_once '../../config/config.php';
require_once '../../config/database.php';
require_once '../../includes/notification_helper.php';

checkRole(['pengguna', 'manajemen']);

$id_user = $_SESSION['id_user'];

// Ambil laporan langsung dari database (tanpa cache)
$query = "
  SELECT 
      d.id_laporan,
      d.id_aset,
      d.tanggal_lapor,
      d.deskripsi,
      d.status,
      a.nama_aset,
      a.kode_aset
  FROM damage_reports d
  LEFT JOIN assets a ON d.id_aset = a.id_aset
  WHERE d.id_user = ?
  ORDER BY d.tanggal_lapor DESC
";

$stmt = $conn->prepare($query);
$stmt->bind_param("i", $id_user);
$stmt->execute();
$result = $stmt->get_result();

include '../../includes/header.php';
include '../../includes/sidebar.php';
?>

<main class="main-content">
  <div class="page-header d-flex justify-content-between align-items-center">
    <h1 class="page-title"><i class="fa-solid fa-clipboard-list me-2"></i>Laporan Kerusakan Saya</h1>
  </div>

  <div class="card shadow-sm border-0 mt-3">
    <div class="card-body">
      <?php display_notification(); ?>

      <?php if ($result->num_rows > 0): ?>
        <div class="table-responsive">
          <table class="table table-bordered table-striped align-middle text-center">
            <thead class="table-dark align-middle">
              <tr>
                <th style="width: 5%;">No</th>
                <th style="width: 10%;">Kode Aset</th>
                <th style="width: 20%;">Nama Aset</th>
                <th style="width: 12%;">Tanggal Lapor</th>
                <th style="width: 30%;">Deskripsi</th>
                <th style="width: 10%;">Status</th>
              </tr>
            </thead>
            <tbody>
              <?php $no = 1; while ($row = $result->fetch_assoc()): ?>
                <?php
                // Map warna badge agar konsisten dengan admin
                $status_class = [
                  'baru' => 'warning',
                  'diproses' => 'primary',
                  'selesai' => 'success'
                ][$row['status']] ?? 'secondary';
                ?>
                <tr>
                  <td><?= $no++ ?></td>
                  <td class="fw-semibold"><?= htmlspecialchars($row['kode_aset'] ?? '-') ?></td>
                  <td><?= htmlspecialchars($row['nama_aset'] ?? '-') ?></td>
                  <td><?= htmlspecialchars($row['tanggal_lapor']) ?></td>
                  <td class="text-start"><?= nl2br(htmlspecialchars($row['deskripsi'])) ?></td>
                  <td>
                    <span class="badge bg-<?= $status_class ?> px-3 py-2 text-uppercase">
                      <?= ucfirst($row['status']) ?>
                    </span>
                  </td>
                </tr>
              <?php endwhile; ?>
            </tbody>
          </table>
        </div>
      <?php else: ?>
        <div class="text-center text-muted py-5">
          <i class="fa-solid fa-circle-info fa-2x mb-3"></i>
          <p>Belum ada laporan kerusakan aset.</p>
        </div>
      <?php endif; ?>
    </div>
  </div>
</main>

<?php include '../../includes/footer.php'; ?>
