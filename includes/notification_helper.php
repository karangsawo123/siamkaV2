<?php
/**
 * Notification/Flash Message Helper
 */

/**
 * Set notification
 */
function set_notification($type, $message) {
    $_SESSION["notification"] = [
        "type" => $type,  // success, error, warning, info
        "message" => $message
    ];
}

/**
 * Get and clear notification
 */
function get_notification() {
    if (isset($_SESSION["notification"])) {
        $notification = $_SESSION["notification"];
        unset($_SESSION["notification"]);
        return $notification;
    }
    return null;
}

/**
 * Display notification HTML
 */
function display_notification() {
    $notif = get_notification();
    if ($notif) {
        $type = $notif["type"];
        $message = clean($notif["message"]);
        
        $class_map = [
            "success" => "alert-success",
            "error" => "alert-danger",
            "warning" => "alert-warning",
            "info" => "alert-info"
        ];
        
        $class = $class_map[$type] ?? "alert-info";
        
        echo "<div class='alert {$class} alert-dismissible fade show' role='alert'>";
        echo $message;
        echo "<button type='button' class='btn-close' data-bs-dismiss='alert'></button>";
        echo "</div>";
    }
}
?>