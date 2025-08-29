<?php
session_start();

// Kiểm tra xem session 'booked_flight' có tồn tại không
if (isset($_SESSION['booked_flight'])) {
    $flightDetails = $_SESSION['booked_flight'];
} else {
    echo "Không tìm thấy thông tin chuyến bay.";
    exit();
}

include 'connect.php';

// Kiểm tra xem người dùng đã đăng nhập chưa
if (isset($_SESSION['loggedIn']) && $_SESSION['loggedIn'] === true) {
    // Nếu đã đăng nhập, lấy email của người dùng
    $customer_email = $_SESSION['user_info_query']['email'];

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

    $result = mysqli_query($conn, $query);
    if ($result && mysqli_num_rows($result) > 0) {
        $row = mysqli_fetch_assoc($result);
        $flight_id = $row['id'];

        // Chèn thông tin vào bảng 'booked'
        $sql = "INSERT INTO booked (flight_id, customer_email) VALUES ('$flight_id', '$customer_email')";
        if (mysqli_query($conn, $sql)) {
            echo "Ticket booking successful .";
        } else {
            $error_message = "Lỗi khi thực hiện truy vấn SQL: " . $sql . " - Lỗi: " . mysqli_error($conn);
            file_put_contents("error_log.txt", $error_message . PHP_EOL, FILE_APPEND);
            echo "Có lỗi xảy ra. Vui lòng thử lại sau.";
        }
    } else {
        echo "Không tìm thấy chuyến bay trong cơ sở dữ liệu.";
    }
} else {
    // Nếu chưa đăng nhập, hiển thị thông báo lỗi
    echo "Ticket booking successful.";
}
?>
