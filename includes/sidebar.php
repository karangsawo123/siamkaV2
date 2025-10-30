<?php
if (!defined("SECURE")) {
    die("Direct access not allowed");
}
require_once __DIR__ . '/../config/config.php';
require_once __DIR__ . '/../config/database.php';

require_once __DIR__ . '/functions.php';
require_once __DIR__ . '/notification_helper.php';

// Fallback jika helper tidak ditemukan
if (!function_exists('display_notification')) {
    function display_notification($type = 'info', $message = '')
    {
        if (empty($message)) return;
        echo "<div class='alert alert-{$type} mt-2'>{$message}</div>";
    }
}

$current_page = basename($_SERVER["PHP_SELF"]);
$user_role = $_SESSION["role"] ?? "user";
?>
<?php
// Hitung jumlah peminjaman baru (belum diproses)
$notif_loans = 0;
$notif_query = mysqli_query($conn, "SELECT COUNT(*) AS total FROM loans WHERE status = 'Pending'");
if ($notif_query) {
    $notif_loans = mysqli_fetch_assoc($notif_query)['total'];
}
?>
<!-- ====== LAYOUT WRAPPER ====== -->
<div class="d-flex">

    <!-- ====== SIDEBAR ====== -->
    <aside class="sidebar bg-dark text-white shadow-sm d-flex flex-column"
        style="width:250px; position:fixed; top:56px; bottom:0; left:0; overflow-y:auto;">

        <!-- 🔹 Header Sidebar -->
        <div class="sidebar-header text-center border-bottom border-secondary py-3 mb-3">
            <img src="<?= !empty($_SESSION['foto'])
                            ? BASE_URL . 'uploads/users/' . $_SESSION['foto']
                            : BASE_URL . 'assets/images/default-user.png'; ?>"
                alt="User Photo"
                class="rounded-circle mb-2"
                width="90" height="90"
                style="object-fit: cover; border:2px solid gold;">

            <h6 class="fw-bold mb-0"><?= htmlspecialchars($_SESSION['nama'] ?? 'User') ?></h6>
            <small class="text-warning"><?= strtoupper($_SESSION['role'] ?? 'GUEST') ?></small>
        </div>



        <!-- 🔸 Navigasi -->
        <nav class="nav flex-column px-2">
            <a href="<?= BASE_URL ?>modules/dashboard/<?= $user_role ?>.php"
                class="nav-link text-white py-2 px-3 rounded mb-1 <?= $current_page == $user_role . '.php' ? 'active bg-primary' : '' ?>">
                <i class="fa-solid fa-gauge me-2"></i> Dashboard
            </a>

            <?php if ($user_role == "admin"): ?>
                <a href="<?= BASE_URL ?>modules/users/index.php"
                    class="nav-link text-white py-2 px-3 rounded mb-1 <?= $current_page == 'index.php' && strpos($_SERVER['REQUEST_URI'], '/users/') !== false ? 'active bg-primary' : '' ?>">
                    <i class="fa-solid fa-users me-2"></i> Kelola User
                </a>

                <a href="<?= BASE_URL ?>modules/categories/index.php"
                    class="nav-link text-white py-2 px-3 rounded mb-1 <?= $current_page == 'index.php' && strpos($_SERVER['REQUEST_URI'], '/categories/') !== false ? 'active bg-primary' : '' ?>">
                    <i class="fa-solid fa-tags me-2"></i> Kategori
                </a>

                <a href="<?= BASE_URL ?>modules/assets/index.php"
                    class="nav-link text-white py-2 px-3 rounded mb-1 <?= $current_page == 'index.php' && strpos($_SERVER['REQUEST_URI'], '/assets/') !== false ? 'active bg-primary' : '' ?>">
                    <i class="fa-solid fa-box me-2"></i> Kelola Aset
                </a>

                <a href="<?= BASE_URL ?>modules/loans/admin_loans.php"
                    class="nav-link text-white py-2 px-3 rounded mb-1 <?= $current_page == 'admin_loans.php' ? 'active bg-primary' : '' ?>">
                    <i class="fa-solid fa-clipboard-list me-2"></i> Kelola Peminjaman
                    <?php if ($notif_loans > 0): ?>
                        <span class="badge bg-warning text-dark ms-2" style="font-size: 0.75rem;">
                            <?= $notif_loans ?>
                        </span>
                    <?php endif; ?>
                </a>
                <!-- 🔹 Tambahan baru: Loan Reports -->
                <a href="<?= BASE_URL ?>modules/reports/loans_report.php"
                    class="nav-link text-white py-2 px-3 rounded mb-1 <?= $current_page == 'loans_report.php' ? 'active bg-primary' : '' ?>">
                    <i class="fa-solid fa-file-lines me-2"></i> Laporan Peminjaman
                </a>

                <a href="<?= BASE_URL ?>modules/damage/index.php"
                    class="nav-link text-white py-2 px-3 rounded mb-1 <?= $current_page == 'index.php' && strpos($_SERVER['REQUEST_URI'], '/damage/') !== false ? 'active bg-primary' : '' ?>">
                    <i class="fa-solid fa-wrench me-2"></i> Laporan Kerusakan
                </a>

                <a href="<?= BASE_URL ?>modules/maintenance/schedule.php"
                    class="nav-link text-white py-2 px-3 rounded mb-1 <?= $current_page == 'schedule.php' ? 'active bg-primary' : '' ?>">
                    <i class="fa-solid fa-screwdriver-wrench me-2"></i> Maintenance
                </a>

                <a href="<?= BASE_URL ?>modules/reports/summary.php"
                    class="nav-link text-white py-2 px-3 rounded mb-1 <?= $current_page == 'summary.php' ? 'active bg-primary' : '' ?>">
                    <i class="fa-solid fa-chart-column me-2"></i> Laporan
                </a>




            <?php elseif ($user_role == "pengguna"): ?>
                <a href="<?= BASE_URL ?>modules/loans/available.php"
                    class="nav-link text-white py-2 px-3 rounded mb-1 <?= $current_page == 'available.php' ? 'active bg-primary' : '' ?>">
                    <i class="fa-solid fa-box-open me-2"></i> Aset Tersedia
                </a>

                <a href="<?= BASE_URL ?>modules/loans/my_loans.php"
                    class="nav-link text-white py-2 px-3 rounded mb-1 <?= $current_page == 'my_loans.php' ? 'active bg-primary' : '' ?>">
                    <i class="fa-solid fa-clipboard-check me-2"></i> Peminjaman Saya
                </a>

                <a href="<?= BASE_URL ?>modules/damage/report.php"
                    class="nav-link text-white py-2 px-3 rounded mb-1 <?= $current_page == 'report.php' ? 'active bg-primary' : '' ?>">
                    <i class="fa-solid fa-tools me-2"></i> Laporan
                </a>
                <a href="<?= BASE_URL ?>modules/damage/my_reports.php"
                    class="nav-link text-white py-2 px-3 rounded mb-1 <?= $current_page == 'my_reports.php' ? 'active bg-primary' : '' ?>">
                    <i class="fa-solid fa-tools me-2"></i> Riwayat Laporan Saya
                </a>
            <?php elseif ($user_role == "manajemen"): ?>
                <a href="<?= BASE_URL ?>modules/assets/index.php"
                    class="nav-link text-white py-2 px-3 rounded mb-1 <?= $current_page == 'index.php' && strpos($_SERVER['REQUEST_URI'], '/assets/') !== false ? 'active bg-primary' : '' ?>">
                    <i class="fa-solid fa-boxes-stacked me-2"></i> Data Aset
                </a>

                <a href="<?= BASE_URL ?>modules/loans/admin_loans.php"
                    class="nav-link text-white py-2 px-3 rounded mb-1 <?= $current_page == 'admin_loans.php' ? 'active bg-primary' : '' ?>">
                    <i class="fa-solid fa-clipboard-list me-2"></i> Data Peminjaman
                </a>

                <a href="<?= BASE_URL ?>modules/reports/summary.php"
                    class="nav-link text-white py-2 px-3 rounded mb-1 <?= $current_page == 'summary.php' ? 'active bg-primary' : '' ?>">
                    <i class="fa-solid fa-chart-pie me-2"></i> Laporan & Statistik
                </a>

                <a href="<?= BASE_URL ?>modules/reports/loans_report.php"
                    class="nav-link text-white py-2 px-3 rounded mb-1 <?= $current_page == 'loans_report.php' ? 'active bg-primary' : '' ?>">
                    <i class="fa-solid fa-file-lines me-2"></i> Laporan Peminjaman
                </a>
            <?php endif; ?>

        </nav>


        <style>
            /* Hover efek warna emas */
            .nav-link:hover {
                background-color: #b8941f !important;
                /* gold */
                color: #212529 !important;
                /* teks gelap agar kontras */
                font-weight: 600;
            }

            .nav-link.active {
                background-color: #ffc107 !important;
                color: #212529 !important;
                font-weight: 700;
            }
        </style>

    </aside>


    <?php display_notification(); ?>