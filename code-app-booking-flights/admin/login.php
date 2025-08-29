<?php include('includes/header.php');
include('includes/showMessage.php');
?>
<?php
// it's not making any sense, if he currently login, then what's the reason or any usefulness of accessing the login page?
if (isset($_SESSION['logged_in']) && $_SESSION['logged_in'] === true) {
    header('location:logout.php');
}
?>

<?php
// Start a session at the beginning of the page
// session_start();

// Check if the 'option' parameter is set and store it in a session variable
if (isset($_GET['option'])) {
    $_SESSION['option'] = $_GET['option'];
}
// else {
//     // $_SESSION['option'] = 'customer';
//     header('location:index.php');
// }
// echo $_SESSION['option'];
?>

<div class="wrapper" style="background-image: url('images/signupback.jpg');">
    <div class="inner">
        <div class="image-holder">
            <img src="images/loginfront.jpg" alt="">
        </div>
        <form action="login.php" method="POST">
            <h3>
                <?php echo $_SESSION['option']; ?> Login
            </h3>
            <div class="form-wrapper">
                <input type="text" name="email_or_username" placeholder="Email Address or Username" class="form-control"
                    required>
            </div>
            <div class="form-wrapper">
                <input type="password" name="password" placeholder="Password" class="form-control" id="password"
                    required>
                <span toggle="#password" class="password-toggle"
                    style="position: absolute; right: 10px; top: 50%; transform: translateY(-50%); cursor: pointer;">
                    👀
                </span>
            </div>

            <script src="js/tooglePass.js"> </script>
            <button type="submit" name="login">Login
                <i class="zmdi zmdi-arrow-right"></i>
            </button>
        </form>
    </div>
</div>
<?php
if (isset($_POST['login'])) {

    include('connection.php');

    // Get form data
    $email_or_username = $_POST['email_or_username'];
    $password = $_POST['password'];

    $user_type = strtolower($_SESSION['option']);

    // Check if the email or username exists in the database
    $User = "{$user_type}_name";
    // echo $User;
    $isEmail = "SELECT * FROM $user_type WHERE (email = '$email_or_username' AND BINARY pass = '$password')"; // using BINARY to make the pass column case-sensitive
    $isUsername = "SELECT * FROM $user_type WHERE ($User = '$email_or_username' AND BINARY pass = '$password')";
    $qEmail = $con->query($isEmail);
    $qUsername = $con->query($isUsername);
    if ($qEmail->num_rows == 1 or $qUsername->num_rows == 1) {
        // Start a new session or resume the existing session
        session_destroy();
        session_start();

        // Store user-related information in session variables
        $_SESSION['logged_in'] = true;
        $_SESSION['user_type'] = strtolower($user_type);
        // echo $_SESSION['user_type'];
        if ($qEmail->num_rows == 1) {
            $_SESSION['email'] = $email_or_username;

        } else {
            $q = "SELECT * FROM $user_type WHERE $User = '$email_or_username'";
            $result = $con->query($q);
            $row = $result->fetch_assoc();
            $rowValue = $row['email'];
            $_SESSION['email'] = $rowValue;
        }
        // Redirect the user to the dashboard
        header("Location: {$user_type}-dashboard.php");
    } else {
        $query = "select * from $user_type where (email = '$email_or_username' or $User = '$email_or_username')";
        $result = $con->query($query);
        if($result->num_rows > 0) {
            $messageText = "Password incorrect.";
        } else {
            $messageText = "User doesn't exist, please register";
        }
        echo '<script>var jsMessageText = "' . $messageText . '";</script>';
    }

    // Close the connection
    $con->close();
}
?>
<?php include('includes/footer.php'); ?>
       
