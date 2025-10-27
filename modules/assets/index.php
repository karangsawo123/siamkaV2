<?php
define("SECURE", true);
if (session_status() === PHP_SESSION_NONE) session_start();

include '../../includes/auth_check.php';
include '../../includes/role_check.php';
include '../../config/config.php';
include '../../config/database.php';
include '../../includes/header.php';
include '../../includes/sidebar.php';
include '../../includes/notification_helper.php';
display_notification();

checkRole(['admin', 'manajemen']);

// Pagination
$limit = 12;
$page = isset($_GET['page']) ? (int)$_GET['page'] : 1;
$offset = ($page - 1) * $limit;

// Search & Filter
$search   = isset($_GET['search']) ? $_GET['search'] : '';
$kategori = isset($_GET['kategori']) ? $_GET['kategori'] : '';
$kondisi  = isset($_GET['kondisi']) ? $_GET['kondisi'] : '';
$status   = isset($_GET['status']) ? $_GET['status'] : '';

// Base query
$where = "WHERE a.deleted_at IS NULL AND (a.nama_aset LIKE '%$search%' OR a.kode_aset LIKE '%$search%')";
if ($kategori != '') $where .= " AND a.id_kategori = '$kategori'";
if ($kondisi != '') $where .= " AND a.kondisi = '$kondisi'";
if ($status != '') $where .= " AND a.status = '$status'";

// Hitung total aset
$countQuery = mysqli_query($conn, "
  SELECT COUNT(*) AS total 
  FROM assets a 
  LEFT JOIN categories c ON a.id_kategori = c.id_kategori 
  $where
");
$total_assets = mysqli_fetch_assoc($countQuery)['total'];
$total_pages = ceil($total_assets / $limit);

// Ambil data aset
$query = mysqli_query($conn, "
  SELECT a.*, c.nama_kategori 
  FROM assets a 
  LEFT JOIN categories c ON a.id_kategori = c.id_kategori 
  $where 
  ORDER BY a.id_aset DESC 
  LIMIT $limit OFFSET $offset
");
?>

<main class="main-content">

    <div class="card shadow-sm">
      <div class="card-header d-flex justify-content-between align-items-center">
        <h4 class="mb-0">📦 Manajemen Aset</h4>
        <div>
          <a href="add.php" class="btn btn-primary btn-sm">+ Tambah Aset</a>
          <a href="trash.php" class="btn btn-outline-danger btn-sm">🗑️ Aset Terhapus</a>
        </div>
      </div>

      <div class="card-body">
        <!-- Search & Filter -->
        <form method="GET" id="filterForm" class="row g-2 mb-4">
          <div class="col-md-4">
            <input type="text" name="search" class="form-control" 
                   placeholder="Cari nama atau kode aset..." 
                   value="<?= htmlspecialchars($search); ?>">
          </div>

          <div class="col-md-2">
            <select name="kategori" class="form-select">
              <option value="">-- Kategori --</option>
              <?php
              $kategori_q = mysqli_query($conn, "SELECT * FROM categories ORDER BY nama_kategori");
              while ($k = mysqli_fetch_assoc($kategori_q)) {
                $selected = ($kategori == $k['id_kategori']) ? 'selected' : '';
                echo "<option value='{$k['id_kategori']}' $selected>{$k['nama_kategori']}</option>";
              }
              ?>
            </select>
          </div>

          <div class="col-md-2">
            <select name="kondisi" class="form-select">
              <option value="">-- Kondisi --</option>
              <option value="baik" <?= $kondisi == 'baik' ? 'selected' : '' ?>>Baik</option>
              <option value="rusak" <?= $kondisi == 'rusak' ? 'selected' : '' ?>>Rusak</option>
              <option value="hilang" <?= $kondisi == 'hilang' ? 'selected' : '' ?>>Hilang</option>
            </select>
          </div>

          <div class="col-md-2">
            <select name="status" class="form-select">
              <option value="">-- Status --</option>
              <option value="Tersedia" <?= $status == 'Tersedia' ? 'selected' : '' ?>>Tersedia</option>
              <option value="Dipinjam" <?= $status == 'Dipinjam' ? 'selected' : '' ?>>Dipinjam</option>
              <option value="Maintenance" <?= $status == 'Maintenance' ? 'selected' : '' ?>>Maintenance</option>
            </select>
          </div>

          <div class="col-md-2">
            <button type="submit" class="btn btn-secondary w-100">
              <i class="fa fa-search"></i> Cari
            </button>
          </div>
        </form>

        <script>
          document.addEventListener('DOMContentLoaded', () => {
            document.querySelectorAll('#filterForm select').forEach(select => {
              select.addEventListener('change', () => {
                document.getElementById('filterForm').submit();
              });
            });
          });
        </script>

        <!-- Grid Aset -->
        <div class="row">
          <?php if (mysqli_num_rows($query) > 0): ?>
            <?php while ($row = mysqli_fetch_assoc($query)): ?>
              <div class="col-md-3 mb-4">
                <div class="card h-100 border-0 shadow-sm">
                  <img src="../../assets/uploads/assets/<?= htmlspecialchars($row['foto']); ?>" 
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
                      <a href="detail.php?id=<?= $row['id_aset']; ?>" class="btn btn-outline-info btn-sm" title="Detail">
                        <i class="fa fa-eye"></i>
                      </a>
                      <a href="edit.php?id=<?= $row['id_aset']; ?>" class="btn btn-outline-warning btn-sm" title="Edit">
                        <i class="fa fa-edit"></i>
                      </a>
                      <a href="delete.php?id_aset=<?= $row['id_aset']; ?>" 
                         class="btn btn-outline-danger btn-sm"
                         onclick="return confirm('Yakin ingin menghapus aset ini?')"
                         title="Hapus">
                        <i class="fa fa-trash"></i>
                      </a>
                    </div>
                  </div>
                </div>
              </div>
            <?php endwhile; ?>
          <?php else: ?>
            <div class="text-center py-4">
              <p class="text-muted">Tidak ada aset ditemukan.</p>
            </div>
          <?php endif; ?>
        </div>

        <!-- Pagination -->
        <?php if ($total_pages > 1): ?>
          <nav>
            <ul class="pagination justify-content-center mt-3">
              <?php for ($i = 1; $i <= $total_pages; $i++): ?>
                <li class="page-item <?= $i == $page ? 'active' : '' ?>">
                  <a class="page-link" href="?page=<?= $i ?>&search=<?= urlencode($search) ?>&kategori=<?= $kategori ?>&kondisi=<?= $kondisi ?>&status=<?= $status ?>"><?= $i ?></a>
                </li>
              <?php endfor; ?>
            </ul>
          </nav>
        <?php endif; ?>
      </div>
    </div>
  </div>
</main>

<?php include '../../includes/footer.php'; ?>
