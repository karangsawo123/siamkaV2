<?php
define("SECURE", true);

if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// Include file logika (tidak menghasilkan output HTML)
include '../../includes/auth_check.php';
include '../../includes/role_check.php';
include '../../includes/notification_helper.php';
include '../../config/config.php';
include '../../config/database.php';

// Batasi akses hanya admin
checkRole(['admin']);

// Proses form submit
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $nama = trim($_POST['nama']);
    $email = trim($_POST['email']);
    $no_telp = trim($_POST['no_telp']);
    $role = $_POST['role'];
    $password = password_hash($_POST['password'], PASSWORD_DEFAULT);

    // Cek apakah email sudah terdaftar
    $check = mysqli_query($conn, "SELECT id_user FROM users WHERE email='$email' AND deleted_at IS NULL");

    if (mysqli_num_rows($check) > 0) {
        set_notification('error', '❌ Email sudah terdaftar. Gunakan email lain.');
        header('Location: add.php');
        exit;
    } else {
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

// Setelah semua logika selesai, baru tampilkan tampilan
include '../../includes/header.php';
include '../../includes/sidebar.php';
?>

<main class="main-content">
  <?php display_notification(); ?>

  <div class="card">
    <h1 class="page-title">Tambah User</h1>

    <form method="POST" class="form-container">

      <div class="form-group">
        <label>Nama</label>
        <input type="text" name="nama" required>
      </div>

      <div class="form-group">
        <label>Email</label>
        <input type="email" name="email" required>
      </div>

      <div class="form-group">
        <label>No. Telp</label>
        <input type="text" name="no_telp">
      </div>

      <div class="form-group">
        <label>Role</label>
        <select name="role" required>
          <option value="admin">Admin</option>
          <option value="manajemen">Manajemen</option>
          <option value="pengguna">Pengguna</option>
        </select>
      </div>

      <div class="form-group">
        <label>Password</label>
        <input type="password" name="password" required>
      </div>

      <div class="form-actions">
        <button type="submit" class="btn btn-primary">Simpan</button>
        <a href="index.php" class="btn btn-secondary">Batal</a>
      </div>

    </form>
  </div>
</main>

<?php include '../../includes/footer.php'; ?>
