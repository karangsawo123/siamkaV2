<?php
define("SECURE", true);

if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// Include file penting (yang tidak menghasilkan output)
include '../../includes/auth_check.php';
include '../../includes/role_check.php';
include '../../includes/notification_helper.php';
include '../../config/config.php';
include '../../config/database.php';

// Batasi akses hanya admin
checkRole(['admin']);

// Pastikan id_user ada
if (!isset($_GET['id_user'])) {
    die("❌ ID user tidak ditemukan.");
}

$id_user = $_GET['id_user'];
$user = mysqli_fetch_assoc(mysqli_query($conn, "SELECT * FROM users WHERE id_user='$id_user' AND deleted_at IS NULL"));
if (!$user) {
    die("❌ Data user tidak ditemukan.");
}

// Proses form sebelum HTML ditampilkan
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $nama  = trim($_POST['nama']);
    $email = trim($_POST['email']);
    $role  = $_POST['role'];

    // Cek email unik
    $check = mysqli_query($conn, "SELECT id_user FROM users WHERE email='$email' AND id_user!='$id_user' AND deleted_at IS NULL");

    if (mysqli_num_rows($check) > 0) {
        set_notification('error', '❌ Email sudah digunakan oleh user lain.');
    } else {
        $update = mysqli_query($conn, "UPDATE users SET nama='$nama', email='$email', role='$role' WHERE id_user='$id_user'");
        if ($update) {
            set_notification('success', '✅ Data user berhasil diperbarui.');
        } else {
            set_notification('error', '❌ Gagal memperbarui data user.');
        }
    }

    // Redirect sebelum ada output HTML
    header('Location: index.php');
    exit;
}

// Baru tampilkan HTML setelah semua proses selesai
include '../../includes/header.php';
include '../../includes/sidebar.php';
?>

<main class="main-content">
  <?php display_notification(); ?>

  <div class="card">
    <h1 class="page-title">Edit User</h1>

    <form method="POST" class="form-container">
      <div class="form-group">
        <label>Nama</label>
        <input type="text" name="nama" value="<?= htmlspecialchars($user['nama']); ?>" required>
      </div>

      <div class="form-group">
        <label>Email</label>
        <input type="email" name="email" value="<?= htmlspecialchars($user['email']); ?>" required>
      </div>

      <div class="form-group">
        <label>Role</label>
        <select name="role" required>
          <option value="admin" <?= $user['role']=='admin' ? 'selected' : ''; ?>>Admin</option>
          <option value="manajemen" <?= $user['role']=='manajemen' ? 'selected' : ''; ?>>Manajemen</option>
          <option value="pengguna" <?= $user['role']=='pengguna' ? 'selected' : ''; ?>>Pengguna</option>
        </select>
      </div>

      <div class="form-actions">
        <button type="submit" class="btn btn-primary">Simpan</button>
        <a href="index.php" class="btn btn-secondary">Batal</a>
      </div>
    </form>
  </div>
</main>

<?php include '../../includes/footer.php'; ?>
