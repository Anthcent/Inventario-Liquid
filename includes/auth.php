<?php
session_start();

// Simple Check
function require_pin() {
    if (!isset($_SESSION['admin_unlocked']) || $_SESSION['admin_unlocked'] !== true) {
        $current_url = urlencode($_SERVER['REQUEST_URI']);
        header("Location: pin_access.php?redirect=$current_url");
        exit;
    }
}
?>
