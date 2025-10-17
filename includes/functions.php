<?php
function redirect($url) {
    header("Location: " . $url);
    exit;
}

function alert($message, $type = 'success') {
    echo "<div class='alert alert-{$type}'>{$message}</div>";
}
?>
