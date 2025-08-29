<?php
session_start();
include 'connect.php';

// Hàm kiểm tra xem email hoặc số điện thoại đã tồn tại trong bảng khách hàng
function checkCustomerExistence($emailOrPhone) {
    global $conn;
    $sql = "SELECT * FROM customer WHERE email = '$emailOrPhone' OR phone = '$emailOrPhone'";
    $result = $conn->query($sql);
    return $result->num_rows > 0;
}

// Hàm đăng ký một khách hàng mới
function registerCustomer($firstName, $lastName, $customername, $email, $phone, $password) {
    global $conn; // Sử dụng biến $conn ở phạm vi toàn cục
    $sql = "INSERT INTO customer (first_name, last_name, customer_name, email, phone, pass) VALUES ('$firstName', '$lastName', '$customername', '$email', '$phone', '$password')";
    
    if (!$conn->query($sql)) {
        echo "Error when sign up: " . $conn->error;
        return false;
    }
    
    return true;
}


// Hàm xử lý việc gửi form đăng nhập
function handleLoginFormSubmission($emailOrPhone) {
    global $showMainForm, $showLoginForm, $showRegisterForm, $autoFillEmail, $autoFillPhone;
    if (checkCustomerExistence($emailOrPhone)) {
        $_SESSION['emailOrPhone'] = $emailOrPhone; // Lưu giá trị vào biến session
        $showMainForm = false;
        $showLoginForm = true;
    } else {
        $autoFillEmail = filter_var($emailOrPhone, FILTER_VALIDATE_EMAIL) ? $emailOrPhone : "";
        $autoFillPhone = !$autoFillEmail ? $emailOrPhone : "";
        $showMainForm = false;
        $showRegisterForm = true;
    }
}

// Hàm xử lý việc gửi form đăng ký
function handleRegisterFormSubmission($firstName, $lastName, $customername, $email, $phone, $password) {
    global $showMainForm, $showLoginForm;
    if (registerCustomer($firstName, $lastName, $customername, $email, $phone, $password)) {
        $showMainForm = true;
        $showLoginForm = false;
    } else {
        echo "Error when register";
    }
}

// Khởi tạo biến
$showMainForm = true;
$showLoginForm = false;
$showRegisterForm = false;
$autoFillEmail = "";
$autoFillPhone = "";

// Kiểm tra nếu form đăng nhập đã được gửi
if(isset($_POST['loginEmail'])) {
    handleLoginFormSubmission($_POST['loginEmail']);
}

// Kiểm tra nếu form đăng ký đã được gửi
if(isset($_POST['registerEmail'])) {
    handleRegisterFormSubmission($_POST['registerFirstName'], $_POST['registerLastName'], $_POST['registerCustomerName'], $_POST['registerEmail'], $_POST['registerPhone'], $_POST['registerPassword']);

}


function checklogin($loginEmailOrPhone, $loginPassword, $conn) {
    // Thực hiện truy vấn SQL
    $sql = "SELECT * FROM customer WHERE (email = '$loginEmailOrPhone' OR phone = '$loginEmailOrPhone') AND pass = '$loginPassword'";
    $result = $conn->query($sql);

    // Kiểm tra kết quả truy vấn
    if ($result->num_rows > 0) {
        $_SESSION['loggedIn'] = true; // Thiết lập biến session 'loggedIn'
        return true;
    } else {
        // Đăng nhập không thành công
        return false;
    }
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    if (isset($_POST['loginEmailOrPhone']) && isset($_POST['loginPassword'])) {
        // Lấy giá trị từ form
        $loginEmailOrPhone = $_POST['loginEmailOrPhone'];
        $loginPassword = $_POST['loginPassword'];


        $isLoggedIn = checklogin($loginEmailOrPhone, $loginPassword, $conn);

        if ($isLoggedIn) {
            $sql_user_info = "SELECT * FROM customer WHERE email = '$loginEmailOrPhone' OR phone = '$loginEmailOrPhone'";
            $result = $conn->query($sql_user_info);
            $_SESSION['user_info_query'] = $result->fetch_assoc();
            header("Location: index.php");
            exit();
        } else {
            echo "User's email or phone number or password incorrect";
        }
    }
}

?>


