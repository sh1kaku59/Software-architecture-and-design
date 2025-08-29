<?php
session_start();
include 'connect.php';

// Hàm lấy thông tin người dùng từ email
function getUserInfo($conn, $email) {
    $stmt = $conn->prepare("SELECT * FROM customer WHERE email = ?");
    $stmt->bind_param("s", $email);
    $stmt->execute();
    $result = $stmt->get_result();
    return $result->fetch_assoc();
}

// Hàm cập nhật thông tin người dùng
function updateUserInfo($conn, $current_email, $new_email, $first_name, $last_name, $phone, $gender, $customer_name) {
    $stmt = $conn->prepare("UPDATE customer SET email = ?, first_name = ?, last_name = ?, phone = ?, gender = ?, customer_name = ? WHERE email = ?");
    $stmt->bind_param("sssssss", $new_email, $first_name, $last_name, $phone, $gender, $customer_name, $current_email);
    return $stmt->execute();
}

// Hàm cập nhật mật khẩu người dùng
function updatePassword($conn, $email, $new_password) {
    $stmt = $conn->prepare("UPDATE customer SET pass = ? WHERE email = ?");
    $stmt->bind_param("ss", $new_password, $email);
    return $stmt->execute();
}

// Kiểm tra xem người dùng đã đăng nhập hay chưa
if (!isset($_SESSION['loggedIn']) || $_SESSION['loggedIn'] !== true) {
    // Nếu chưa đăng nhập, chuyển hướng đến trang đăng nhập
    header("Location: login.php");
    exit();
}


$email = $_SESSION['user_info_query']['email'];

// Xử lý khi người dùng gửi form
if ($_SERVER["REQUEST_METHOD"] === "POST") {
    if (isset($_POST['old_password'])) {
        $userInfo = getUserInfo($conn, $email);
        $old_password = $_POST['old_password'];
        $new_password = $_POST['new_password'];
        $confirm_password = $_POST['confirm_password'];

        if ($old_password === $userInfo['pass']) {
            if ($new_password === $confirm_password) {
                if (updatePassword($conn, $email, $new_password)) {
                    echo "Updated password successfully!";
                } else {
                    echo "Error updating password: " . $conn->error;
                }
            } else {
                echo "New password and confirm password don't match";
            }
        } else {
            echo "Old password incorrect";
        }
    } else {
        $new_email = $_POST['email'];
        $first_name = $_POST['first_name'];
        $last_name = $_POST['last_name'];
        $phone = $_POST['phone'];
        $gender = $_POST['gender'];
        $customer_name = $_POST['customer_name'];

        // Kiểm tra nếu email mới khác với email hiện tại
        if ($new_email !== $email) {
            if (updateUserInfo($conn, $email, $new_email, $first_name, $last_name, $phone, $gender, $customer_name)) {
                echo "Updated email and information successfully";
                $_SESSION['user_info_query']['email'] = $new_email;
            } else {
                echo "Error when updated email and information: " . $conn->error;
            }
        } else {
            if (updateUserInfo($conn, $email, $new_email, $first_name, $last_name, $phone, $gender, $customer_name)) {
                echo "Updated information successfully";
            } else {        
                echo "Updated information error: " . $conn->error;
            }
        }

        // Cập nhật biến phiên với thông tin mới
        $_SESSION['user_info_query']['first_name'] = $first_name;
        $_SESSION['user_info_query']['last_name'] = $last_name;
        $_SESSION['user_info_query']['phone'] = $phone;
        $_SESSION['user_info_query']['gender'] = $gender;
        $_SESSION['user_info_query']['customer_name'] = $customer_name;
    }

    // Chuyển hướng và thêm tham số vào URL để xác định thông báo
    header("Location: changeinformation.php?message=success");
    exit();
}

// Lấy thông tin người dùng từ cơ sở dữ liệu
$userInfo = getUserInfo($conn, $email);

// Kiểm tra nếu thông tin người dùng có sẵn
if ($userInfo) {
    $customer_name = $userInfo['customer_name'];
    $first_name = $userInfo['first_name'];
    $last_name = $userInfo['last_name'];
    $phone = $userInfo['phone'];
    $gender = $userInfo['gender'];
}

$conn->close();
?>




