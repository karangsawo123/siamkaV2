<?php
define("SECURE", true);
if (session_status() === PHP_SESSION_NONE) session_start();

include '../../includes/auth_check.php';
include '../../config/config.php';
include '../../config/database.php';
include '../../includes/header.php';
include '../../includes/sidebar.php';

$id_user = $_SESSION['user_id'];

// Ambil data user
$query = "SELECT nama, email, no_telp, role FROM users WHERE id_user = '$id_user'";
$result = mysqli_query($conn, $query);
$user = mysqli_fetch_assoc($result);
?>

<main class="main-content" style="padding: 20px;">

  <h1 style="font-size: 24px; margin-bottom: 20px;">Profil Saya</h1>

  <!-- ✅ Notifikasi (Flash Message) -->
  <?php if (!empty($_SESSION['flash'])): ?>
    <div id="toast" style="
      position: fixed;
      top: 20%;
      left: 50%;
      transform: translate(-50%, -50%) scale(0.9);
      background: #1e3a5f;
      color: #fff;
      padding: 14px 24px;
      border-radius: 12px;
      box-shadow: 0 6px 16px rgba(0,0,0,0.2);
      z-index: 9999;
      font-family: 'Segoe UI', sans-serif;
      font-size: 15px;
      text-align: center;
      opacity: 0;
      transition: all 0.5s ease;
    ">
      <?= $_SESSION['flash']; unset($_SESSION['flash']); ?>
    </div>

    <script>
      const toast = document.getElementById('toast');
      if (toast) {
        setTimeout(() => {
          toast.style.opacity = '1';
          toast.style.transform = 'translate(-50%, -50%) scale(1)';
        }, 100);
        setTimeout(() => {
          toast.style.opacity = '0';
          toast.style.transform = 'translate(-50%, -50%) scale(0.9)';
          setTimeout(() => toast.remove(), 500);
        }, 2500);
      }
    </script>
  <?php endif; ?>

  <!-- ✅ Kartu Profil -->
  <div class="profile-card" style="
      background: #fff;
      padding: 20px;
      border-radius: 12px;
      box-shadow: 0 2px 6px rgba(0,0,0,0.1);
      max-width: 500px;
      margin-bottom: 20px;
  ">
    <p><b>Nama:</b> <?= htmlspecialchars($user['nama']); ?></p>
    <p><b>Email:</b> <?= htmlspecialchars($user['email']); ?></p>
    <p><b>No. Telepon:</b> <?= htmlspecialchars($user['no_telp']); ?></p>
    <p><b>Role:</b> <?= ucfirst($user['role']); ?></p>
  </div>

  <!-- ✅ Tombol menuju halaman edit -->
  <a href="edit_profile.php" style="
      display: inline-block;
      padding: 10px 16px;
      background: #1e3a5f;
      color: white;
      text-decoration: none;
      border-radius: 8px;
  ">Edit Profil</a>

</main>

<?php include '../../includes/footer.php'; ?>
