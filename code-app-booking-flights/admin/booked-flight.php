<?php
session_start();
if (!isset($_SESSION['user_type'])) {
    header('location: login.php');
}
require_once('includes/showMessage.php');
require 'includes/functions.php';
displaySessionMessage();
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
</head>

<body>
    <?php include('includes/admin-nav.php'); ?>

    <div class="container mt-5">
        <h2>Booking List</h2>

        <table class="table table-striped">
            <thead class="table-dark">
                <tr>
                    <th>Customer Email</th>
                    <th>Airline</th>
                    <th>Departure Airport</th>
                    <th>Arrival Airport</th>
                    <th>Departure</th>
                    <th>Arrival</th>
                    <th>Class</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                <?php
                include("connection.php");

                // Delete Booking Operation
                if (isset($_POST["confirm_delete_booking"])) {
                    $deleteBookingId = $_POST["delete_booking_id"];
                    $deleteSql = "DELETE FROM booked WHERE id = '$deleteBookingId'";
                    if ($con->query($deleteSql) === TRUE) {
                        setSessionMessage("Booking deleted successfully");
                        header('location: booked-flight.php');
                    } else {
                        echo "<script>showModal('errorModal', 'Error deleting booking: " . $con->error . "');</script>";
                    }
                }

                // Displaying bookings in the table with joined data
                $sqlBookings = "SELECT b.id, b.customer_email, f.airline_name, a1.airport_name as dep_airport, a2.airport_name as arr_airport, 
                f.source_date, f.source_time, f.dest_date, f.dest_time, f.flight_class 
                FROM booked b
                INNER JOIN flight f ON b.flight_id = f.id
                INNER JOIN customer c ON b.customer_email = c.email
                INNER JOIN airport a1 ON f.dep_airport_id = a1.airport_id
                INNER JOIN airport a2 ON f.arr_airport_id = a2.airport_id";
                $resultBookings = $con->query($sqlBookings);

                if ($resultBookings->num_rows > 0) {
                    while ($rowBooking = $resultBookings->fetch_assoc()) {
                        echo "<tr>";
                        echo "<td>" . $rowBooking["customer_email"] . "</td>";
                        echo "<td>" . $rowBooking["airline_name"] . "</td>";
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
                    echo "<tr><td colspan='8' class='text-center'><h3>No bookings available</h3></td></tr>";
                }
                ?>
            </tbody>
        </table>

        <!-- Delete Booking Form -->
        <form action="" method="POST">
            <input type="hidden" name="delete_booking_id" id="delete_booking_id" value="">
            <div class="modal fade" id="deleteBookingModal" tabindex="-1" role="dialog" aria-labelledby="deleteBookingModalLabel" aria-hidden="true">
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
                            <button type="button" class="btn btn-secondary" data-dismiss="modal">Refuse</button>
                            <button type="submit" class="btn btn-danger" name="confirm_delete_booking">Confirm</button>
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
        $(document).on("click", ".delete-booking", function () {
            var deleteBookingId = $(this).data('id');
            $('#delete_booking_id').val(deleteBookingId);
        });
        $(document).ready(function () {
        // Bắt sự kiện click trên nút "Refuse"
        $('.btn-secondary').click(function () {
            // Hiển thị thông báo "Refuse successfully"
            alert('Refuse successfully');
        });
    });
    </script>
</body>

</html>
