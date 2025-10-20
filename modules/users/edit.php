<?php
session_start();
include '../../includes/auth_check.php';
include '../../includes/role_check.php';
include '../../config/config.php';
include '../../config/database.php';
include '../../includes/notification_helper.php';
checkRole(['admin']);

// Pastikan id_user ada di URL
if (!isset($_GET['id_user'])) {
  die("❌ ID user tidak ditemukan.");
}

$id_user = $_GET['id_user'];

// Ambil data user berdasarkan id_user
$user = mysqli_fetch_assoc(mysqli_query($conn, "SELECT * FROM users WHERE id_user='$id_user'"));
if (!$user) {
  die("❌ Data user tidak ditemukan.");
}

$message = '';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
  $nama  = $_POST['nama'];
  $email = $_POST['email'];
  $role  = $_POST['role'];

   // Cek email unik untuk user lain
  $check = mysqli_query($conn, "SELECT id_user FROM users WHERE email='$email' AND id_user!='$id_user'");
  if (mysqli_num_rows($check) > 0) {
    // Email sudah dipakai user lain
    set_notification('error', '❌ Email sudah digunakan oleh user lain.');
  } else {
    // Update data user
    $update = mysqli_query($conn, "UPDATE users SET nama='$nama', email='$email', role='$role' WHERE id_user='$id_user'");
    if ($update) {
      set_notification('success', '✅ Data user berhasil diperbarui.');
    } else {
      set_notification('error', '❌ Gagal memperbarui data user.');
    }
  }

  // Redirect agar alert muncul di index
  header('Location: index.php');
  exit;
}
?>

<main class="main-content">
  <h1>Edit User</h1>
  <?php if ($message) echo "<div class='alert'>$message</div>"; ?>

  <form method="POST">
    <label>Nama</label>
    <input type="text" name="nama" value="<?= htmlspecialchars($user['nama']); ?>" required>

    <label>Email</label>
    <input type="email" name="email" value="<?= htmlspecialchars($user['email']); ?>" required>

    <label>Role</label>
    <select name="role">
      <option value="admin" <?= $user['role']=='admin'?'selected':''; ?>>Admin</option>
      <option value="manajemen" <?= $user['role']=='manajemen'?'selected':''; ?>>Manajemen</option>
      <option value="pengguna" <?= $user['role']=='pengguna'?'selected':''; ?>>Pengguna</option>
    </select>

    <button type="submit" class="btn-save">Simpan</button>
  </form>
</main>