<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <?php include 'header.php'; ?>
    <title>Update Information and Password</title>
    <link href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css">
    <style>
        body {
            font-family: 'Montserrat', sans-serif;
        }
        h2 {
            color: #333;
            margin-top: 30px;
            margin-bottom: 20px;
        }
        /* Additional custom styling */
        body {
            background-color: #f8f9fa;
        }
        .container {
            margin-top: 50px;
            margin-bottom: 80px;
        }
        .form-container {
            background-color: #fff;
            padding: 30px;
            border-radius: 8px;
            box-shadow: 0px 0px 10px 0px rgba(0,0,0,0.1);
        }
        .form-group {
            margin-bottom: 20px;
        }
        .btn-primary {
            transition: all 0.3s ease;
        }
        .btn-primary:hover {
            transform: translateY(-2px);
            box-shadow: 0px 4px 10px rgba(0, 0, 0, 0.1);
        }
        .form-group label i {
            margin-right: 5px;
            margin-left: 5px;
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="row">
            <div class="col-md-6">
                <div class="form-container">
                    <h2>Update Information <i class="fas fa-user-edit"></i></h2>
                    <form method="post" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]);?>">
                        <div class="form-group">
                            <label for="email"><i class="fas fa-envelope"></i> Email:</label>
                            <input type="email" class="form-control" id="email" name="email" value="<?php echo $email; ?>">
                        </div>
                        <div class="form-group">
                            <label for="customer_name"><i class="fas fa-user"></i> Username:</label>
                            <input type="text" class="form-control" id="customer_name" name="customer_name" value="<?php echo $customer_name; ?>">
                        </div>
                        <div class="form-group">
                            <label for="first_name"><i class="fas fa-user"></i> First Name:</label>
                            <input type="text" class="form-control" id="first_name" name="first_name" value="<?php echo $first_name; ?>">
                        </div>
                        <div class="form-group">
                            <label for="last_name"><i class="fas fa-user"></i> Last Name:</label>
                            <input type="text" class="form-control" id="last_name" name="last_name" value="<?php echo $last_name; ?>">
                        </div>
                        <div class="form-group">
                            <label for="phone"><i class="fas fa-phone"></i> Phone Number:</label>
                            <input type="tel" class="form-control" id="phone" name="phone" value="<?php echo $phone; ?>">
                        </div>
                        <div class="form-group">
                            <label for="gender"><i class="fas fa-venus-mars"></i> Gender:</label>
                            <select class="form-control" id="gender" name="gender">
                                <option value="male" <?php if($gender == 'male') echo 'selected'; ?>>Male</option>
                                <option value="female" <?php if($gender == 'female') echo 'selected'; ?>>Female</option>
                                <option value="other" <?php if($gender == 'other') echo 'selected'; ?>>Other</option>
                            </select>
                        </div>
                        <button type="submit" class="btn btn-primary"><i class="fas fa-save"></i> Update</button>
                    </form>
                </div>
            </div>
            <div class="col-md-6">
                <div class="form-container">
                    <h2>Change Password <i class="fas fa-lock"></i></h2>
                    <form method="post" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]);?>">
                        <input type="hidden" name="email" value="<?php echo $email; ?>"> 
                        <div class="form-group">
                            <label for="old_password"><i class="fas fa-key"></i> Current Password:</label>
                            <input type="password" class="form-control" id="old_password" name="old_password">
                        </div>
                        <div class="form-group">
                            <label for="new_password"><i class="fas fa-key"></i> New Password:</label>
                            <input type="password" class="form-control" id="new_password" name="new_password">
                        </div>
                        <div class="form-group">
                            <label for="confirm_password"><i class="fas fa-key"></i> Confirm New Password:</label>
                            <input type="password" class="form-control" id="confirm_password" name="confirm_password">
                        </div>
                        <button type="submit" class="btn btn-primary"><i class="fas fa-sync-alt"></i> Update</button>
                    </form>
                </div>
            </div>
        </div>
    </div>
    <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.5.4/dist/umd/popper.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
    <script>
    // Check if there are parameters in the URL and display corresponding messages
    document.addEventListener("DOMContentLoaded", function() {
        const urlParams = new URLSearchParams(window.location.search);
        const message = urlParams.get('message');
        
        if (message === 'success') {
            alert("Update successful!");
        } else if (message === 'error') {
            alert("An error occurred while updating information!");
        }
    });
    </script>

<?php include 'footer.php'; ?>

</body>
</html>
