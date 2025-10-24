<?php
define("SECURE", true);

if (session_status() === PHP_SESSION_NONE) {
  session_start();
}

include '../../includes/auth_check.php';
include '../../includes/role_check.php';
include '../../config/config.php';
include '../../config/database.php';
include '../../includes/header.php';
include '../../includes/sidebar.php';
include '../../includes/notification_helper.php';
display_notification();

// Batasi akses hanya admin
checkRole(['admin']);

// Pagination
$limit = 12;
$page = isset($_GET['page']) ? (int)$_GET['page'] : 1;
$offset = ($page - 1) * $limit;

// Search dan Filter
$search   = isset($_GET['search']) ? $_GET['search'] : '';
$kategori = isset($_GET['kategori']) ? $_GET['kategori'] : '';
$kondisi  = isset($_GET['kondisi']) ? $_GET['kondisi'] : '';
$status   = isset($_GET['status']) ? $_GET['status'] : '';

// Base Query
$where = "WHERE a.deleted_at IS NULL 
          AND (a.nama_aset LIKE '%$search%' OR a.kode_aset LIKE '%$search%')";

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
  <?php if (isset($_SESSION['success_message'])): ?>
    <div class="alert success">
      <?= htmlspecialchars($_SESSION['success_message']); ?>
    </div>
    <?php unset($_SESSION['success_message']); ?>
  <?php endif; ?>

  <div class="card">
    <h1 class="page-title">📦 Manajemen Aset</h1>

    <!-- Filter & Search -->
    <form method="GET" id="filterForm" class="filter-form">
      <input
        type="text"
        name="search"
        placeholder="Cari nama atau kode aset..."
        value="<?= htmlspecialchars($search); ?>">

      <select name="kategori" id="kategori">
        <option value="">-- Kategori --</option>
        <?php
        $kategori_q = mysqli_query($conn, "SELECT * FROM categories ORDER BY nama_kategori");
        while ($k = mysqli_fetch_assoc($kategori_q)) {
          $selected = ($kategori == $k['id_kategori']) ? 'selected' : '';
          echo "<option value='{$k['id_kategori']}' $selected>{$k['nama_kategori']}</option>";
        }
        ?>
      </select>

      <select name="kondisi" onchange="this.form.submit()">
        <option value="">-- Kondisi --</option>
        <option value="baik" <?= $kondisi == 'baik' ? 'selected' : '' ?>>Baik</option>
        <option value="rusak" <?= $kondisi == 'rusak' ? 'selected' : '' ?>>Rusak</option>
        <option value="hilang" <?= $kondisi == 'hilang' ? 'selected' : '' ?>>Hilang</option>
      </select>


      <select name="status" id="status">
        <option value="">-- Status --</option>
        <option value="Tersedia" <?= $status == 'Tersedia' ? 'selected' : '' ?>>Tersedia</option>
        <option value="Dipinjam" <?= $status == 'Dipinjam' ? 'selected' : '' ?>>Dipinjam</option>
        <option value="Maintenance" <?= $status == 'Maintenance' ? 'selected' : '' ?>>Maintenance</option>
      </select>

      <!-- Tombol Filter dihapus -->
      <a href="add.php" class="btn btn-primary">+ Tambah Aset</a>
      <a href="trash.php" class="btn btn-secondary">🗑️ Aset Terhapus</a>
    </form>

    <script>
      document.addEventListener('DOMContentLoaded', () => {
        // Ambil semua dropdown
        const selects = document.querySelectorAll('#filterForm select');

        // Kalau ada perubahan nilai, langsung submit
        selects.forEach(select => {
          select.addEventListener('change', () => {
            document.getElementById('filterForm').submit();
          });
        });
      });
    </script>


    <!-- Grid Aset -->
    <div class="asset-grid">
      <?php if (mysqli_num_rows($query) > 0): ?>
        <?php while ($row = mysqli_fetch_assoc($query)): ?>
          <div class="asset-card">
            <img src="../../assets/uploads/assets/<?= htmlspecialchars($row['foto']); ?>"
              alt="<?= htmlspecialchars($row['nama_aset']); ?>">
            <div class="asset-info">
              <h3><?= htmlspecialchars($row['nama_aset']); ?></h3>
              <p><strong>Kode:</strong> <?= htmlspecialchars($row['kode_aset']); ?></p>
              <p><strong>Kategori:</strong> <?= htmlspecialchars($row['nama_kategori']); ?></p>
              <p><strong>Kondisi:</strong> <?= htmlspecialchars($row['kondisi']); ?></p>
              <p><strong>Status:</strong> <?= htmlspecialchars($row['status']); ?></p>
            </div>
            <div class="asset-actions">
              <a href="detail.php?id=<?= $row['id_aset']; ?>" class="btn-action view">Detail</a>
              <a href="edit.php?id=<?= $row['id_aset']; ?>" class="btn-action edit">Edit</a>
              <a href="delete.php?id_aset=<?= $row['id_aset']; ?>" class="btn-action delete"
                onclick="return confirm('Yakin ingin menghapus aset ini?')">Hapus</a>
            </div>
          </div>
        <?php endwhile; ?>
      <?php else: ?>
        <p class="no-data">Tidak ada aset ditemukan.</p>
      <?php endif; ?>
    </div>

    <!-- Pagination -->
    <div class="pagination">
      <?php for ($i = 1; $i <= $total_pages; $i++): ?>
        <a href="?page=<?= $i ?>&search=<?= urlencode($search) ?>&kategori=<?= $kategori ?>&kondisi=<?= $kondisi ?>&status=<?= $status ?>"
          class="<?= $i == $page ? 'active' : '' ?>">
          <?= $i ?>
        </a>
      <?php endfor; ?>
    </div>
  </div>
</main>

<?php include '../../includes/footer.php'; ?>