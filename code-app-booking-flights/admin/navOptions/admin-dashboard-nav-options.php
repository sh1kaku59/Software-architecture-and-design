<?php
$user_type = $_SESSION['user_type'];
// Define an array to store the navigation options
$navOptions = array(
    "Home" => "index.php",
    // "About Us" => "aboutUs.php",
    // "Sign up" => array(
    //     "Customer" => "signup.php",
    //     // "Airline" => "#"
    // ),
    "Dashboard" => "{$user_type}-dashboard.php",
    "Settings" => array(
        "Change Password" => "change-password.php",
        "Log out" => "logout.php",
        // "Customer" => "login.php",
        // "Airline" => "login.php",
        // "Admin" => "login.php"
    )

);
?>