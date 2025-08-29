<?php
if(session_status() !== PHP_SESSION_ACTIVE) {
    session_start();
}



// If the user is logged in, assign $customer_name based on session information
if(isset($_SESSION['user_info_query']) && isset($_SESSION['user_info_query']['customer_name'])) {
    $customer_name = $_SESSION['user_info_query']['customer_name'];
} else {
    // If no data is available, assign a link to the login page
    $customer_name = '<a href="login.php">SignUp / LogIn</a>';
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Booking Flight</title>
    <!-- Bootstrap CSS -->
    <link href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css" rel="stylesheet">
    <!-- Font Awesome CSS -->
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.3/css/all.min.css" rel="stylesheet">
    <!-- Google Fonts -->
    <link href="https://fonts.googleapis.com/css2?family=Montserrat:wght@400;700&display=swap" rel="stylesheet">
    <style>
        body {
            font-family: 'Montserrat', sans-serif;
            background-color: rgba(255, 255, 255, 0.3);
        }
        .unique-navbar {
            background-color: rgba(255, 255, 255, 0.5);
            box-shadow: 0 2px 4px rgba(0,0,0,0.1); 
        }
        .unique-navbar-brand {
            font-weight: bold;
            font-size: 24px;
            color: #333; 
            transition: color 0.3s; 
        }
        .unique-navbar-brand:hover {
            color: #ff7700; 
        }
        .unique-nav-link {
            color: #555; 
            transition: color 0.3s; 
        }
        .unique-nav-link:hover {
            color: #ff7700; 
        }
        .unique-user-info {
            color: #555; 
            padding: 10px;
            border-radius: 20px;
            background-color: #f8f9fa;
            display: flex;
            align-items: center; 
        }
        .unique-user-info a {
            color: #333;
            text-decoration: none;
            margin-right: 10px;
        }
        .unique-user-info a:hover {
            color: #ff7700;
        }
        .unique-user-info-highlighted {
            background-color: #ff7700; 
            color: #fff;
            padding: 8px 12px;
            border-radius: 20px;
        }
        .unique-user-info-highlighted a {
            color: #fff;
        }
        .unique-user-info-highlighted a:hover {
            text-decoration: underline;
        }
        .menu-custom {
            display: none;
            position: absolute;
            background-color: #fff;
            border: 1px solid #ccc;
            padding: 10px;
            box-shadow: 0 2px 4px rgba(0,0,0,0.1);
            z-index: 1000;
            list-style: none;
            margin: 0;
            top: calc(100% + 5px);
            right: 0;
        }
        .menu-custom li {
            margin-bottom: 5px;
        }
        .menu-custom li a {
            color: #333;
            text-decoration: none;
            transition: color 0.3s;
            display: block;
            padding: 5px 10px;
        }
        .menu-custom li a:hover {
            color: #ff7700;
            background-color: #f8f9fa;
        }
    </style>
</head>
<body>
<nav class="navbar navbar-expand-lg unique-navbar">
    <a class="navbar-brand unique-navbar-brand" href="index.php">Booking Flight</a>
    <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#uniqueNavbarNav" aria-controls="uniqueNavbarNav" aria-expanded="false" aria-label="Toggle navigation">
        <span class="navbar-toggler-icon"></span>
    </button>
    <div class="collapse navbar-collapse" id="uniqueNavbarNav">
        <ul class="navbar-nav ml-auto">
            <li class="nav-item active">
                <a class="nav-link unique-nav-link" href="index.php">Homepage <span class="sr-only">(current)</span></a>
            </li>
            <li class="nav-item">
                <a class="nav-link unique-nav-link" href="about.php">About Us</a>
            </li>
            <li class="nav-item">
                <a class="nav-link unique-nav-link" href="contact.php">Contact</a>
            </li>

            <!-- Custom user menu -->
            <li class="nav-item">
                <span class="unique-user-info unique-user-info-highlighted"><?php echo $customer_name; ?></span>
                <ul class="menu-custom">
                    <?php if(isset($_SESSION['user_info_query']) && isset($_SESSION['user_info_query']['customer_name'])): ?>
                        <li><a href="changeinformation.php">Account</a></li>
                        <li><a href="Airlinebookinghistory.php">Booked History</a></li>
                        <li><a href="logout.php">Log Out</a></li>
                    <?php endif; ?>
                </ul>
            </li>
        </ul>
    </div>
</nav>
<!-- Bootstrap JS -->
<script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.5.4/dist/umd/popper.min.js"></script>
<script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>

<script>
    document.addEventListener("DOMContentLoaded", function() {
        var customerName = document.querySelector(".unique-user-info");
        var menu = document.querySelector(".menu-custom");

        // Event handling when clicking on the customer name
        customerName.addEventListener("click", function() {
            // If the menu is currently displayed, hide it. Otherwise, display it.
            if (menu.style.display === "block") {
                menu.style.display = "none";
            } else {
                menu.style.display = "block";
            }
        });

        // Hide the menu when clicking anywhere on the page
        document.addEventListener("click", function(event) {
            var isClickInside = customerName.contains(event.target) || menu.contains(event.target);
            if (!isClickInside) {
                menu.style.display = "none";
            }
        });
    });
</script>
</body>
</html>

