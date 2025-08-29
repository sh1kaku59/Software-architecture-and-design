<?php
include 'connect.php'; // Kết nối đến cơ sở dữ liệu

// Thực hiện truy vấn để lấy danh sách các sân bay từ cơ sở dữ liệu
$query_airports = "SELECT airport_name FROM airport";
$result_airports = mysqli_query($conn, $query_airports);
?>



<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Flight Booking</title>
  <?php include 'header.php'; ?>
  <!-- Bootstrap CSS -->
  <link href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css" rel="stylesheet">
  <!-- Font Awesome CSS -->
  <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.3/css/all.min.css" rel="stylesheet">
  <!-- Google Fonts -->
  <link href="https://fonts.googleapis.com/css2?family=Montserrat:wght@400;700&display=swap" rel="stylesheet">
  <style>
    body {
      margin: 0;
      font-family: 'Montserrat', sans-serif;
      background-color: #f9f9f9;
      height: 100%;
    }
    .jumbotron {
      margin-top: 70px;
      margin-bottom: 100px;
      background-color: rgba(255, 255, 255, 0.8); 
      border-radius: 10px;
      box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
      padding: 40px;
    }
    .jumbotron h1 {
      font-weight: bold;
      font-size: 40px;
      margin-bottom: 20px;
      color: #007bff; 
    }
    .jumbotron p {
      font-size: 18px;
      margin-bottom: 30px;
      color: #555; 
    }
    .search-form {
      background-color: rgba(255, 255, 255, 0.8); 
      border-radius: 10px;
      box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
      padding: 30px;
    }
    .form-row {
      margin-bottom: 20px;
    }
    .toggle-round-trip {
      margin-top: 30px;
    }
    .btn-primary {
      min-width: 150px;
      transition: all 0.3s ease;
      background-color: #ff7700; 
      border-color: #934c00;
    }
    .btn-primary:hover {
      background-color: #ff5900; 
      border-color: #963613;
    }
    .btn-outline-secondary {
      min-width: 40px;
      transition: all 0.3s ease;
    }
    .btn-outline-secondary:hover {
      background-color: #f0f0f0;
    }
    .search-icon {
      margin-right: 10px;
    }
    .custom-select {
      appearance: none;
      -webkit-appearance: none;
      -moz-appearance: none;
      padding-right: 30px;
    }
    .one-way-background {
      background-image: url('images/one-way-flight.jpeg');
      height: 100%;
      background-position: center;
      background-repeat: no-repeat;
      background-size: cover;
      transition: background-image 0.3s ease-in-out
    }
    .round-trip-background {
      background-image: url('images/round-trip-flight.jpeg');
      height: 100%;
      background-position: center;
      background-repeat: no-repeat;
      background-size: cover;
      transition: background-image 0.3s ease-in-out
    }
  </style>
</head>
<body class="round-trip-background">

