<?php
session_start();
include '../../includes/auth_check.php';
include '../../includes/role_check.php';
include '../../config/config.php';
include '../../config/database.php';
include '../../includes/notification_helper.php';
checkRole(['admin']);

$id_user = $_GET['id_user'] ?? null;
if (!$id_user) {
  die("❌ ID user tidak ditemukan.");
}

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $password = password_hash($_POST['password'], PASSWORD_DEFAULT);
    mysqli_query($conn, "UPDATE users SET password='$password' WHERE id_user = '$id_user'");

    set_notification('success', '✅ Password berhasil diubah.');
    header('Location: index.php');
    exit;
}

?>

<main class="main-content">
  <h1>Ubah Password</h1>
  <?php if (!empty($message)) echo "<div class='alert'>$message</div>"; ?>

  <form method="POST">
    <label>Password Baru</label>
    <input type="password" name="password" required>
    <button type="submit" class="btn-save">Simpan</button>
  </form>
</main>
