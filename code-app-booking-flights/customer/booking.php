<?php

session_start();


$loggedIn = isset($_SESSION['loggedIn']) && $_SESSION['loggedIn'] === true;

// Nếu người dùng đã đăng nhập, lấy thông tin từ session
$lastName = $loggedIn && isset($_SESSION['user_info_query']['last_name']) ? $_SESSION['user_info_query']['last_name'] : '';
$firstName = $loggedIn && isset($_SESSION['user_info_query']['first_name']) ? $_SESSION['user_info_query']['first_name'] : '';
$phoneNumber = $loggedIn && isset($_SESSION['user_info_query']['phone']) ? $_SESSION['user_info_query']['phone'] : '';
$email = $loggedIn && isset($_SESSION['user_info_query']['email']) ? $_SESSION['user_info_query']['email'] : '';

// Kết nối đến cơ sở dữ liệu
include 'connect.php';
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Booking</title>
  <?php include 'header.php'; ?>
  <!-- Bootstrap CSS -->
  <link href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css" rel="stylesheet">
  <!-- Font Awesome CSS -->
  <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.3/css/all.min.css" rel="stylesheet">
  <!-- Google Fonts -->
  <link href="https://fonts.googleapis.com/css2?family=Montserrat:wght@400;700&display=swap" rel="stylesheet">
  <style>
    body {
      font-family: 'Montserrat', sans-serif;
      background-color: #f9f9f9;
    }
    .container {
      padding-top: 50px;
      padding-bottom: 50px;
    }
    .card-header {
      background-color: #007bff;
      color: #fff;
    }
    .btn-primary {
      background-color: #007bff;
      border-color: #007bff;
    }
    .btn-primary:hover {
      background-color: #0056b3;
      border-color: #0056b3;
    }
    .header {
      background-color: #007bff;
      color: #fff;
      padding: 10px;
      box-shadow: 0px 2px 5px rgba(0, 0, 0, 0.1);
    }
    .steps {
      display: flex;
      justify-content: center;
      align-items: center;
    }
    .step {
      width: 40px;
      height: 40px;
      border: 2px solid #007bff;
      border-radius: 50%;
      background-color: #fff;
      display: flex;
      justify-content: center;
      align-items: center;
      margin-right: 10px;
      font-size: 14px;
      font-weight: bold;
      color: #007bff;
    }
    .step.inactive {
      background-color: #007bff;
      color: #fff;
    }
  </style>
</head>
<body>
<div class="header">
    <div class="steps">
        <div class="step inactive">1</div>
        <div class="step">2</div>
        <div class="step inactive">3</div>
    </div>
</div>

<div class="container">
  <h2 class="my-4">Booking</h2>

  <!-- Contact Information Form -->
  <div class="card mb-4">
    <div class="card-header">
      Contact Information
    </div>
    <div class="card-body">
      <form id="contactForm">
        <div class="form-row">
          <div class="form-group col-md-6">
            <label for="lastName">Last Name</label>
            <input type="text" class="form-control" id="lastName" name="lastName" value="<?php echo htmlspecialchars($lastName); ?>" required>
          </div>
          <div class="form-group col-md-6">
            <label for="firstName">First Name</label>
            <input type="text" class="form-control" id="firstName" name="firstName" value="<?php echo htmlspecialchars($firstName); ?>" required>
          </div>
        </div>
        <div class="form-row">
          <div class="form-group col-md-6">
            <label for="phoneNumber">Phone Number</label>
            <input type="text" class="form-control" id="phoneNumber" name="phoneNumber" value="<?php echo htmlspecialchars($phoneNumber); ?>" required>
          </div>
          <div class="form-group col-md-6">
            <label for="email">Email</label>
            <input type="email" class="form-control" id="email" name="email" value="<?php echo htmlspecialchars($email); ?>" required>
          </div>
        </div>
      </form>
    </div>
  </div>

  <!-- Passenger Information Form -->
  <div class="card">
    <div class="card-header">
      Passenger Information
    </div>
    <div class="card-body">
      <form id="passengerForm" action="payment.php" method="POST">
        <div class="form-row">
          <div class="form-group col-md-2">
            <label for="title">Title</label>
            <select class="form-control" id="title" name="title" required>
              <option value="Mr.">Mr.</option>
              <option value="Mrs.">Mrs.</option>
              <option value="Miss">Miss</option>
              <option value="Ms">Ms</option>
            </select>
          </div>
          <div class="form-group col-md-5">
            <label for="passengerFirstName">First Name</label>
            <input type="text" class="form-control" id="passengerFirstName" name="passengerFirstName" required>
          </div>
          <div class="form-group col-md-5">
            <label for="passengerLastName">Last Name</label>
            <input type="text" class="form-control" id="passengerLastName" name="passengerLastName" required>
          </div>
        </div>
        <div class="form-row">
          <div class="form-group col-md-6">
            <label for="dob">Date of Birth</label>
            <input type="date" class="form-control" id="dob" name="dob" required>
          </div>
          <div class="form-group col-md-6">
            <label for="nationality">Nationality</label>
            <input type="text" class="form-control" id="nationality" name="nationality" required>
          </div>
        </div>
        <div class="form-row">
          <div class="form-group col-md-6">
            <label for="passportNumber">Passport Number</label>
            <input type="text" class="form-control" id="passportNumber" name="passportNumber" required>
          </div>
          <div class="form-group col-md-6">
            <label for="passportExpiry">Passport Expiry Date</label>
            <input type="date" class="form-control" id="passportExpiry" name="passportExpiry" required>
          </div>
        </div>
        <button type="submit" class="btn btn-primary">Submit</button>
      </form>
    </div>
  </div>
</div>

<!-- Bootstrap JS and jQuery -->
<script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.5.4/dist/umd/popper.min.js"></script>
<script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
<!-- Font Awesome JS -->
<script src="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.3/js/all.min.js"></script>


<script>
  function savePassengerInfo() {
    // Lấy thông tin từ form Passenger Information
    var title = document.getElementById('title').value;
    var passengerFirstName = document.getElementById('passengerFirstName').value;
    var passengerLastName = document.getElementById('passengerLastName').value;

    // Lưu thông tin vào sessionStorage
    sessionStorage.setItem('passengerTitle', title);
    sessionStorage.setItem('passengerFirstName', passengerFirstName);
    sessionStorage.setItem('passengerLastName', passengerLastName);
  }

  // Thêm sự kiện lắng nghe cho form Passenger Information
  document.getElementById('passengerForm').addEventListener('submit', function(event) {
    event.preventDefault(); // Chặn sự kiện gửi form mặc định
    savePassengerInfo(); // Gọi hàm savePassengerInfo để lưu thông tin
    window.location.href = 'payment.php';
  });
</script>

<?php include 'footer.php'; ?>
</body>
</html>