<div class="container">
  <div class="jumbotron">
    <h1 class="display-4 text-center mb-5">Find Your Perfect Flight</h1>
    <p class="lead text-center mb-5">Search and compare prices from thousands of airlines and flights.</p>
    <div class="row justify-content-center">
      <div class="col-md-12">
        
        <form class="search-form" action="search_results.php" method="GET">
          <div class="form-row">
            <div class="col-md-5 mb-3">
              <label for="inputDeparture"><i class="fas fa-plane-departure"></i> Departure</label>
              
              <select class="form-control custom-select" id="inputDeparture" name="departure" required>
                <option value="">Select Departure</option>
                <?php
                while ($row_departure = mysqli_fetch_assoc($result_airports)) {
                  echo "<option value='" . $row_departure['airport_name'] . "'>" . $row_departure['airport_name'] . "</option>";
                }
                ?>
              </select>

            </div>
            <div class="col-md-2 align-self-end mb-3">
              <button id="switchLocations" class="btn btn-outline-secondary btn-block">&#8646;</button>
            </div>
            <div class="col-md-5 mb-3">
              <label for="inputArrival"><i class="fas fa-plane-arrival"></i> Arrival</label>
              
              <select class="form-control custom-select" id="inputArrival" name="arrival" required>
                <option value="">Select Arrival</option>
                <?php
                // Reset the result set pointer and fetch the airports again for arrival
                mysqli_data_seek($result_airports, 0);
                while ($row_arrival = mysqli_fetch_assoc($result_airports)) {
                  echo "<option value='" . $row_arrival['airport_name'] . "'>" . $row_arrival['airport_name'] . "</option>";
                }
                ?>
              </select>

            </div>
          </div>
          <div class="form-row">
            <div class="col-md-4 mb-3">
              <label for="inputDepartureDate"><i class="fas fa-calendar-alt"></i> Departure Date</label>
              <input type="date" class="form-control" id="inputDepartureDate" name="departure_date" required>
            </div>
            <div class="col-md-4 mb-3">
              <label for="inputReturnDate"><i class="fas fa-calendar-alt"></i> Return Date</label>
              <input type="date" class="form-control" id="inputReturnDate" name="return_date" disabled>
            </div>
            <div class="col-md-4 mb-3">
              <label for="inputSeatType"><i class="fas fa-chair"></i> Seat Type</label>
              <select class="form-control custom-select" id="inputSeatType" name="seat_type" required>
                <option value="">Select Seat Type</option>
                <option value="Economy">Economy</option>
                <option value="Business">Business</option>
                <option value="First Class">First Class</option>
              </select>
            </div>
          </div>
          <div class="form-row">
            <div class="col-md-12">
              <div class="form-row">
                <div class="col-md-3 mb-3 toggle-round-trip align-self-end">
                  <button id="toggleRoundTrip" class="btn btn-outline-primary btn-block" type="button"> Round-Trip</button>
                </div>
                <div class="col-md-9 mb-3 align-self-end">
                  <button class="btn btn-primary btn-block" type="submit"><i class="fas fa-search search-icon"></i> Search</button>
                </div>
              </div>
            </div>
          </div>
        </form>

      </div>
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
  document.getElementById('toggleRoundTrip').addEventListener('click', function() {
    var returnDateInput = document.getElementById('inputReturnDate');
    returnDateInput.disabled = !returnDateInput.disabled;
    
    if (returnDateInput.disabled) {
      this.textContent = 'Round-Trip';
      document.body.classList.add('round-trip-background');
      document.body.classList.remove('one-way-background');
    } else {
      this.textContent = 'One-Way';
      document.body.classList.add('one-way-background');
      document.body.classList.remove('round-trip-background');
    }
  });

  document.getElementById('switchLocations').addEventListener('click', function() {
    var departureValue = document.getElementById('inputDeparture').value;
    var arrivalValue = document.getElementById('inputArrival').value;
    
    document.getElementById('inputDeparture').value = arrivalValue;
    document.getElementById('inputArrival').value = departureValue;
  });

  function saveAndRedirect() {
    // Lấy thông tin từ form
    var departure = document.getElementById('inputDeparture').value;
    var arrival = document.getElementById('inputArrival').value;
    var departureDate = document.getElementById('inputDepartureDate').value;
    var returnDate = document.getElementById('inputReturnDate').value;
    var seatType = document.getElementById('inputSeatType').value;
    var tripType = document.getElementById('tripType').value;

    // Lưu thông tin vào session
    sessionStorage.setItem('departure', departure);
    sessionStorage.setItem('arrival', arrival);
    sessionStorage.setItem('departureDate', departureDate);
    sessionStorage.setItem('seatType', seatType);


    // Chuyển hướng sang trang kết quả tìm kiếm
    window.location.href = 'search_results.php';
}

document.getElementById('searchForm').addEventListener('submit', function(event) {
    event.preventDefault(); // Chặn sự kiện gửi form mặc định
    saveAndRedirect(); // Gọi hàm saveAndRedirect để xử lý
});

</script>

<?php include 'footer.php'; ?>
</body>
</html>