<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Sign Up / Log in</title>
    <!-- Bootstrap CSS -->
    <link href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body {
            font-family: 'Montserrat', sans-serif;
            background-image: url('images/logsign.jpeg'); 
            background-size: cover;
            background-repeat: no-repeat;
            background-attachment: fixed;
        }

        .form-container {
            max-width: 400px;
            margin: 50px auto;
            background-color: rgba(255, 255, 255, 0.8); 
            padding: 30px;
            border-radius: 10px;
            box-shadow: 0 0 20px rgba(0, 0, 0, 0.1);
        }

        .form-container h2 {
            margin-bottom: 30px;
            text-align: center;
            color: #007bff;
        }

        .form-container form {
            margin-bottom: 20px;
        }

        .form-container label {
            font-weight: bold;
            color: #555;
        }

        .form-container input {
            width: 100%;
            padding: 10px;
            margin-bottom: 20px;
            border: 1px solid #ccc;
            border-radius: 5px;
            box-sizing: border-box;
        }

        .form-container button {
            width: 100%;
            padding: 12px;
            background-color: #007bff;
            color: #fff;
            border: none;
            border-radius: 5px;
            cursor: pointer;
            transition: background-color 0.3s;
        }

        .form-container button:hover {
            background-color: #0056b3;
        }

        .homepage-btn {
            text-align: center;
            margin-top: 20px;
        }

        .homepage-btn a {
            border: orange;
            display: inline-block;
            padding: 12px 24px;
            background-color: #ff7700;
            color: #fff;
            text-decoration: none;
            border-radius: 6px;
            transition: background-color 0.3s;
        }

        .homepage-btn a:hover {
            background-color: #ff9800;
        }
    </style>
</head>
<body>
    <div class="form-container">
        <!-- Form Chính -->
        <form id="MainForm" method="post" onsubmit="return validateInput()" <?php if(!$showMainForm) echo 'style="display: none;"'; ?>>
            <h2>Sign Up / Log In</h2>
            <label for="loginEmail">Email or Phone number:</label>
            <input type="text" id="loginEmail" name="loginEmail" required>
            <button type="submit">Continue</button>
        </form>

        
        <!-- Form đăng nhập -->
        <form id="loginForm" method="post" <?php if(!$showLoginForm) echo 'style="display: none;"'; ?>>
            <h2>Log In</h2>
            <input type="hidden" name="loginEmailOrPhone" value="<?php echo isset($_SESSION['emailOrPhone']) ? $_SESSION['emailOrPhone'] : ''; ?>">
            <label for="loginPassword">Password:</label>
            <input type="password" id="loginPassword" name="loginPassword" required>
            <button type="submit">Log In</button>
        </form>

        
        <!-- Form đăng ký -->
        <form id="registerForm" method="post" <?php if(!$showRegisterForm) echo 'style="display: none;"'; ?>>
            <h2>Sign Up</h2>
            <label for="registerFirstName">First Name:</label>
            <input type="text" id="registerFirstName" name="registerFirstName" required>
            <label for="registerLastName">Last Name:</label>
            <input type="text" id="registerLastName" name="registerLastName" required>

            <label for="registerCustomerName">Customer Name:</label>
            <input type="text" id="registerCustomerName" name="registerCustomerName" required>

            <label for="registerEmail">Email:</label>
            <input type="email" id="registerEmail" name="registerEmail" value="<?php echo $autoFillEmail; ?>" <?php if(!empty($autoFillEmail)) echo 'readonly'; ?> required>
            <label for="registerPhone">Phone Number:</label>
            <input type="tel" id="registerPhone" name="registerPhone" value="<?php echo $autoFillPhone; ?>" <?php if(!empty($autoFillPhone)) echo 'readonly'; ?> required>
            <label for="registerPassword">Password:</label>
            <input type="password" id="registerPassword" name="registerPassword" required>
            <button type="submit">Sign Up</button>
        </form>

        <!-- Homepage Button -->
        <div class="homepage-btn">
            <a class="btn btn-primary" href="index.php">Home Page</a>
        </div>
    </div>
    <script>
        function validateInput() {
            var input = document.getElementById("loginEmail").value;

            // Kiểm tra nếu đầu vào không phải là email hoặc số điện thoại
            if (!isValidEmail(input) && !isValidPhoneNumber(input)) {
                alert("Please fill in with the right email or phone number format!");
                return false;
            }

            return true;
        }

        // Hàm kiểm tra định dạng email
        function isValidEmail(email) {
            var regex = /\S+@\S+\.\S+/;
            return regex.test(email);
        }

        // Hàm kiểm tra định dạng số điện thoại
        function isValidPhoneNumber(phone) {
            var regex = /^[0-9]+$/;
            return regex.test(phone);
        }
    </script>
</body>
</html>
