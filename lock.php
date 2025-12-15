<?php
session_start();
// Remove the unlock flag
unset($_SESSION['admin_unlocked']);
// Redirect to home
header("Location: index.php");
exit;
?>
