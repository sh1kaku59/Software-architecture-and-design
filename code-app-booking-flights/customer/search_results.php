<?php
session_start();
include 'connect.php'; // Kết nối đến cơ sở dữ liệu

// Lấy thông tin từ biểu mẫu gửi đi
$departure = $_GET['departure']; // Thay 'GET' bằng 'POST' nếu bạn sử dụng phương thức POST
$arrival = $_GET['arrival']; // Thay 'GET' bằng 'POST' nếu bạn sử dụng phương thức POST
$departureDate = $_GET['departure_date']; // Thay 'GET' bằng 'POST' nếu bạn sử dụng phương thức POST
$seatType = $_GET['seat_type']; // Thay 'GET' bằng 'POST' nếu bạn sử dụng phương thức POST


// Truy vấn cơ sở dữ liệu để lấy thông tin về các chuyến bay phù hợp
$query = "
    SELECT flight.*, airline.logo AS airline_logo_url
    FROM flight
    JOIN airline ON flight.airline_name = airline.airline_name
    WHERE dep_airport = '$departure' 
      AND arr_airport = '$arrival' 
      AND source_date = '$departureDate' 
      AND flight_class = '$seatType'
";

$result = $conn->query($query);

// Xử lý yêu cầu đặt vé
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
  $bookedFlight = [
    'airline_name' => $_POST['airline_name'],
    'dep_airport' => $_POST['dep_airport'],
    'arr_airport' => $_POST['arr_airport'],
    'source_date' => $_POST['source_date'],
    'source_time' => $_POST['source_time'],
    'dest_date' => $_POST['dest_date'],
    'dest_time' => $_POST['dest_time'],
    'price' => $_POST['price']
  ];

  $_SESSION['booked_flight'] = $bookedFlight;

  // Chuyển hướng đến trang booking.php
  header('Location: booking.php');
  exit();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Search Results</title>
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
    .flight-card {
      margin-bottom: 20px;
      background-color: #fff;
      border-radius: 7px;
      box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
      transition: transform 0.3s ease, box-shadow 0.3s ease;
      width: 100%; 
      max-width: 1200px; 
      position: relative;
    }
    .flight-card:hover {
      transform: translateY(-5px);
      box-shadow: 0 10px 20px rgba(0, 0, 0, 0.2);
      border: 2px solid #007bff;
    }
    .flight-card .card-body {
      padding: 20px;
    }
    .flight-card .card-title {
      font-size: 20px;
      font-weight: bold;
      display: flex;
      align-items: center;
    }
    .flight-card .airline-logo {
      width: 40px;
      height: auto;
      margin-right: 10px;
    }
    .flight-card .card-text {
      font-size: 16px;
      margin-bottom: 10px;
    }
    .flight-card .price {
      font-size: 16px;
      color: #ff7700; 
      margin-top: 10px;
    }
    .flight-card .btn-book {
      min-width: 120px;
      transition: background-color 0.3s ease;
      position: absolute;
      bottom: 20px;
      right: 20px;
    }
    .flight-card .btn-book:hover {
      background-color: #ff5900;
      border: 2px solid #dea301;
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
      <div class="step">1</div>
      <div class="step inactive">2</div>
      <div class="step inactive">3</div>
    </div>
  </div>

  <div class="container">
    <h2 class="mb-4">|| Search Results</h2>

    <?php
    // Kiểm tra xem có chuyến bay nào được tìm thấy không
    if ($result->num_rows > 0) {
      // Hiển thị thông tin về các chuyến bay
      while($row = $result->fetch_assoc()) {
    ?>
      <div class="flight-card">
        <div class="card">
          <div class="card-body">
            <h5 class="card-title">
              <img src="<?php echo $row['airline_logo_url']; ?>" alt="Airline Logo" class="airline-logo">
              <?php echo $row['airline_name']; ?>
            </h5>
            <p class="card-text"><strong>From:</strong> <?php echo $row['dep_airport']; ?></p>
            <p class="card-text"><strong>To:</strong> <?php echo $row['arr_airport']; ?></p>
            <p class="card-text"><i class="fas fa-plane-departure"></i> Departure: <?php echo $row['source_date'] . ' ' . $row['source_time']; ?></p>
            <p class="card-text"><i class="fas fa-plane-arrival"></i> Arrival: <?php echo $row['dest_date'] . ' ' . $row['dest_time']; ?></p>
            <p class="price"><i class="fas fa-dollar-sign"></i> Price: $<?php echo $row['price']; ?></p>
            
            <form method="POST" action="" class="book-form">
              <input type="hidden" name="airline_name" value="<?php echo $row['airline_name']; ?>">
              <input type="hidden" name="dep_airport" value="<?php echo $row['dep_airport']; ?>">
              <input type="hidden" name="arr_airport" value="<?php echo $row['arr_airport']; ?>">
              <input type="hidden" name="source_date" value="<?php echo $row['source_date']; ?>">
              <input type="hidden" name="source_time" value="<?php echo $row['source_time']; ?>">
              <input type="hidden" name="dest_date" value="<?php echo $row['dest_date']; ?>">
              <input type="hidden" name="dest_time" value="<?php echo $row['dest_time']; ?>">
              <input type="hidden" name="price" value="<?php echo $row['price']; ?>">
              <button type="submit" class="btn btn-primary btn-book"><i class="fas fa-shopping-cart"></i> Book Now</button>
            </form>
          </div>
        </div>
      </div>
    <?php
      }
    } else {
      // Hiển thị thông báo nếu không tìm thấy chuyến bay
      echo "<p>No flights found.</p>";
    }
    ?>

  </div>
  
<!-- Bootstrap JS and jQuery -->
<script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.5.4/dist/umd/popper.min.js"></script>
<script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
<!-- Font Awesome JS -->
<script src="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.3/js/all.min.js"></script>

<script>
  function redirectToBooking() {
    window.location.href = 'booking.php';
  }
</script>
<?php include 'footer.php'; ?>
</body>
</html>
