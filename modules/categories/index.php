<?php
define("SECURE", true);
if (session_status() === PHP_SESSION_NONE) session_start();

require_once '../../includes/auth_check.php';
require_once '../../includes/role_check.php';
require_once '../../config/config.php';
require_once '../../config/database.php';
require_once '../../includes/notification_helper.php';
checkRole(['admin']);

$selected_kategori = $_GET['kategori'] ?? '';

// 🔹 Query data kategori + jumlah aset
$query = "SELECT c.*, COUNT(a.id_aset) AS total_aset
          FROM categories c
          LEFT JOIN assets a ON c.id_kategori = a.id_kategori
          WHERE 1=1";
$params = [];
$types = '';

if ($selected_kategori !== '') {
    $query .= " AND c.id_kategori = ?";
    $params[] = $selected_kategori;
    $types .= 'i';
}

$query .= " GROUP BY c.id_kategori ORDER BY c.nama_kategori ASC";

$stmt = $conn->prepare($query);
if (!empty($params)) $stmt->bind_param($types, ...$params);
$stmt->execute();
$result = $stmt->get_result();

// 🔹 Data untuk dropdown kategori
$kategori_result = $conn->query("SELECT id_kategori, nama_kategori FROM categories ORDER BY nama_kategori ASC");

include '../../includes/header.php';
include '../../includes/sidebar.php';
?>

<main class="main-content">
    <div class="page-header d-flex justify-content-between align-items-center">
        <h1 class="page-title"><i class="fa-solid fa-tags me-2"></i>Manajemen Kategori Aset</h1>
        <a href="add.php" class="btn btn-primary">
            <i class="fa-solid fa-plus"></i> Tambah Kategori
        </a>
    </div>

    <div class="card shadow-sm">
        <div class="card-body">
            <?php display_notification(); ?>

            <!-- 🔽 Dropdown Filter -->
            <form method="get" class="mb-3">
                <div class="row g-3">
                    <div class="col-md-4">
                        <label class="form-label">Pilih Kategori</label>
                        <select name="kategori" class="form-select" onchange="this.form.submit()">
                            <option value="">Semua Kategori</option>
                            <?php while ($kat = $kategori_result->fetch_assoc()): ?>
                                <option value="<?= $kat['id_kategori'] ?>" 
                                    <?= $selected_kategori == $kat['id_kategori'] ? 'selected' : '' ?>>
                                    <?= htmlspecialchars($kat['nama_kategori']) ?>
                                </option>
                            <?php endwhile; ?>
                        </select>
                    </div>
                </div>
            </form>

            <!-- 🔹 Data Table -->
            <div class="table-responsive">
                <table class="table table-striped align-middle">
                    <thead class="table-dark">
                        <tr>
                            <th>No</th>
                            <th>Nama Kategori</th>
                            <th>Deskripsi</th>
                            <th>Jumlah Aset</th>
                            <th class="text-center">Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php if ($result->num_rows > 0): ?>
                            <?php $no = 1; while ($row = $result->fetch_assoc()): ?>
                                <tr>
                                    <td><?= $no++ ?></td>
                                    <td><?= htmlspecialchars($row['nama_kategori']) ?></td>
                                    <td><?= htmlspecialchars($row['deskripsi']) ?: '-' ?></td>
                                    <td><span class="badge bg-info"><?= $row['total_aset'] ?></span></td>
                                    <td class="text-center">
                                        <a href="edit.php?id=<?= $row['id_kategori'] ?>" class="btn btn-sm btn-warning me-1">
                                            <i class="fa-solid fa-pen"></i> Edit
                                        </a>
                                        <a href="delete.php?id=<?= $row['id_kategori'] ?>" 
                                           class="btn btn-sm btn-danger"
                                           onclick="return confirm('Yakin hapus kategori ini?')">
                                            <i class="fa-solid fa-trash"></i> Hapus
                                        </a>
                                    </td>
                                </tr>
                            <?php endwhile; ?>
                        <?php else: ?>
                            <tr>
                                <td colspan="5" class="text-center text-muted py-4">
                                    <i class="fa-solid fa-circle-info me-1"></i> Tidak ada data kategori.
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
