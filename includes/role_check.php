<?php
if ($_SESSION['role'] !== 'admin') {
    echo "<h3>Akses ditolak</h3>";
    exit;
}
?>
