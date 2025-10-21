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
  <?php if (isset($_SESSION['success_message'])): ?>
    <div class="alert success">
      <?= htmlspecialchars($_SESSION['success_message']); ?>
    </div>
    <?php unset($_SESSION['success_message']); ?>
  <?php endif; ?>

  <div class="card">
    <h1 class="page-title">Manajemen Pengguna</h1>

    <form method="GET" class="filter-form">
      <input type="text" name="search" placeholder="Cari nama/email..." value="<?= htmlspecialchars($search); ?>">
      
      <select name="role">
        <option value="">Semua Role</option>
        <option value="admin" <?= $role_filter=='admin'?'selected':''; ?>>Admin</option>
        <option value="manajemen" <?= $role_filter=='manajemen'?'selected':''; ?>>Manajemen</option>
        <option value="pengguna" <?= $role_filter=='pengguna'?'selected':''; ?>>Pengguna</option>
      </select>

      <button type="submit" class="btn btn-secondary">Filter</button>
      <a href="add.php" class="btn btn-primary">+ Tambah User</a>
    </form>

    <table class="table-custom">
      <thead>
        <tr>
          <th>No</th>
          <th>Nama</th>
          <th>Email</th>
          <th>Role</th>
          <th>Aksi</th>
        </tr>
      </thead>
      <tbody>
        <?php $no = $offset + 1; while ($row = mysqli_fetch_assoc($query)): ?>
          <tr>
            <td><?= $no++; ?></td>
            <td><?= htmlspecialchars($row['nama']); ?></td>
            <td><?= htmlspecialchars($row['email']); ?></td>
            <td><?= ucfirst($row['role']); ?></td>
            <td>
              <a href="edit.php?id_user=<?= $row['id_user']; ?>" class="btn-action edit">Edit</a> |
              <button type="button" class="btn-action password" onclick="openPasswordModal(<?= $row['id_user']; ?>)">Password</button> |
              <a href="delete.php?id_user=<?= $row['id_user']; ?>" onclick="return confirm('Hapus user ini?')" class="btn-action delete">Hapus</a>
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
  </div>    
</main>

<!-- Modal Ubah Password -->
<div id="passwordModal" class="modal">
  <div class="modal-content">
    <span class="close" onclick="closePasswordModal()">&times;</span>
    <h2>Ubah Password</h2>
    <form method="POST" action="change_password.php" class="form-container">
      <input type="hidden" name="id_user" id="modal_id_user">
      <div class="form-group">
        <label>Password Baru</label>
        <input type="password" name="password" placeholder="Masukkan password baru" required>
      </div>
      <div class="form-actions">
        <button type="submit" class="btn btn-primary">Simpan</button>
        <button type="button" class="btn btn-secondary" onclick="closePasswordModal()">Batal</button>
      </div>
    </form>
  </div>
</div>

<script>
function openPasswordModal(id_user) {
  document.getElementById('modal_id_user').value = id_user;
  document.getElementById('passwordModal').style.display = 'flex';
}

function closePasswordModal() {
  document.getElementById('passwordModal').style.display = 'none';
}
</script>


<?php include '../../includes/footer.php'; ?>
