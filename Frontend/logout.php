<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

session_start();
session_unset();    // Clear all session variables
session_destroy();  // Destroy the session
header("Location: login.php");
exit;
?>
