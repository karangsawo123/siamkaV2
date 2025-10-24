<?php
define("SECURE", true);

if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

include '../../includes/auth_check.php';
include '../../includes/role_check.php';
include '../../config/config.php';
include '../../config/database.php';
include '../../includes/notification_helper.php';

// Batasi akses: hanya admin & manajemen
checkRole(['admin', 'manajemen']);

// AUTO-GENERATE KODE ASET (contoh: AST-2025-0001)
$query = mysqli_query($conn, "SELECT kode_aset FROM assets ORDER BY id_aset DESC LIMIT 1");
$last = mysqli_fetch_assoc($query);
if ($last) {
    $last_num = (int) substr($last['kode_aset'], -4);
    $new_num = $last_num + 1;
} else {
    $new_num = 1;
}
$kode_aset = 'AST-' . date('Y') . '-' . str_pad($new_num, 4, '0', STR_PAD_LEFT);

$error = '';
$success = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $nama_aset = mysqli_real_escape_string($conn, $_POST['nama_aset']);
    $id_kategori = mysqli_real_escape_string($conn, $_POST['id_kategori']);
    $kondisi = mysqli_real_escape_string($conn, $_POST['kondisi']);
    $status = mysqli_real_escape_string($conn, $_POST['status']);
    $keterangan = mysqli_real_escape_string($conn, $_POST['keterangan']); // ✅ sesuaikan dengan DB
    $lokasi = mysqli_real_escape_string($conn, $_POST['lokasi']);
    $harga = mysqli_real_escape_string($conn, $_POST['harga']);
    $tanggal_perolehan = mysqli_real_escape_string($conn, $_POST['tanggal_perolehan']);

    // Upload foto
    $foto = $_FILES['foto']['name'];
    $tmp_name = $_FILES['foto']['tmp_name'];
    $upload_dir = '../../assets/uploads/assets/';
    $max_size = 2 * 1024 * 1024; // 2MB
    $allowed_types = ['jpg', 'jpeg', 'png'];

    if (!empty($foto)) {
        $ext = strtolower(pathinfo($foto, PATHINFO_EXTENSION));
        if (!in_array($ext, $allowed_types)) {
            $error = "Format foto tidak valid (hanya JPG/PNG).";
        } elseif ($_FILES['foto']['size'] > $max_size) {
            $error = "Ukuran foto maksimal 2MB.";
        } else {
            $new_filename = uniqid('AST_', true) . '.' . $ext;
            move_uploaded_file($tmp_name, $upload_dir . $new_filename);
        }
    } else {
        $new_filename = 'default.png';
    }

    if (empty($error)) {
        $sql = "INSERT INTO assets 
                (kode_aset, nama_aset, id_kategori, lokasi, kondisi, status, harga, tanggal_perolehan, keterangan, foto)
                VALUES 
                ('$kode_aset', '$nama_aset', '$id_kategori', '$lokasi', '$kondisi', '$status', '$harga', '$tanggal_perolehan', '$keterangan', '$new_filename')";

        if (mysqli_query($conn, $sql)) {
            set_notification('success', 'Aset berhasil ditambahkan!');
            header("Location: index.php");
            exit;
        } else {
            set_notification('danger', 'Gagal menyimpan data aset: ' . mysqli_error($conn));
        }
    } else {
        set_notification('danger', $error);
    }
}

include '../../includes/header.php';
include '../../includes/sidebar.php';
?>

<main class="main-content">
  <div class="card">
    <h1 class="page-title">🆕 Tambah Aset Baru</h1>

    <?php display_notification(); ?>

    <form action="" method="POST" enctype="multipart/form-data" class="form-container">

      <div class="form-group">
        <label>Kode Aset</label>
        <input type="text" name="kode_aset" value="<?= htmlspecialchars($kode_aset); ?>" readonly>
      </div>

      <div class="form-group">
        <label>Nama Aset</label>
        <input type="text" name="nama_aset" placeholder="Masukkan nama aset" required>
      </div>

      <div class="form-group">
        <label>Kategori</label>
        <select name="id_kategori" required>
          <option value="">-- Pilih Kategori --</option>
          <?php
          $q = mysqli_query($conn, "SELECT * FROM categories ORDER BY nama_kategori");
          while ($k = mysqli_fetch_assoc($q)) {
              echo "<option value='{$k['id_kategori']}'>{$k['nama_kategori']}</option>";
          }
          ?>
        </select>
      </div>

      <div class="form-group">
        <label>Lokasi</label>
        <input type="text" name="lokasi" placeholder="Masukkan lokasi aset" required>
      </div>

      <div class="form-group">
        <label>Kondisi</label>
        <select name="kondisi" required>
          <option value="Baik">Baik</option>
          <option value="Rusak Ringan">Rusak Ringan</option>
          <option value="Rusak Berat">Rusak Berat</option>
        </select>
      </div>

      <div class="form-group">
        <label>Status</label>
        <select name="status" required>
          <option value="Tersedia">Tersedia</option>
          <option value="Dipinjam">Dipinjam</option>
          <option value="Maintenance">Maintenance</option>
        </select>
      </div>

      <div class="form-group">
        <label>Harga</label>
        <input type="number" name="harga" placeholder="Masukkan harga aset" required>
      </div>

      <div class="form-group">
        <label>Tanggal Perolehan</label>
        <input type="date" name="tanggal_perolehan" required>
      </div>

      <div class="form-group">
        <label>Keterangan</label> <!-- ✅ disesuaikan -->
        <textarea name="keterangan" rows="4" placeholder="Tuliskan detail kondisi atau keterangan aset..."></textarea>
      </div>

      <div class="form-group">
        <label>Foto Aset</label>
        <input type="file" name="foto" accept=".jpg,.jpeg,.png" onchange="previewImage(event)">
        <div id="fotoPreview" style="margin-top:10px;">
          <img id="previewImg" src="../../assets/uploads/assets/default.png" alt="Preview Foto"
               style="max-width:200px; height:auto; display:none; border:1px solid #ccc; padding:2px;">
        </div>
      </div>

      <div class="form-actions">
        <button type="submit" class="btn btn-primary">💾 Simpan</button>
        <a href="index.php" class="btn btn-secondary">↩️ Kembali</a>
      </div>

    </form>
  </div>
</main>

<script>
function previewImage(event) {
  const input = event.target;
  const preview = document.getElementById('previewImg');
  if (input.files && input.files[0]) {
      const reader = new FileReader();
      reader.onload = function(e) {
          preview.src = e.target.result;
          preview.style.display = 'block';
      }
      reader.readAsDataURL(input.files[0]);
  } else {
      preview.src = '';
      preview.style.display = 'none';
  }
}
</script>

<?php include '../../includes/footer.php'; ?>
