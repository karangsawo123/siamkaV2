<?php
define("SECURE", true);
if (session_status() === PHP_SESSION_NONE) session_start();

require_once '../../includes/auth_check.php';
require_once '../../includes/role_check.php';
require_once '../../config/config.php';
require_once '../../config/database.php';
require_once '../../includes/notification_helper.php';

checkRole(['pengguna', 'staff', 'manajemen']);

$id_user = $_SESSION['id_user'];

// Jika form dikirim
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $id_aset = $_POST['id_aset'] ?? '';
    $deskripsi = trim($_POST['deskripsi'] ?? '');

    if (empty($id_aset) || empty($deskripsi)) {
        set_notification("Semua field wajib diisi!", "danger");
    } else {
        $stmt = $conn->prepare("
            INSERT INTO damage_reports (id_user, id_aset, tanggal_lapor, deskripsi, status)
            VALUES (?, ?, CURDATE(), ?, 'baru')
        ");
        $stmt->bind_param("iis", $id_user, $id_aset, $deskripsi);

        if ($stmt->execute()) {
            set_notification("Laporan kerusakan berhasil dikirim.", "success");
            header("Location: my_reports.php");
            exit;
        } else {
            set_notification("Gagal mengirim laporan: " . $conn->error, "danger");
        }
    }
}

include '../../includes/header.php';
include '../../includes/sidebar.php';
?>

<main class="main-content">
  <div class="page-header">
    <h1 class="page-title"><i class="fa-solid fa-screwdriver-wrench me-2"></i>Lapor Kerusakan Aset</h1>
  </div>

  <div class="card shadow-sm border-0">
    <div class="card-body">
      <?php display_notification(); ?>

      <form method="POST" class="mt-3">
        <div class="mb-3">
          <label for="id_aset" class="form-label fw-semibold">Pilih Aset (yang sedang dipinjam)</label>
          <select name="id_aset" id="id_aset" class="form-select" required>
            <option value="">-- Pilih Aset --</option>
            <?php
            // 🔹 Hanya ambil aset yang sedang dipinjam user dengan status approved
            $query = "
                SELECT a.id_aset, a.nama_aset, a.kode_aset
                FROM loans l
                JOIN assets a ON l.id_aset = a.id_aset
                WHERE l.id_user = ? AND l.status = 'approved'
                ORDER BY a.nama_aset ASC
            ";
            $stmt = $conn->prepare($query);
            $stmt->bind_param("i", $id_user);
            $stmt->execute();
            $result = $stmt->get_result();

            if ($result->num_rows > 0):
              while ($row = $result->fetch_assoc()):
            ?>
                <option value="<?= $row['id_aset'] ?>">
                  <?= htmlspecialchars($row['nama_aset']) ?> (<?= htmlspecialchars($row['kode_aset']) ?>)
                </option>
            <?php
              endwhile;
            else:
            ?>
              <option value="">Tidak ada aset yang sedang dipinjam</option>
            <?php endif; ?>
          </select>
        </div>

        <div class="mb-3">
          <label for="deskripsi" class="form-label fw-semibold">Deskripsi Kerusakan</label>
          <textarea name="deskripsi" id="deskripsi" class="form-control" rows="4" placeholder="Jelaskan kondisi atau kerusakan aset..." required></textarea>
        </div>

        <div class="d-flex justify-content-between">
          <a href="my_reports.php" class="btn btn-outline-secondary">
            <i class="fa-solid fa-list me-1"></i> Lihat Laporan Saya
          </a>
          <button type="submit" class="btn btn-primary">
            <i class="fa-solid fa-paper-plane me-1"></i> Kirim Laporan
          </button>
        </div>
      </form>
    </div>
  </div>
</main>

<?php include '../../includes/footer.php'; ?>
