<?php
if (!defined("SECURE")) {
    die("Direct access not allowed");
}

require_once __DIR__ . '/functions.php';
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= htmlspecialchars($page_title ?? "Dashboard") ?> - <?= SITE_NAME ?></title>

    <!-- Bootstrap 5 -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css">

    <!-- Font Awesome (ikon user) -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.6.0/css/all.min.css">

    <!-- Custom Dashboard CSS -->
    <link rel="stylesheet" href="<?= BASE_URL ?>assets/css/global.css">
    <link rel="stylesheet" href="<?= BASE_URL ?>assets/css/dashboard.css">
    <link rel="stylesheet" href="<?= BASE_URL ?>assets/css/assets.css">

    <link rel="stylesheet" href="<?php echo BASE_URL; ?>/assets/css/style.css">




    <!-- Tambahan CSS per halaman -->
    <?php if (isset($additional_css)): ?>
        <?php foreach ($additional_css as $css): ?>
            <link rel="stylesheet" href="<?= BASE_URL . $css ?>">
        <?php endforeach; ?>
    <?php endif; ?>
</head>
<body>
    <div class="wrapper d-flex flex-column min-vh-100">

        <!-- 🔷 TOP NAVBAR -->
        <nav class="navbar navbar-expand-lg shadow-sm fixed-top" style="background-color: #0a1a33;">
            <div class="container-fluid">

                <!-- Logo + Nama Sistem -->
                <a class="navbar-brand fw-bold text-gold d-flex align-items-center" 
                   href="<?= BASE_URL ?>modules/dashboard/<?= $_SESSION['role'] ?? 'user' ?>.php">

                    <!-- 🖼️ Logo gambar -->
                    <img src="<?= BASE_URL ?>assets/images/logo2.png" alt="Logo" 
                         style="height: 40px; width: 40px; margin-right: 8px;">

                    <!-- Nama sistem -->
                    <span><i></i><?= SITE_NAME ?></span>
                </a>

                <!-- User Info -->
                <div class="ms-auto d-flex align-items-center">
                    <span class="text-white me-3 fw-semibold">
                        <?= htmlspecialchars($_SESSION["nama"] ?? "User") ?>
                        <small class="text-warning">(<?= htmlspecialchars($_SESSION["role"] ?? "Unknown") ?>)</small>
                    </span>

                    <!-- Tombol Profil -->
                    <a href="<?= BASE_URL ?>modules/users/profile.php" class="btn btn-sm btn-outline-warning me-2">
                        <i class="fa-solid fa-user"></i> Profile
                    </a>

                    <!-- Tombol Logout -->
                    <a href="<?= BASE_URL ?>modules/auth/logout.php" class="btn btn-sm btn-outline-danger">
                        <i class="fa-solid fa-right-from-bracket"></i> Logout
                    </a>
                </div>
            </div>
        </nav>

        <!-- Spacer agar konten tidak ketutup navbar fixed-top -->
        <div style="height: 70px;"></div>

        <!-- Tambahan CSS Kecil -->
        <style>
            .text-gold { color: #f4c542 !important; }
            .btn-outline-warning:hover {
                background-color: #f4c542;
                color: #0a1a33;
            }
            .btn-outline-danger:hover {
                background-color: #dc3545;
                color: #fff;
            }
            .navbar-brand img {
                border-radius: 50%;
                object-fit: cover;
            }
        </style>
