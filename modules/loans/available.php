<?php
define("SECURE", true);

if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

require_once '../../config/config.php';
require_once '../../config/database.php';
require_once '../../includes/auth_check.php';
require_once '../../includes/role_check.php';
require_once '../../includes/header.php';
require_once '../../includes/sidebar.php';

checkRole(['pengguna']);

// 🔎 Ambil filter kategori (opsional)
$kategori = isset($_GET['kategori']) ? trim($_GET['kategori']) : '';

// 🔧 Query aset tersedia (kecuali yang hilang)
$query = "
    SELECT a.*, k.nama_kategori 
    FROM assets a
    LEFT JOIN categories k ON a.id_kategori = k.id_kategori
    WHERE a.status = 'Tersedia' 
      AND a.kondisi != 'Hilang'
      AND a.deleted_at IS NULL
";

if (!empty($kategori)) {
    $query .= " AND a.id_kategori = " . intval($kategori);
}

$query .= " ORDER BY a.nama_aset ASC";

// Jalankan query
$result = mysqli_query($conn, $query);

// 🔽 Ambil daftar kategori untuk dropdown filter
$kategori_query = mysqli_query($conn, "SELECT id_kategori, nama_kategori FROM categories");
?>

<main class="main-content">
    <div class="container-fluid mt-4">
        <div class="card shadow-sm">
            <div class="card-header d-flex justify-content-between align-items-center">
                <h4 class="mb-0">
                    <i class="fa-solid fa-box-open me-2"></i> Aset Tersedia untuk Dipinjam
                </h4>
            </div>

            <div class="card-body">
                <!-- Filter Kategori -->
                <form method="get" id="filterForm" class="row g-2 mb-4">
                    <div class="col-md-4">
                        <select name="kategori" class="form-select" onchange="document.getElementById('filterForm').submit()">
                            <option value="">-- Semua Kategori --</option>
                            <?php while ($kat = mysqli_fetch_assoc($kategori_query)): ?>
                                <option value="<?= $kat['id_kategori'] ?>" <?= ($kategori == $kat['id_kategori']) ? 'selected' : '' ?>>
                                    <?= htmlspecialchars($kat['nama_kategori']) ?>
                                </option>
                            <?php endwhile; ?>
                        </select>
                    </div>
                </form>

                <!-- Grid Aset -->
                <div class="row">
                    <?php if (mysqli_num_rows($result) > 0): ?>
                        <?php while ($row = mysqli_fetch_assoc($result)): ?>
                            <div class="col-md-3 mb-4">
                                <div class="card h-100 border-0 shadow-sm">
                                    <img src="<?= BASE_URL ?>assets/uploads/assets/<?= htmlspecialchars($row['foto'] ?: 'default.png') ?>"
                                        class="card-img-top"
                                        alt="<?= htmlspecialchars($row['nama_aset']); ?>"
                                        style="height: 180px; object-fit: cover;">

                                    <div class="card-body">
                                        <h5 class="card-title"><?= htmlspecialchars($row['nama_aset']); ?></h5>
                                        <p class="card-text small text-muted mb-1"><strong>Kode:</strong> <?= htmlspecialchars($row['kode_aset']); ?></p>
                                        <p class="card-text small text-muted mb-1"><strong>Kategori:</strong> <?= htmlspecialchars($row['nama_kategori']); ?></p>
                                        <p class="card-text small text-muted mb-1"><strong>Kondisi:</strong> <?= htmlspecialchars($row['kondisi']); ?></p>
                                        <p class="card-text small text-muted"><strong>Status:</strong> <?= htmlspecialchars($row['status']); ?></p>
                                    </div>

                                    <div class="card-footer bg-light text-center">
                                        <div class="btn-group" role="group">
                                            <a href="detail.php?id=<?= $row['id_aset']; ?>"
                                                class="btn btn-outline-info btn-sm"
                                                title="Detail">
                                                <i class="fa fa-eye me-1"></i> Detail
                                            </a>
                                            <a href="request.php?id_aset=<?= $row['id_aset']; ?>"
                                                class="btn btn-outline-success btn-sm"
                                                title="Pinjam">
                                                <i class="fa fa-box-arrow-in-right me-1"></i> Pinjam
                                            </a>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        <?php endwhile; ?>
                    <?php else: ?>
                        <div class="text-center py-4">
                            <p class="text-muted">Tidak ada aset yang tersedia.</p>
                        </div>
                    <?php endif; ?>
                </div>
            </div>
        </div>
    </div>
</main>

<?php require_once '../../includes/footer.php'; ?>
