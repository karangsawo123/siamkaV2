<?php
session_start();
include '../../includes/auth_check.php';
include '../../includes/role_check.php';
include '../../config/config.php';
include '../../config/database.php';
include '../../includes/notification_helper.php';
checkRole(['admin']);

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
  $nama = trim($_POST['nama']);
  $email = trim($_POST['email']);
  $no_telp = trim($_POST['no_telp']);
  $role = $_POST['role'];
  $password = password_hash($_POST['password'], PASSWORD_DEFAULT);

  // Cek apakah email sudah terdaftar
  $check = mysqli_query($conn, "SELECT id_user FROM users WHERE email='$email' AND deleted_at IS NULL");

  if (mysqli_num_rows($check) > 0) {
    // ❌ Email duplikat → tampilkan notifikasi dan kembali ke form
    set_notification('error', '❌ Email sudah terdaftar. Gunakan email lain.');
    header('Location: add.php');
    exit;
  } else {
    // ✅ Tambah user baru
    $query = "INSERT INTO users (nama, email, no_telp, role, password) 
              VALUES ('$nama','$email','$no_telp','$role','$password')";

    if (mysqli_query($conn, $query)) {
      set_notification('success', '✅ User baru berhasil ditambahkan!');
    } else {
      set_notification('error', '❌ Gagal menambahkan user.');
    }

    header('Location: index.php');
    exit;
  }
}
?>

<main class="main-content">
  <h1>Tambah User</h1>
  
  <!-- Tampilkan notifikasi -->
  <?php display_notification(); ?>

  <form method="POST">
    <label>Nama</label>
    <input type="text" name="nama" required>

    <label>Email</label>
    <input type="email" name="email" required>

    <label>No. Telp</label>
    <input type="text" name="no_telp">

    <label>Role</label>
    <select name="role" required>
      <option value="admin">Admin</option>
      <option value="manajemen">Manajemen</option>
      <option value="pengguna">Pengguna</option>
    </select>

    <label>Password</label>
    <input type="password" name="password" required>

    <button type="submit" class="btn-save">Simpan</button>
  </form>
</main>
