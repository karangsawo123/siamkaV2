<?php
define("SECURE", true);
if (session_status() === PHP_SESSION_NONE) session_start();

require_once '../../includes/auth_check.php';
require_once '../../includes/role_check.php';
require_once '../../config/config.php';
require_once '../../config/database.php';
require_once '../../includes/notification_helper.php';

checkRole(['pengguna', 'staff', 'manajemen']);

// 🔹 Ambil semua peminjaman milik user
$id_user = $_SESSION['id_user'];

// Cek pinjaman yang hampir jatuh tempo (2 hari lagi)
$notif_query = "
  SELECT COUNT(*) AS count_due
  FROM loans
  WHERE id_user = ?
  AND status = 'approved'
  AND end_date BETWEEN CURDATE() AND DATE_ADD(CURDATE(), INTERVAL 2 DAY)
";
$notif_stmt = $conn->prepare($notif_query);
$notif_stmt->bind_param("i", $id_user);
$notif_stmt->execute();
$notif_result = $notif_stmt->get_result()->fetch_assoc();
$count_due = $notif_result['count_due'] ?? 0;

// Ambil semua pinjaman user
$query = "
    SELECT 
        l.*, 
        a.nama_aset, 
        a.kode_aset, 
        a.lokasi, 
        a.kondisi
    FROM loans l
    LEFT JOIN assets a ON l.id_aset = a.id_aset
    WHERE l.id_user = ?
    ORDER BY l.created_at DESC
";

$stmt = $conn->prepare($query);
$stmt->bind_param("i", $id_user);
$stmt->execute();
$result = $stmt->get_result();

include '../../includes/header.php';
include '../../includes/sidebar.php';
?>

<main class="main-content">
  <div class="page-header">
    <h1 class="page-title"><i class="fa-solid fa-clipboard-check me-2"></i>Peminjaman Saya</h1>
  </div>

  <div class="card shadow-sm">
    <div class="card-body">
      <?php display_notification(); ?>

      <?php if ($count_due > 0): ?>
        <div class="alert alert-warning mb-3">
          <i class="fa-solid fa-exclamation-triangle me-2"></i>
          Anda memiliki <strong><?= $count_due ?></strong> peminjaman yang akan jatuh tempo dalam 2 hari!
        </div>
      <?php endif; ?>

      <?php if ($result->num_rows > 0): ?>
        <div class="table-responsive">
          <table class="table table-striped align-middle">
            <thead class="table-dark">
              <tr>
                <th>No</th>
                <th>Kode Aset</th>
                <th>Nama Aset</th>
                <th>Tanggal Pinjam</th>
                <th>Tanggal Kembali</th>
                <th>Status</th>
                <th>Aksi</th>
              </tr>
            </thead>
            <tbody>
              <?php $no = 1;
              while ($row = $result->fetch_assoc()): ?>
                <?php
                $status = $row['status'];
                $today = date('Y-m-d');
                $end_date = $row['end_date'];

                // Deteksi status terlambat
                if ($status === 'approved' && $end_date < $today) {
                  $status_display = 'overdue';
                  $badge_class = 'danger';
                  $label = 'overdue';
                } else {
                  $badge_class = [
                    'pending' => 'warning',
                    'approved' => 'primary',
                    'rejected' => 'danger',
                    'returned' => 'success'
                  ][$status] ?? 'secondary';
                  $status_display = $status;
                  $label = ucfirst($status);
                }
                ?>
                <tr>
                  <td><?= $no++ ?></td>
                  <td><?= htmlspecialchars($row['kode_aset']) ?></td>
                  <td><?= htmlspecialchars($row['nama_aset']) ?></td>
                  <td><?= htmlspecialchars($row['start_date']) ?></td>
                  <td><?= htmlspecialchars($row['end_date']) ?></td>
                  <td><span class="badge bg-<?= $badge_class ?>"><?= $label ?></span></td>
                  <td>
                    <?php if ($status_display === 'pending'): ?>
                      <button class="btn btn-sm btn-outline-secondary" disabled>Menunggu Persetujuan</button>

                    <?php elseif ($status_display === 'approved'): ?>
                      <a href="return_asset.php?id_peminjaman=<?php echo htmlspecialchars($row['id_peminjaman']); ?>"
                        class="btn btn-sm btn-success"
                        onclick="return confirm('Yakin ingin mengembalikan aset ini?')">
                        <i class="fa-solid fa-undo"></i> Kembalikan
                      </a>

                    <?php elseif ($status_display === 'overdue'): ?>
                      <a href="return_asset.php?id_peminjaman=<?php echo htmlspecialchars($row['id_peminjaman']); ?>"
                        class="btn btn-sm btn-danger"
                        onclick="return confirm('Aset sudah lewat jatuh tempo! Tetap kembalikan sekarang?')">
                        <i class="fa-solid fa-undo"></i> Kembalikan
                      </a>

                    <?php elseif ($status_display === 'rejected'): ?>
                      <button class="btn btn-sm btn-outline-danger" disabled>Ditolak</button>

                    <?php elseif ($status_display === 'returned'): ?>
                      <button class="btn btn-sm btn-outline-dark" disabled>Selesai</button>
                    <?php endif; ?>
                  </td>

                </tr>
              <?php endwhile; ?>
            </tbody>
          </table>
        </div>
      <?php else: ?>
        <div class="text-center text-muted py-5">
          <i class="fa-solid fa-info-circle fa-2x mb-3"></i>
          <p>Belum ada riwayat peminjaman.</p>
        </div>
      <?php endif; ?>
    </div>
  </div>
</main>

<?php include '../../includes/footer.php'; ?>