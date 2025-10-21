<?php
define("SECURE", true);

if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

include '../../includes/auth_check.php';
include '../../includes/role_check.php';
include '../../includes/notification_helper.php';
include '../../config/config.php';
include '../../config/database.php';
include '../../includes/header.php';
include '../../includes/sidebar.php';

checkRole(['admin']);

// Pagination
$limit = 10;
$page = isset($_GET['page']) ? (int)$_GET['page'] : 1;
$offset = ($page - 1) * $limit;

// Dropdown filter
$search = isset($_GET['search']) ? trim($_GET['search']) : '';

// Query kategori
$where = "";
$params = [];
$paramTypes = "";

if (!empty($search)) {
    // Jika user pilih kategori tertentu
    $where = "WHERE c.nama_kategori = ?";
    $params[] = $search;
    $paramTypes .= "s";
}

$sql = "SELECT c.*, COUNT(a.id_aset) AS total_aset
        FROM categories c
        LEFT JOIN assets a ON c.id_kategori = a.id_kategori
        $where
        GROUP BY c.id_kategori
        ORDER BY c.nama_kategori ASC
        LIMIT ? OFFSET ?";

$params[] = $limit;
$params[] = $offset;
$paramTypes .= "ii";

$stmt = $conn->prepare($sql);
$stmt->bind_param($paramTypes, ...$params);
$stmt->execute();
$result = $stmt->get_result();

// Hitung total data
$countSql = "SELECT COUNT(*) AS total FROM categories c " . ($where ? $where : "");
$countStmt = $conn->prepare($countSql);
if (!empty($search)) {
    $countStmt->bind_param("s", $search);
}
$countStmt->execute();
$total = $countStmt->get_result()->fetch_assoc()['total'];
$total_pages = ceil($total / $limit);
?>


<main class="main-content">
  <?php display_notification(); ?>

  <?php if (isset($_SESSION['success_message'])): ?>
    <div class="alert success">
      <?= htmlspecialchars($_SESSION['success_message']); ?>
    </div>
    <?php unset($_SESSION['success_message']); ?>
  <?php endif; ?>

  <div class="card">
    <h1 class="page-title">Manajemen Kategori Aset</h1>

    <!-- 🔽 Dropdown Filter -->
    <form method="GET" class="filter-form" style="margin-bottom:15px;">
  <select name="search" onchange="this.form.submit()">
    <option value="">Semua Kategori</option>
    <?php
    $kategoriResult = $conn->query("SELECT nama_kategori FROM categories ORDER BY nama_kategori ASC");
    while ($kategori = $kategoriResult->fetch_assoc()):
        $selected = ($search === $kategori['nama_kategori']) ? 'selected' : '';
    ?>
      <option value="<?= htmlspecialchars($kategori['nama_kategori']); ?>" <?= $selected; ?>>
        <?= htmlspecialchars($kategori['nama_kategori']); ?>
      </option>
    <?php endwhile; ?>
  </select>

  <a href="add.php" class="btn btn-primary">+ Tambah Kategori</a>
</form>


    <!-- 🔹 Tabel Data -->
    <table class="table-custom">
      <thead>
        <tr>
          <th>No</th>
          <th>Nama Kategori</th>
          <th>Deskripsi</th>
          <th>Jumlah Aset</th>
          <th>Aksi</th>
        </tr>
      </thead>
      <tbody>
        <?php if ($result && $result->num_rows > 0): ?>
          <?php $no = $offset + 1; ?>
          <?php while ($row = $result->fetch_assoc()): ?>
            <tr>
              <td><?= $no++; ?></td>
              <td><?= htmlspecialchars($row['nama_kategori']); ?></td>
              <td><?= htmlspecialchars($row['deskripsi']); ?></td>
              <td><?= $row['total_aset']; ?></td>
              <td>
                <a href="edit.php?id=<?= $row['id_kategori']; ?>" class="btn-action edit">Edit</a> |
                <a href="delete.php?id=<?= $row['id_kategori']; ?>" class="btn-action delete" onclick="return confirm('Hapus kategori ini?')">Hapus</a>
              </td>
            </tr>
          <?php endwhile; ?>
        <?php else: ?>
          <tr>
            <td colspan="5" style="text-align:center;">Tidak ada data kategori.</td>
          </tr>
        <?php endif; ?>
      </tbody>
    </table>

    <!-- 🔸 Pagination -->
    <div class="pagination">
      <?php for ($i = 1; $i <= $total_pages; $i++): ?>
        <a href="?page=<?= $i ?>&search=<?= urlencode($search) ?>"
           class="<?= $i == $page ? 'active' : '' ?>">
           <?= $i ?>
        </a>
      <?php endfor; ?>
    </div>
  </div>
</main>

<?php include '../../includes/footer.php'; ?>
