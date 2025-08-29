<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Flight Information</title>
</head>
<body>

<?php
session_start();

// Kiểm tra xem session 'booked_flight' có tồn tại không và có chứa giá trị không
if(isset($_SESSION['booked_flight'])) {
    $booked_flight = $_SESSION['booked_flight'];

    echo "Thông tin chuyến bay:<br>";
    
    // Hiển thị thông tin chi tiết về chuyến bay
    foreach ($booked_flight as $key => $value) {
        echo ucfirst(str_replace('_', ' ', $key)) . ": " . htmlspecialchars($value) . "<br>";
    }

    // Tạo câu truy vấn SQL để lấy ID của chuyến bay từ session
    $query = "SELECT id FROM flight WHERE 
              airline_name = '{$_SESSION['booked_flight']['airline_name']}' AND 
              dep_airport = '{$_SESSION['booked_flight']['dep_airport']}' AND 
              arr_airport = '{$_SESSION['booked_flight']['arr_airport']}' AND 
              source_date = '{$_SESSION['booked_flight']['source_date']}' AND 
              source_time = '{$_SESSION['booked_flight']['source_time']}' AND 
              dest_date = '{$_SESSION['booked_flight']['dest_date']}' AND 
              dest_time = '{$_SESSION['booked_flight']['dest_time']}' AND 
              price = '{$_SESSION['booked_flight']['price']}'";

    // Thực hiện truy vấn SQL và hiển thị kết quả
    include 'connect.php'; // Kết nối đến cơ sở dữ liệu
    $result = mysqli_query($conn, $query);
    if ($result && mysqli_num_rows($result) > 0) {
        $row = mysqli_fetch_assoc($result);
        echo "ID của chuyến bay: " . $row['id'];
    } else {
        echo "Không tìm thấy chuyến bay trong cơ sở dữ liệu.";
    }
} else {
    echo "Session 'booked_flight' không tồn tại hoặc nó trống.";
}

// Kiểm tra xem thông tin người dùng có tồn tại không
if (isset($_SESSION['user_info_query']['customer_email'])) {
    $customer_email = $_SESSION['user_info_query'];

    // Hiển thị giá trị customer_email
    echo "Thông tin người dùng: " . $customer_email . "<br>";

    // Tiếp tục xử lý với giá trị này
} else {
    echo "Không tìm thấy thông tin người dùng.";
}


?>

</body>
</html>
