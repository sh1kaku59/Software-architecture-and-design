<?php
session_start();
if (!isset($_SESSION['user_type'])) {
    header('location: login.php');
}
require_once('includes/showMessage.php');
require 'includes/functions.php';
displaySessionMessage();
include("navOptions/airline-dashboard-nav-options.php");
// Get the user_email from the session
$user_email = $_SESSION['email'];


//fetch the name
require_once 'connection.php';
$sql = "SELECT airline_name FROM airline WHERE email = '$user_email'";
$result = $con->query($sql);

if ($result->num_rows > 0) {
    $row = $result->fetch_assoc();
    $customer_name = $row['airline_name'];
} else {
    $customer_name = "Guest"; // Default if no name found
}

?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Managing Bookings</title>
    <link rel="stylesheet" href="css/style.css" />
    <!-- Fontawesome CDN Link -->
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.2.1/css/all.min.css" />
    <link rel="stylesheet" type="text/css" href="css/general.css">
</head>

<body>
    <header>
    </header>

    <nav>
        <a class="logo" href="index.php"> <img src="images/Easyfly.png" alt="site-logo"> </a>
        <?php include('navOptions/nav.php') ?>
    </nav>



    <div class="user-info">
            <p style="text-align: right; font-size: 24px;">
                <span style="font-weight: light; color: #999; margin-bottom: 10px; margin-right: 10px"><em style="font-style: italic;">user:</em></span>
                <span style="font-size: 20pt; color: #333; margin-right: 35px"><?php echo $customer_name; ?></span>
            </p>
        </div>



    <div class="container mt-5">
        <h2>Booked Flight List</h2>

        <table class="table table-striped">
        <thead class="table-dark">
    <tr>
        <th>Booking ID</th> <!-- Include this line for the ID field -->
        <th>Customer Email</th>
        <th>Flight ID</th>
        <th>Departure Airport</th>
        <th>Arrival Airport</th>
        <th>Departure</th>
        <th>Arrival</th>
        <th>Class</th>
        <th>Actions</th>
    </tr>
</thead>

            <tbody>

                <!-- Displaying bookings in the table with joined data for the user_email -->
                <?php
                include("connection.php");

                // Delete Booking Operation
                if (isset($_POST["confirm_delete_booking"])) {
                    $deleteBookingId = $_POST["delete_booking_id"];
                    $deleteSql = "DELETE FROM booked WHERE id = '$deleteBookingId'";
                    if ($con->query($deleteSql) === TRUE) {
                        setSessionMessage("Booking deleted successfully");
                        header('location: airline-dashboard.php');
                    } else {
                        echo "<script>showModal('errorModal', 'Error deleting booking: " . $con->error . "');</script>";
                    }
                }

                $sqlBookings = "SELECT b.id, b.customer_email, b.flight_id, a1.airport_name as dep_airport, a2.airport_name as arr_airport, 
                f.source_date, f.source_time, f.dest_date, f.dest_time, f.flight_class 
                FROM booked b
                INNER JOIN flight f ON b.flight_id = f.id
                INNER JOIN airline a ON f.airline_email = a.email
                INNER JOIN airport a1 ON f.dep_airport_id = a1.airport_id
                INNER JOIN airport a2 ON f.arr_airport_id = a2.airport_id
                WHERE a.email = '$user_email'"; // Filter by airline_email
        
        
                $resultBookings = $con->query($sqlBookings);

                if ($resultBookings->num_rows > 0) {
                    while ($rowBooking = $resultBookings->fetch_assoc()) {
                        echo "<tr>";
                        echo "<td>" . $rowBooking["id"] . "</td>"; // Include this line for the ID field
                        echo "<td>" . $rowBooking["customer_email"] . "</td>";
                        echo "<td>" . $rowBooking["flight_id"] . "</td>";
                        echo "<td>" . $rowBooking["dep_airport"] . "</td>";
                        echo "<td>" . $rowBooking["arr_airport"] . "</td>";
                        echo "<td>" . $rowBooking["source_date"] . "</td>";
                        echo "<td>" . $rowBooking["dest_date"] . "</td>";
                        echo "<td>" . $rowBooking["flight_class"] . "</td>";
                        echo "<td>";
                        echo "<button class='btn btn-danger btn-sm delete-booking' data-id='" . $rowBooking["id"] . "' data-toggle='modal' data-target='#deleteBookingModal'>Delete</button>";
                        echo "</td>";
                        echo "</tr>";
                    }
                    
                } else {
                    echo "<tr><td colspan='8' class='text-center'><h3>No one have chosen your airline!</h3><br><h5>Please do some Marketing</h5></td></tr>";
                }
                ?>
            </tbody>
        </table>
                <!-- Delete Booking Form -->
                <form action="" method="POST">
                    <input type="hidden" name="delete_booking_id" id="delete_booking_id" value="">
                    <div class="modal fade" id="deleteBookingModal" tabindex="-1" role="dialog"
                        aria-labelledby="deleteBookingModalLabel" aria-hidden="true">
                        <div class="modal-dialog" role="document">
                            <div class="modal-content">
                                <div class="modal-header">
                                    <h5 class="modal-title" id="deleteBookingModalLabel">Confirm Delete</h5>
                                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                        <span aria-hidden="true">&times;</span>
                                    </button>
                                </div>
                                <div class="modal-body">
                                    Are you sure you want to delete this booking?
                                </div>
                                <div class="modal-footer">
                                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancel</button>
                                    <button type="submit" class="btn btn-danger"
                                        name="confirm_delete_booking">Delete</button>
                                </div>
                            </div>
                        </div>
                    </div>
                </form>
    </div>
        <!-- Bootstrap and jQuery Scripts -->
        <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
        <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@1.16.1/dist/umd/popper.min.js"></script>
        <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>

        <!-- JavaScript to handle modals -->
        <script>
            // Click event handler for delete buttons
            $(document).on("click", ".delete-booking", function () {
                var deleteBookingId = $(this).data('id');
                $('#delete_booking_id').val(deleteBookingId);
            });
        </script>
      <footer>
            <ul>
                <li><a href="index.php">Home</a></li>
                <li><a href="aboutUs.php">About Us</a></li>
                <li><a href="aboutUs.php#targeting-contact">Contact</a></li>
                <li><a href="booking-form.php">Services</a></li>
            </ul>
            <p>&copy 2023 EASYFLY, all right reserved</p>
        </footer>

</body>

</html>