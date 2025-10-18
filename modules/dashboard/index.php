<?php
include '../../includes/role_check.php';

checkRole(['admin'])

?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Dashboard</title>
</head>
<body>
    <h1>Selamat Datang, <?= htmlspecialchars($_SESSION['nama']); ?> (<?= htmlspecialchars($_SESSION['role']); ?>)!</h1>
    <p>Halaman ini masih dalam tahap pengembangan (Sprint berikutnya).</p>
    <a href="../auth/logout.php">Logout</a>
</body>
</html>
