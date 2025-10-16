<?php
/**
 * Authentication Check Middleware
 * Include this at the top of protected pages
 */

if (!defined("SECURE")) {
    define("SECURE", true);
}

require_once __DIR__ . "/../config/session.php";
require_once __DIR__ . "/../config/config.php";

if (!isset($_SESSION["user_id"])) {
    header("Location: " . BASE_URL . "modules/auth/login.php");
    exit;
}
?>