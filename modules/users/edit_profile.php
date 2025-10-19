<?php
define("SECURE", true);

if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
include '../../includes/auth_check.php';
include '../../config/config.php';
include '../../config/database.php';

$id_user = $_SESSION['user_id'];
$message = "";

// Ambil data lama
$result = mysqli_query($conn, "SELECT * FROM users WHERE id_user = '$id_user'");
$user = mysqli_fetch_assoc($result);

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $nama = trim($_POST['nama']);
    $email = trim($_POST['email']);
    $no_telp = trim($_POST['no_telp']);

    // Cek email unik
    $check_email = mysqli_query($conn, "SELECT id_user FROM users WHERE email='$email' AND id_user != '$id_user'");
    if (mysqli_num_rows($check_email) > 0) {
        $_SESSION['flash'] = "❌ Email sudah digunakan oleh pengguna lain.";
    } else {
        mysqli_query($conn, "UPDATE users SET nama='$nama', email='$email', no_telp='$no_telp' WHERE id_user='$id_user'");
        $_SESSION['flash'] = "✅ Profil berhasil diperbarui.";
        $_SESSION['nama'] = $nama;
    }

    // Ganti password (opsional)
    if (!empty($_POST['password_lama']) && !empty($_POST['password_baru'])) {
        $password_lama = $_POST['password_lama'];
        $password_baru = $_POST['password_baru'];

        if (password_verify($password_lama, $user['password'])) {
            $hashed = password_hash($password_baru, PASSWORD_DEFAULT);
            mysqli_query($conn, "UPDATE users SET password='$hashed' WHERE id_user='$id_user'");
            $_SESSION['flash'] .= "<br>🔒 Password berhasil diganti.";
        } else {
            $_SESSION['flash'] .= "<br>⚠️ Password lama salah.";
        }
    }

    // Redirect ke profil
    header("Location: profile.php");
    exit;
}
?>

<?php include '../../includes/header.php'; ?>
<?php include '../../includes/sidebar.php'; ?>

<main class="main-content" style="
  display: flex;
  justify-content: center;
  align-items: flex-start;
  padding: 40px 0;
  background-color: #f5f7fa;
  min-height: 100vh;
  font-family: 'Segoe UI', sans-serif;
">

  <div class="profile-card" style="
    background: #fff;
    padding: 25px 30px;
    border-radius: 12px;
    box-shadow: 0 3px 8px rgba(0,0,0,0.1);
    max-width: 500px;
    width: 100%;
  ">
    <h1 style="text-align:center;margin-bottom:20px;font-size:22px;color:#333;">Edit Profil</h1>

    <form method="POST" style="display: flex; flex-direction: column; gap: 10px;">
      <label style="font-weight: 600;">Nama Lengkap</label>
      <input type="text" name="nama" value="<?= htmlspecialchars($user['nama']); ?>" required
        style="padding: 8px; border: 1px solid #ccc; border-radius: 6px;">

      <label style="font-weight: 600;">Email</label>
      <input type="email" name="email" value="<?= htmlspecialchars($user['email']); ?>" required
        style="padding: 8px; border: 1px solid #ccc; border-radius: 6px;">

      <label style="font-weight: 600;">No. Telepon</label>
      <input type="text" name="no_telp" value="<?= htmlspecialchars($user['no_telp']); ?>"
        style="padding: 8px; border: 1px solid #ccc; border-radius: 6px;">

      <hr style="margin: 20px 0;">

      <h3 style="font-size: 18px; color: #333; margin-bottom: 10px;">Ubah Password</h3>

      <label style="font-weight: 600;">Password Lama</label>
      <input type="password" name="password_lama" style="padding: 8px; border: 1px solid #ccc; border-radius: 6px;">

      <label style="font-weight: 600;">Password Baru</label>
      <input type="password" name="password_baru" style="padding: 8px; border: 1px solid #ccc; border-radius: 6px;">

      <button type="submit" class="btn-save" style="
        padding: 10px 18px;
        border: none;
        background: #1e3a5f;
        color: white;
        border-radius: 8px;
        margin-top: 15px;
        cursor: pointer;
        font-weight: 600;
      " onmouseover="this.style.background='#0056b3';" onmouseout="this.style.background='#007bff';">
        Simpan Perubahan
      </button>
    </form>
  </div>
</main>

<?php include '../../includes/footer.php'; ?>
