<?php
/**
 * Common Helper Functions
 */

/**
 * Sanitize output to prevent XSS
 */
function clean($data) {
    return htmlspecialchars($data, ENT_QUOTES, "UTF-8");
}

/**
 * Redirect to URL
 */
function redirect($url) {
    header("Location: " . $url);
    exit;
}

/**
 * Check if user is logged in
 */
function is_logged_in() {
    return isset($_SESSION["user_id"]);
}

/**
 * Get current user ID
 */
function get_user_id() {
    return $_SESSION["user_id"] ?? null;
}

/**
 * Get current user role
 */
function get_user_role() {
    return $_SESSION["role"] ?? null;
}

/**
 * Format date
 */
function format_date($date, $format = DATE_FORMAT) {
    return date($format, strtotime($date));
}

/**
 * Format currency (Indonesian Rupiah)
 */
function format_rupiah($number) {
    return "Rp " . number_format($number, 0, ",", ".");
}

/**
 * Generate random string
 */
function generate_random_string($length = 10) {
    return bin2hex(random_bytes($length / 2));
}

/**
 * Debug helper
 */
function dd($data) {
    echo "<pre>";
    var_dump($data);
    echo "</pre>";
    die();
}
?>