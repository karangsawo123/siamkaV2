<?php
if (!defined("SECURE")) {
    die("Direct access not allowed");
}

require_once __DIR__ . '/functions.php';
require_once __DIR__ . '/notification_helper.php';

// Fallback jika helper tidak ditemukan
if (!function_exists('display_notification')) {
    function display_notification($type = 'info', $message = '') {
        if (empty($message)) return;
        echo "<div class='alert alert-{$type} mt-2'>{$message}</div>";
    }
}

$current_page = basename($_SERVER["PHP_SELF"]);
$user_role = $_SESSION["role"] ?? "user";
?>

<!-- ====== LAYOUT WRAPPER ====== -->
<div class="d-flex">

    <!-- ====== SIDEBAR ====== -->
    <aside class="sidebar bg-dark text-white shadow-sm d-flex flex-column"
           style="width:250px; position:fixed; top:56px; bottom:0; left:0; overflow-y:auto;">

        <div class="sidebar-header text-center border-bottom border-secondary py-3 mb-3">
            <h5 class="fw-bold mb-0"><?= strtoupper($user_role) ?></h5>
            <small class="text-muted">SIAMKA Panel</small>
        </div>

        <nav class="nav flex-column px-2">
            <!-- Dashboard -->
            <a href="<?= BASE_URL ?>modules/dashboard/<?= $user_role ?>.php"
               class="nav-link text-white py-2 px-3 rounded mb-1 <?= $current_page == $user_role . '.php' ? 'active bg-primary' : '' ?>">
                <i class="fa-solid fa-gauge me-2"></i> Dashboard
            </a>

            <?php if ($user_role == "admin"): ?>
                <a href="<?= BASE_URL ?>modules/users/index.php"
                   class="nav-link text-white py-2 px-3 rounded mb-1 <?= strpos($current_page, 'users') !== false ? 'active bg-primary' : '' ?>">
                    <i class="fa-solid fa-users me-2"></i> Kelola User
                </a>
                <a href="<?= BASE_URL ?>modules/categories/index.php"
                   class="nav-link text-white py-2 px-3 rounded mb-1 <?= strpos($current_page, 'categories') !== false ? 'active bg-primary' : '' ?>">
                    <i class="fa-solid fa-tags me-2"></i> Kategori
                </a>
                <a href="<?= BASE_URL ?>modules/assets/index.php"
                   class="nav-link text-white py-2 px-3 rounded mb-1 <?= strpos($current_page, 'assets') !== false ? 'active bg-primary' : '' ?>">
                    <i class="fa-solid fa-box me-2"></i> Kelola Aset
                </a>
                <a href="<?= BASE_URL ?>modules/loans/admin_loans.php"
                   class="nav-link text-white py-2 px-3 rounded mb-1 <?= strpos($current_page, 'loans') !== false ? 'active bg-primary' : '' ?>">
                    <i class="fa-solid fa-clipboard-list me-2"></i> Kelola Peminjaman
                </a>
                <a href="<?= BASE_URL ?>modules/damage/index.php"
                   class="nav-link text-white py-2 px-3 rounded mb-1 <?= strpos($current_page, 'damage') !== false ? 'active bg-primary' : '' ?>">
                    <i class="fa-solid fa-wrench me-2"></i> Laporan Kerusakan
                </a>
                <a href="<?= BASE_URL ?>modules/maintenance/schedule.php"
                   class="nav-link text-white py-2 px-3 rounded mb-1 <?= strpos($current_page, 'maintenance') !== false ? 'active bg-primary' : '' ?>">
                    <i class="fa-solid fa-screwdriver-wrench me-2"></i> Maintenance
                </a>
                <a href="<?= BASE_URL ?>modules/reports/summary.php"
                   class="nav-link text-white py-2 px-3 rounded mb-1 <?= strpos($current_page, 'reports') !== false ? 'active bg-primary' : '' ?>">
                    <i class="fa-solid fa-chart-column me-2"></i> Laporan
                </a>

            <?php elseif ($user_role == "pengguna"): ?>
                <a href="<?= BASE_URL ?>modules/loans/available.php" class="nav-link text-white py-2 px-3 rounded mb-1">
                    <i class="fa-solid fa-box-open me-2"></i> Aset Tersedia
                </a>
                <a href="<?= BASE_URL ?>modules/loans/my_loans.php" class="nav-link text-white py-2 px-3 rounded mb-1">
                    <i class="fa-solid fa-clipboard-check me-2"></i> Peminjaman Saya
                </a>
                <a href="<?= BASE_URL ?>modules/damage/my_reports.php" class="nav-link text-white py-2 px-3 rounded mb-1">
                    <i class="fa-solid fa-tools me-2"></i> Laporan Saya
                </a>

            <?php elseif ($user_role == "manajemen"): ?>
                <a href="<?= BASE_URL ?>modules/assets/index.php" class="nav-link text-white py-2 px-3 rounded mb-1">
                    <i class="fa-solid fa-boxes-stacked me-2"></i> Data Aset
                </a>
                <a href="<?= BASE_URL ?>modules/loans/admin_loans.php" class="nav-link text-white py-2 px-3 rounded mb-1">
                    <i class="fa-solid fa-clipboard-list me-2"></i> Data Peminjaman
                </a>
                <a href="<?= BASE_URL ?>modules/reports/summary.php" class="nav-link text-white py-2 px-3 rounded mb-1">
                    <i class="fa-solid fa-chart-pie me-2"></i> Laporan & Statistik
                </a>
            <?php endif; ?>

            <div class="mt-3 border-top border-secondary pt-3">
                <a href="<?= BASE_URL ?>modules/users/profile.php" class="nav-link text-white py-2 px-3 rounded mb-1">
                    <i class="fa-solid fa-user me-2"></i> Profil Saya
                </a>
            </div>
        </nav>
    </aside>


    <?php display_notification(); ?>
