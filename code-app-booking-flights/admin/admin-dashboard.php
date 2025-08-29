<?php
session_start();
if(!isset($_SESSION['user_type'])) {
    header('location: login.php');
}
require_once('includes/showMessage.php');
require 'includes/functions.php';
displaySessionMessage();
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Sidebar Menu for Admin Dashboard</title>
    <link rel="stylesheet" href="css/style.css" />
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.2.1/css/all.min.css" />
</head>
<body>
<?php include('includes/admin-nav.php') ?>;
    <main class="main">
        <div class="welcome-text" >
            <h1>Welcome Admin </h1>
            <p>This is our admin dashboard where we can manage airline companies, airports, flight details, and more. <br>Use the sidebar menu to navigate through the different sections.</p>
        </div>
        <!-- You can add more content and instructions here -->
    </main>

    <script src="js/script.js"></script>
</body>
</html>
