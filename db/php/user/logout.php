<?php
// Start the session
session_start();

// Check if the specific session variable exists, and unset it
if (isset($_SESSION['userInfo'])) {
    unset($_SESSION['userInfo']);
}

// Optionally, redirect the user to the login page or homepage
header("Location: ../../../signIn.php");
exit();
?>
