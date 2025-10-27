<?php
define("SECURE", true);
if (session_status() === PHP_SESSION_NONE) session_start();

require_once '../../includes/auth_check.php';
require_once '../../includes/role_check.php';
require_once '../../config/config.php';
require_once '../../config/database.php';
require_once '../../includes/notification_helper.php';
checkRole(['admin']);

$role_filter = $_GET['role'] ?? '';
$search = $_GET['search'] ?? '';

$query = "SELECT * FROM users WHERE deleted_at IS NULL";
$params = [];
$types = '';

if ($role_filter !== '') {
    $query .= " AND role = ?";
    $params[] = $role_filter;
    $types .= 's';
}

if ($search !== '') {
    $query .= " AND (nama LIKE ? OR email LIKE ?)";
    $params[] = "%$search%";
    $params[] = "%$search%";
    $types .= 'ss';
}

$query .= " ORDER BY id_user DESC";
$stmt = $conn->prepare($query);

if (!empty($params)) {
    $stmt->bind_param($types, ...$params);
}
$stmt->execute();
$result = $stmt->get_result();

include '../../includes/header.php';
include '../../includes/sidebar.php';
?>

<main class="main-content">
    <div class="page-header d-flex justify-content-between align-items-center">
        <h1 class="page-title"><i class="fa-solid fa-users me-2"></i>Manajemen Pengguna</h1>
        <a href="add.php" class="btn btn-primary">
            <i class="fa-solid fa-plus"></i> Tambah User
        </a>
    </div>

    <div class="card shadow-sm">
        <div class="card-body">
            <?php display_notification(); ?>

            <!-- Filter Form -->
            <form method="get" class="row g-3 mb-3">
                <div class="col-md-4">
                    <label class="form-label">Cari Nama / Email</label>
                    <input type="text" name="search" value="<?= htmlspecialchars($search) ?>" class="form-control" placeholder="Masukkan kata kunci...">
                </div>
                <div class="col-md-3">
                    <label class="form-label">Filter Role</label>
                    <select name="role" class="form-select">
                        <option value="">Semua Role</option>
                        <option value="admin" <?= $role_filter == 'admin' ? 'selected' : '' ?>>Admin</option>
                        <option value="manajemen" <?= $role_filter == 'manajemen' ? 'selected' : '' ?>>Manajemen</option>
                        <option value="pengguna" <?= $role_filter == 'pengguna' ? 'selected' : '' ?>>Pengguna</option>
                    </select>
                </div>
                <div class="col-md-2 d-flex align-items-end">
                    <button type="submit" class="btn btn-primary w-100">
                        <i class="fa-solid fa-filter me-1"></i> Filter
                    </button>
                </div>
            </form>

            <!-- Data Table -->
            <div class="table-responsive">
                <table class="table table-striped align-middle">
                    <thead class="table-dark">
                        <tr>
                            <th>No</th>
                            <th>Nama</th>
                            <th>Email</th>
                            <th>Role</th>
                            <th class="text-center">Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php if ($result->num_rows > 0): ?>
                            <?php $no = 1; while ($row = $result->fetch_assoc()): ?>
                                <tr>
                                    <td><?= $no++ ?></td>
                                    <td><?= htmlspecialchars($row['nama']) ?></td>
                                    <td><?= htmlspecialchars($row['email']) ?></td>
                                    <td>
                                        <?php
                                        $role = $row['role'];
                                        $badge = [
                                            'admin' => 'danger',
                                            'manajemen' => 'info',
                                            'pengguna' => 'secondary'
                                        ][$role] ?? 'secondary';
                                        ?>
                                        <span class="badge bg-<?= $badge ?>"><?= ucfirst($role) ?></span>
                                    </td>
                                    <td class="text-center">
                                        <a href="edit.php?id_user=<?= $row['id_user'] ?>" class="btn btn-sm btn-warning me-1">
                                            <i class="fa-solid fa-pen"></i> Edit
                                        </a>
                                        <button type="button" class="btn btn-sm btn-secondary me-1" onclick="openPasswordModal(<?= $row['id_user'] ?>)">
                                            <i class="fa-solid fa-key"></i> Password
                                        </button>
                                        <a href="delete.php?id_user=<?= $row['id_user'] ?>" class="btn btn-sm btn-danger" onclick="return confirm('Hapus user ini?')">
                                            <i class="fa-solid fa-trash"></i> Hapus
                                        </a>
                                    </td>
                                </tr>
                            <?php endwhile; ?>
                        <?php else: ?>
                            <tr>
                                <td colspan="5" class="text-center text-muted py-4">
                                    <i class="fa-solid fa-circle-info me-1"></i> Tidak ada data pengguna.
                                </td>
                            </tr>
                        <?php endif; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</main>

<!-- Modal Ubah Password -->
<div id="passwordModal" class="modal">
  <div class="modal-dialog modal-dialog-centered">
    <div class="modal-content p-4">
      <div class="d-flex justify-content-between align-items-center mb-3">
        <h5 class="mb-0"><i class="fa-solid fa-key me-1"></i>Ubah Password</h5>
        <button type="button" class="btn-close" onclick="closePasswordModal()"></button>
      </div>
      <form method="POST" action="change_password.php">
        <input type="hidden" name="id_user" id="modal_id_user">
        <div class="mb-3">
          <label class="form-label">Password Baru</label>
          <input type="password" name="password" class="form-control" placeholder="Masukkan password baru" required>
        </div>
        <div class="text-end">
          <button type="button" class="btn btn-secondary me-2" onclick="closePasswordModal()">Batal</button>
          <button type="submit" class="btn btn-primary">Simpan</button>
        </div>
      </form>
    </div>
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
