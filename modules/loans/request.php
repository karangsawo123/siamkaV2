<?php
define("SECURE", true);
if (session_status() === PHP_SESSION_NONE) session_start();

include '../../includes/auth_check.php';
include '../../includes/role_check.php';
include '../../config/config.php';
include '../../config/database.php';
include '../../includes/notification_helper.php';

// Role yang boleh request peminjaman
checkRole(['pengguna']);

// pastikan session aktif
$id_user = $_SESSION['id_user'] ?? null;
if (!$id_user) {
    set_notification('error', 'Session pengguna tidak ditemukan. Silakan login ulang.');
    header('Location: ../../login.php');
    exit;
}

$id_aset = isset($_GET['id_aset']) ? intval($_GET['id_aset']) : 0;

// 🔹 Ambil data aset dulu
$query = $conn->prepare("SELECT * FROM assets WHERE id_aset = ? AND status = 'tersedia'");
$query->bind_param("i", $id_aset);
$query->execute();
$result = $query->get_result();
$asset = $result->fetch_assoc();

if (!$asset) {
    set_notification('error', '⚠️ Aset tidak tersedia untuk dipinjam.');
    header('Location: available.php');
    exit;
}

// 🔹 Proses form (HARUS sebelum output HTML)
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $id_user = $_SESSION['id_user'];
    $start_date = $_POST['start_date'] ?? null;
    $end_date = $_POST['end_date'] ?? null;

    if (empty($start_date) || empty($end_date)) {
        set_notification('error', '⚠️ Tanggal pinjam dan kembali harus diisi.');
    } else {
        $stmt = $conn->prepare("INSERT INTO loans (id_user, id_aset, start_date, end_date, status) VALUES (?, ?, ?, ?, 'pending')");
        $stmt->bind_param("iiss", $id_user, $id_aset, $start_date, $end_date);
        
        if ($stmt->execute()) {
            set_notification('success', '✅ Permintaan peminjaman berhasil dikirim dan menunggu persetujuan.');
            header('Location: available.php');
            exit;
        } else {
            set_notification('error', '❌ Gagal mengirim permintaan peminjaman.');
        }
    }
}

// 🔹 HTML baru mulai di bawah ini
include '../../includes/header.php';
include '../../includes/sidebar.php';
?>

<div class="main-content">
  <div class="card">
    <h2>Request Peminjaman Aset</h2>

    <div class="asset-info mb-4">
      <p><strong>Kode Aset:</strong> <?= htmlspecialchars($asset['kode_aset']) ?></p>
      <p><strong>Nama Aset:</strong> <?= htmlspecialchars($asset['nama_aset']) ?></p>
      <p><strong>Kondisi:</strong> <?= htmlspecialchars($asset['kondisi']) ?></p>
      <p><strong>Lokasi:</strong> <?= htmlspecialchars($asset['lokasi']) ?></p>
    </div>

    <form method="post" class="form">
      <div class="form-group">
        <label for="start_date">Tanggal Pinjam</label>
        <input type="date" name="start_date" id="start_date" required>
      </div>

      <div class="form-group">
        <label for="end_date">Tanggal Kembali</label>
        <input type="date" name="end_date" id="end_date" required>
      </div>

      <button type="submit" class="btn btn-primary mt-3">Kirim Permintaan</button>
      <a href="available.php" class="btn btn-secondary mt-3">Batal</a>
    </form>
  </div>
</div>

<?php include '../../includes/footer.php'; ?>
