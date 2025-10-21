<?php
define("SECURE", true);
if (session_status() === PHP_SESSION_NONE) session_start();

include '../../includes/auth_check.php';
include '../../includes/role_check.php';
include '../../config/config.php';
include '../../config/database.php';
include '../../includes/notification_helper.php';

checkRole(['admin']);

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $id_user = $_POST['id_user'];
    $password = password_hash($_POST['password'], PASSWORD_DEFAULT);

    if (mysqli_query($conn, "UPDATE users SET password='$password' WHERE id_user='$id_user'")) {
        set_notification('success', '✅ Password berhasil diubah.');
    } else {
        set_notification('error', '❌ Gagal mengubah password.');
    }

    header('Location: index.php');
    exit;
}
?>
