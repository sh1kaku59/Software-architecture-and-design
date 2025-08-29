<?php
session_start();

// Kiểm tra xem session 'booked_flight' có tồn tại không
if (isset($_SESSION['booked_flight'])) {
    $flightDetails = $_SESSION['booked_flight'];
} else {
    echo "No flight details found.";
    exit();
}
include 'connect.php';
?>



<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Payment for Flight Tickets</title>
    <?php include 'header.php'; ?>
    <!-- Bootstrap CSS -->
    <link href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css" rel="stylesheet">
    <!-- Font Awesome CSS -->
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css" rel="stylesheet">
    <style>
        /* Add your custom styles here */

        .container {
            background-color: #f9f9f9;
            border-radius: 8px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
            margin-bottom: 20px;
            padding: 20px;
        }

        .header {
            background-color: #007bff;
            color: #fff;
            padding: 10px;
            box-shadow: 0px 2px 5px rgba(0, 0, 0, 0.1);
            margin-bottom: 20px;
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

        .payment-method {
            margin-bottom: 15px;
        }

        .payment-method input[type="radio"] {
            margin-right: 10px;
        }

        .payment-method label {
            font-size: 16px;
            color: #555;
            cursor: pointer;
        }

        .payment-method label:hover {
            color: #333;
        }

        .payment-form, .qr-code-form {
            display: none;
        }

        .qr-code-container {
            display: flex;
            justify-content: space-between;
            margin-top: 15px;
        }

        .qr-code-label {
            padding: 5px 10px;
            background-color: #f0f0f0;
            border-radius: 5px;
            font-weight: bold;
            text-transform: uppercase;
            font-size: 14px;
            color: #333;
        }

        .qr-code {
            max-width: 200px;
            height: auto;
        }

        .btn-pay:hover {
        background-color: #0069d9;
        box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
    }
    </style>
</head>
<body>
        <div class="header">
            <div class="steps">
                <div class="step inactive">1</div>
                <div class="step inactive">2</div>
                <div class="step">3</div>
            </div>
        </div>

        <div class="container">
        <h1 class="mb-4">Flight Details</h1>
        <!-- Flight details -->
        
        <div class="row mb-3">
            <div class="col-sm-4 font-weight-bold">Passenger:</div>
            <div class="col-sm-8 passenger-details" id="displayPassenger"></div>
        </div>


        <div class="row mb-3">
            <div class="col-sm-4 font-weight-bold">Airline:</div>
            <div class="col-sm-8"><?php echo htmlspecialchars($flightDetails['airline_name']); ?></div>
        </div>
        <div class="row mb-3">
            <div class="col-sm-4 font-weight-bold">Departure Airport:</div>
            <div class="col-sm-8"><?php echo htmlspecialchars($flightDetails['dep_airport']); ?></div>
        </div>
        <div class="row mb-3">
            <div class="col-sm-4 font-weight-bold">Arrival Airport:</div>
            <div class="col-sm-8"><?php echo htmlspecialchars($flightDetails['arr_airport']); ?></div>
        </div>
        <div class="row mb-3">
            <div class="col-sm-4 font-weight-bold">Departure Date:</div>
            <div class="col-sm-8"><?php echo htmlspecialchars($flightDetails['source_date']); ?></div>
        </div>
        <div class="row mb-3">
            <div class="col-sm-4 font-weight-bold">Departure Time:</div>
            <div class="col-sm-8"><?php echo htmlspecialchars($flightDetails['source_time']); ?></div>
        </div>
        <div class="row mb-3">
            <div class="col-sm-4 font-weight-bold">Arrival Date:</div>
            <div class="col-sm-8"><?php echo htmlspecialchars($flightDetails['dest_date']); ?></div>
        </div>
        <div class="row mb-3">
            <div class="col-sm-4 font-weight-bold">Arrival Time:</div>
            <div class="col-sm-8"><?php echo htmlspecialchars($flightDetails['dest_time']); ?></div>
        </div>
        <div class="row mb-3">
            <div class="col-sm-4 font-weight-bold">Price:</div>
            <div class="col-sm-8">$<?php echo htmlspecialchars($flightDetails['price']); ?></div>
        </div>
        
    </div>

        <div class="container">
            <h1 class="mb-4">Select Payment Method</h1>
            <div class="payment-method">
                <div class="custom-control custom-radio">
                    <input type="radio" id="bank-transfer" name="payment" value="bank_transfer" class="custom-control-input" onclick="showCardInfo()">
                    <label class="custom-control-label" for="bank-transfer">Bank Transfer <i class="fas fa-university ml-2"></i></label>
                </div>
            </div>
            <div class="payment-method">
                <div class="custom-control custom-radio">
                    <input type="radio" id="e-wallet" name="payment" value="e_wallet" class="custom-control-input" onclick="showQRCode()">
                    <label class="custom-control-label" for="e-wallet">E-Wallet <i class="fas fa-wallet ml-2"></i></label>
                </div>
            </div>
        </div>

        <div class="container">
            <form id="card-info" class="payment-form">
                <h1 class="mb-4">Card Information</h1>
                <div class="form-group">
                    <label for="card-number">Card Number:</label>
                    <input type="text" id="card-number" name="card-number" class="form-control" required>
                </div>
                <div class="form-row">
                    <div class="form-group col-md-8">
                        <label for="expiration-date">Expiration Date:</label>
                        <input type="text" id="expiration-date" name="expiration-date" class="form-control" placeholder="MM/YYYY" required>
                    </div>
                    <div class="form-group col-md-4">
                        <label for="cvv">CVV:</label>
                        <input type="password" id="cvv" name="cvv" class="form-control" maxlength="4" required>
                    </div>
                </div>

                <button type="button" onclick="bookFlight()" class="btn btn-primary btn-lg btn-block btn-pay">Pay Now</button>
            </form>

            <form id="qr-code-form" class="qr-code-form">
                <h1 class="mb-4">Qr code</h1>
                <div class="qr-code-container">
                    <div class="qr-code-label momo-label">MoMo</div>
                    <div class="qr-code-label zalopay-label">ZaloPay</div>
                </div>
                <div class="qr-code-container">
                    <img src="#" id="qr-code-momo" alt="MoMo QR Code" class="qr-code">
                    <img src="#" id="qr-code-zalopay" alt="ZaloPay QR Code" class="qr-code">
                </div>
            </form>
        </div>
    </div>

    <script>
        window.onload = function() {
            // Mặc định chọn phương thức thanh toán là chuyển khoản và ẩn thông tin thẻ
            document.getElementById("bank-transfer").checked = true;
            showCardInfo();
        };

        function showCardInfo() {
            document.getElementById("card-info").style.display = "block";
            document.getElementById("qr-code-form").style.display = "none";
        }

        function showQRCode() {
            document.getElementById("card-info").style.display = "none";
            document.getElementById("qr-code-form").style.display = "block";
            // Replace '#' with the URL to MoMo QR code
            document.getElementById("qr-code-momo").src = "images/MoMo QR.png";
            // Replace '#' with the URL to ZaloPay QR code
            document.getElementById("qr-code-zalopay").src = "images/ZaloPay QR.png";
        }

        function validateInput() {
            var cardNumber = document.getElementById("card-number").value;
            var expirationDate = document.getElementById("expiration-date").value;
            var cvv = document.getElementById("cvv").value;

            if (cardNumber === "" || expirationDate === "" || cvv === "") {
                alert("Please enter complete information in all fields!");
                return false;
            }
            return true;
        }

        function bookFlight() {
    if (validateInput()) {
        // Thực hiện quá trình đặt vé ở đây
        // Gửi yêu cầu AJAX để lưu thông tin đặt vé
        var xhr = new XMLHttpRequest();
        xhr.open("POST", "save_booking.php", true);
        xhr.setRequestHeader("Content-Type", "application/x-www-form-urlencoded");
        xhr.onreadystatechange = function() {
            if (xhr.readyState === 4 && xhr.status === 200) {
                // Nếu đặt vé thành công, hiển thị cảnh báo
                alert(xhr.responseText);
                // Chuyển hướng đến booking.php sau khi đặt vé thành công
                window.location.href = 'booking.php';
            }
        };
        xhr.send();
    }
}


    // Lấy thông tin hành khách từ sessionStorage
    var title = sessionStorage.getItem('passengerTitle');
        var firstName = sessionStorage.getItem('passengerFirstName');
        var lastName = sessionStorage.getItem('passengerLastName');

        // Tạo chuỗi hành khách
        var passengerDetails = title + ' ' + firstName + ' ' + lastName;

        // Hiển thị thông tin hành khách trên trang
        document.getElementById('displayPassenger').innerText = passengerDetails;
    </script>
<?php include 'footer.php'; ?>
</body>
</html>

