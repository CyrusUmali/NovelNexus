<?php
// Start the session
session_start();

// Check if the specific session variable exists, and unset it
if (isset($_SESSION['admin'])) {
    unset($_SESSION['admin']);
}

// Optionally, redirect the user to the login page or homepage
header("Location: ../../../base.php");
exit();
?>
