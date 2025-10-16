<?php
if (!defined("SECURE")) {
    die("Direct access not allowed");
}
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= $page_title ?? "Dashboard" ?> - <?= SITE_NAME ?></title>
    
    <!-- CSS -->
    <link rel="stylesheet" href="<?= BASE_URL ?>assets/css/style.css">
    <link rel="stylesheet" href="<?= BASE_URL ?>assets/css/dashboard.css">
    <link rel="stylesheet" href="<?= BASE_URL ?>assets/css/responsive.css">
    
    <!-- Additional CSS -->
    <?php if(isset($additional_css)): ?>
        <?php foreach($additional_css as $css): ?>
            <link rel="stylesheet" href="<?= BASE_URL . $css ?>">
        <?php endforeach; ?>
    <?php endif; ?>
</head>
<body>
    <div class="wrapper">
        <!-- Header -->
        <header class="main-header">
            <div class="header-content">
                <div class="header-left">
                    <button id="sidebar-toggle" class="sidebar-toggle">☰</button>
                    <h1 class="site-title"><?= SITE_NAME ?></h1>
                </div>
                <div class="header-right">
                    <div class="user-menu">
                        <span class="user-name"><?= clean($_SESSION["nama"] ?? "User") ?></span>
                        <span class="user-role">(<?= clean($_SESSION["role"]) ?>)</span>
                        <a href="<?= BASE_URL ?>modules/users/profile.php" class="btn-profile">Profile</a>
                        <a href="<?= BASE_URL ?>modules/auth/logout.php" class="btn-logout">Logout</a>
                    </div>
                </div>
            </div>
        </header>