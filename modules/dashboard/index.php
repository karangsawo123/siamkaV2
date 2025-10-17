<?php
session_start();
if (!isset($_SESSION['user_id'])) {
    header("Location: ../auth/login.php");
    exit;
}
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Dashboard</title>
</head>
<body>
    <h1>Selamat Datang di Dashboard, <?= $_SESSION['username']; ?>!</h1>
    <p>Halaman ini masih dalam tahap pengembangan (Sprint berikutnya).</p>
    <a href="../auth/logout.php">Logout</a>
</body>
</html>
