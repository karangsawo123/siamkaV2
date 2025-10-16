<?php
if (!defined("SECURE")) {
    die("Direct access not allowed");
}

$current_page = basename($_SERVER["PHP_SELF"]);
$user_role = $_SESSION["role"] ?? "user";
?>
        <!-- Sidebar -->
        <aside class="main-sidebar">
            <nav class="sidebar-nav">
                <ul class="nav-list">
                    <!-- Dashboard -->
                    <li class="nav-item">
                        <a href="<?= BASE_URL ?>modules/dashboard/<?= $user_role ?>.php" 
                           class="nav-link <?= $current_page == $user_role . ".php" ? "active" : "" ?>">
                            <span class="icon">📊</span>
                            <span class="text">Dashboard</span>
                        </a>
                    </li>
                    
                    <?php if($user_role == "admin"): ?>
                        <!-- Admin Menu -->
                        <li class="nav-item">
                            <a href="<?= BASE_URL ?>modules/users/index.php" 
                               class="nav-link <?= strpos($current_page, "users") !== false ? "active" : "" ?>">
                                <span class="icon">👥</span>
                                <span class="text">Kelola User</span>
                            </a>
                        </li>
                        <li class="nav-item">
                            <a href="<?= BASE_URL ?>modules/categories/index.php" 
                               class="nav-link <?= strpos($current_page, "categories") !== false ? "active" : "" ?>">
                                <span class="icon">🏷️</span>
                                <span class="text">Kategori</span>
                            </a>
                        </li>
                        <li class="nav-item">
                            <a href="<?= BASE_URL ?>modules/assets/index.php" 
                               class="nav-link <?= strpos($current_page, "assets") !== false ? "active" : "" ?>">
                                <span class="icon">📦</span>
                                <span class="text">Kelola Aset</span>
                            </a>
                        </li>
                        <li class="nav-item">
                            <a href="<?= BASE_URL ?>modules/loans/admin_loans.php" 
                               class="nav-link <?= strpos($current_page, "loans") !== false ? "active" : "" ?>">
                                <span class="icon">📋</span>
                                <span class="text">Kelola Peminjaman</span>
                            </a>
                        </li>
                        <li class="nav-item">
                            <a href="<?= BASE_URL ?>modules/damage/index.php" 
                               class="nav-link <?= strpos($current_page, "damage") !== false ? "active" : "" ?>">
                                <span class="icon">🔧</span>
                                <span class="text">Laporan Kerusakan</span>
                            </a>
                        </li>
                        <li class="nav-item">
                            <a href="<?= BASE_URL ?>modules/maintenance/schedule.php" 
                               class="nav-link <?= strpos($current_page, "maintenance") !== false ? "active" : "" ?>">
                                <span class="icon">🛠️</span>
                                <span class="text">Maintenance</span>
                            </a>
                        </li>
                        <li class="nav-item">
                            <a href="<?= BASE_URL ?>modules/reports/summary.php" 
                               class="nav-link <?= strpos($current_page, "reports") !== false ? "active" : "" ?>">
                                <span class="icon">📊</span>
                                <span class="text">Laporan</span>
                            </a>
                        </li>
                    
                    <?php elseif($user_role == "user"): ?>
                        <!-- User Menu -->
                        <li class="nav-item">
                            <a href="<?= BASE_URL ?>modules/loans/available.php" 
                               class="nav-link">
                                <span class="icon">📦</span>
                                <span class="text">Aset Tersedia</span>
                            </a>
                        </li>
                        <li class="nav-item">
                            <a href="<?= BASE_URL ?>modules/loans/my_loans.php" 
                               class="nav-link">
                                <span class="icon">📋</span>
                                <span class="text">Peminjaman Saya</span>
                            </a>
                        </li>
                        <li class="nav-item">
                            <a href="<?= BASE_URL ?>modules/damage/my_reports.php" 
                               class="nav-link">
                                <span class="icon">🔧</span>
                                <span class="text">Laporan Saya</span>
                            </a>
                        </li>
                    
                    <?php elseif($user_role == "management"): ?>
                        <!-- Management Menu -->
                        <li class="nav-item">
                            <a href="<?= BASE_URL ?>modules/assets/index.php" 
                               class="nav-link">
                                <span class="icon">📦</span>
                                <span class="text">Data Aset</span>
                            </a>
                        </li>
                        <li class="nav-item">
                            <a href="<?= BASE_URL ?>modules/loans/admin_loans.php" 
                               class="nav-link">
                                <span class="icon">📋</span>
                                <span class="text">Data Peminjaman</span>
                            </a>
                        </li>
                        <li class="nav-item">
                            <a href="<?= BASE_URL ?>modules/reports/summary.php" 
                               class="nav-link">
                                <span class="icon">📊</span>
                                <span class="text">Laporan & Statistik</span>
                            </a>
                        </li>
                    <?php endif; ?>
                    
                    <!-- Common Menu -->
                    <li class="nav-item">
                        <a href="<?= BASE_URL ?>modules/users/profile.php" 
                           class="nav-link">
                            <span class="icon">👤</span>
                            <span class="text">Profil Saya</span>
                        </a>
                    </li>
                </ul>
            </nav>
        </aside>
        
        <!-- Main Content -->
        <main class="main-content">
            <div class="content-wrapper">
                <?php display_notification(); ?>