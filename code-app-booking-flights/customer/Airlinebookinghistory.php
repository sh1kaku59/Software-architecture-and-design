<?php
// Bắt đầu phiên
session_start();

// Kiểm tra xem người dùng đã đăng nhập hay chưa
if (!isset($_SESSION['loggedIn']) || $_SESSION['loggedIn'] !== true) {
    // Nếu chưa đăng nhập, chuyển hướng đến trang đăng nhập
    header("Location: login.php");
    exit();
}

// Kết nối đến cơ sở dữ liệu
include 'connect.php';

// Lấy email của người dùng từ phiên
$email = $_SESSION['user_info_query']['email'];

// Truy vấn để lấy lịch sử đặt vé của người dùng
$sql = "SELECT flight.source_date, flight.source_time, flight.dest_date, flight.dest_time, flight.price, flight.dep_airport, flight.arr_airport, flight.flight_class, flight.airline_name
        FROM flight
        INNER JOIN booked ON flight.id = booked.flight_id
        WHERE booked.customer_email = ?";


        
$stmt = $conn->prepare($sql);
$stmt->bind_param("s", $email);
$stmt->execute();
$result = $stmt->get_result();

// Đóng kết nối
$conn->close();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <?php include 'header.php'; ?>
    <title>Booking History</title>
    <!-- Bootstrap CSS -->
    <link href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css" rel="stylesheet">
    <!-- Font Awesome CSS -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css">
    <!-- Custom CSS -->
    <!-- CSS styling for the booking history table -->
    <style>
        body {
            font-family: 'Montserrat', sans-serif;
        }
        table {
            width: 100%;
            border-collapse: collapse;
        }
        .table-container {
            margin-top: 50px;
            margin-bottom: 50px;
        }
        th, td {
            padding: 8px;
            text-align: left;
            border-bottom: 1px solid #ddd;
        }
        th {
            background-color: #f2f2f2;
        }
        .cancel-button {
            background-color: #f44336;
            color: white;
            padding: 8px 12px;
            border: none;
            border-radius: 4px;
            cursor: pointer;
        }
        .cancel-button {
            background-color: #f44336;
            color: white;
            padding: 8px 12px;
            border: none;
            border-radius: 4px;
            cursor: pointer;
        }
        .container {
            max-width: 1500px; /* Adjust the width as needed */
        }
    </style>
</head>
<body>
    <div class="container table-container">
        <h2 class="mb-4">Booking History</h2>
        <div class="table-responsive">
            <table class="table table-striped">
                <thead class="thead-dark">
                    <tr>
                        <th>Departure Date</th>
                        <th>Departure Time</th>
                        <th>Arrival Date</th>
                        <th>Arrival Time</th>
                        <th>Price</th>
                        <th>Departure Airport</th>
                        <th>Arrival Airport</th>
                        <th>Flight Class</th>
                        <th>Airline</th>
                        <th>Action</th>
                    </tr>
                </thead>
                <tbody>
                    <?php
                    // Loop through the results and display the information
                    while ($row = $result->fetch_assoc()) {
                        echo "<tr>";
                            echo "<td>" . $row["source_date"] . "</td>";
                            echo "<td>" . $row["source_time"] . "</td>"; // Added here
                            echo "<td>" . $row["dest_date"] . "</td>";
                            echo "<td>" . $row["dest_time"] . "</td>"; // Added here
                            echo "<td>" . $row["price"] . "</td>";
                            echo "<td>" . $row["dep_airport"] . "</td>";
                            echo "<td>" . $row["arr_airport"] . "</td>";
                            echo "<td>" . $row["flight_class"] . "</td>";
                            echo "<td>" . $row["airline_name"] . "</td>";
                            echo "<td><button class='cancel-button' onclick='cancelBooking()'>Cancel</button></td>";
                        echo "</tr>";
                    }
                    ?>
                </tbody>
            </table>
        </div>
    </div>

    <!-- Bootstrap JS and jQuery (for Bootstrap) -->
    <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.5.4/dist/umd/popper.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>

    <!-- JavaScript script -->
    <script>
        function cancelBooking() {
            // Display a message indicating that a refund request has been sent
            alert("Refund request has been sent.");
        }
    </script>
</body>
</html>