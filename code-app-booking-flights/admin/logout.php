<?php
// Start or resume the session
session_start();

// Check if the user is logged in
if (isset($_SESSION['logged_in']) && $_SESSION['logged_in'] === true) {
    // Unset all session variables
    $_SESSION = array();

    // Destroy the session
    session_destroy();

    // Redirect the user to the login page or any other desired page
    header('Location: index.php');
    exit();
} else {
    header('location:index.php');
}
?>