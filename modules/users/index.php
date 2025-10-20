<?php
define("SECURE", true);

// Pastikan session aktif
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


// Pagination setup
$limit = 10;
$page = isset($_GET['page']) ? (int)$_GET['page'] : 1;
$offset = ($page - 1) * $limit;

// Filter dan search
$role_filter = isset($_GET['role']) ? $_GET['role'] : '';
$search = isset($_GET['search']) ? $_GET['search'] : '';

$where = "WHERE deleted_at IS NULL";
if ($role_filter) $where .= " AND role='$role_filter'";
if ($search) $where .= " AND (nama LIKE '%$search%' OR email LIKE '%$search%')";

// Hitung total user
$total_result = mysqli_query($conn, "SELECT COUNT(*) as total FROM users $where");
$total_users = mysqli_fetch_assoc($total_result)['total'];
$total_pages = ceil($total_users / $limit);

// Ambil data user
$query = mysqli_query($conn, "SELECT * FROM users $where ORDER BY id_user DESC LIMIT $limit OFFSET $offset");
?>

<main class="main-content">

<?php
if (isset($_SESSION['success_message'])) {
  echo "<div class='alert success'>" . $_SESSION['success_message'] . "</div>";
  unset($_SESSION['success_message']); // hapus supaya tidak muncul terus
}
?>

  <h1>Manajemen Pengguna</h1>

  <form method="GET" class="filter-form">
    <input type="text" name="search" placeholder="Cari nama/email" value="<?= htmlspecialchars($search); ?>">
    <select name="role">
      <option value="">Semua Role</option>
      <option value="admin" <?= $role_filter=='admin'?'selected':''; ?>>Admin</option>
      <option value="manajemen" <?= $role_filter=='manajemen'?'selected':''; ?>>Manajemen</option>
      <option value="pengguna" <?= $role_filter=='pengguna'?'selected':''; ?>>Pengguna</option>
    </select>
    <button type="submit">Filter</button>
    <a href="add.php" class="btn-add">+ Tambah User</a>
  </form>

  <table class="data-table">
    <thead>
      <tr>
        <th>No</th><th>Nama</th><th>Email</th><th>Role</th><th>Aksi</th>
      </tr>
    </thead>
    <tbody>
      <?php
      $no = $offset + 1;
      while ($row = mysqli_fetch_assoc($query)): ?>
        <tr>
          <td><?= $no++; ?></td>
          <td><?= htmlspecialchars($row['nama']); ?></td>
          <td><?= htmlspecialchars($row['email']); ?></td>
          <td><?= ucfirst($row['role']); ?></td>
          <td>
            <a href="edit.php?id_user=<?= $row['id_user']; ?>">Edit</a> |
            <a href="change_password.php?id_user=<?= $row['id_user']; ?>">Password</a> |
            <a href="delete.php?id_user=<?= $row['id_user']; ?>" onclick="return confirm('Hapus user ini?')">Hapus</a>
          </td>
        </tr>
      <?php endwhile; ?>
    </tbody>
  </table>

  <!-- Pagination -->
  <div class="pagination">
    <?php for ($i = 1; $i <= $total_pages; $i++): ?>
      <a href="?page=<?= $i ?>&search=<?= urlencode($search) ?>&role=<?= $role_filter ?>"
         class="<?= $i == $page ? 'active' : '' ?>">
         <?= $i ?>
      </a>
    <?php endfor; ?>
  </div>
</main>

<?php include '../../includes/footer.php'; ?>
