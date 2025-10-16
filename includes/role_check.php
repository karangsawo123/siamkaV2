<?php
/**
 * Role-Based Access Control
 * Usage: require_role("admin"); or require_role(["admin", "management"]);
 */

function require_role($allowed_roles) {
    if (!is_array($allowed_roles)) {
        $allowed_roles = [$allowed_roles];
    }
    
    $user_role = $_SESSION["role"] ?? null;
    
    if (!in_array($user_role, $allowed_roles)) {
        header("HTTP/1.1 403 Forbidden");
        die("Access denied. You don't have permission to access this page.");
    }
}
?>