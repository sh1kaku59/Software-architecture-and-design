<?php require_once('includes/showMessage.php') ?>
<?php
session_start();
if(!isset($_SESSION['user_type'])) {
    header('location: login.php');
}
require 'includes/functions.php';
displaySessionMessage();
?>
<!DOCTYPE html>
<!-- YouTube or Website - CodingLab -->
<html lang="en">

<head>
    <meta charset="UTF-8" />
    <meta http-equiv="X-UA-Compatible" content="IE=edge" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Adding Aiports</title>
    <link rel="stylesheet" href="css/style.css" />
    <!-- Fontawesome CDN Link -->
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.2.1/css/all.min.css" />
</head>

<body>
<?php include('includes/admin-nav.php') ?>;
    <div class="container mt-5">
        <h2>Airports</h2>

        <!-- Add Airport Button -->
        <div class="mb-3">
            <button type="button" class="btn btn-success" data-toggle="modal" data-target="#addAirportModal">Add
                Airport</button>
        </div>

        <table class="table table-striped">
            <thead class="table-dark">
                <tr>
                    <th>Airport Name</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                <?php
                include("connection.php");
                // Add Airport Operation
                if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST["add_airport"])) {
                    $newAirportName = mysqli_real_escape_string($con, $_POST["airport_name"]);

                    // Check if the airport already exists
                    $checkSql = "SELECT * FROM airport WHERE airport_name = '$newAirportName'";
                    $result = $con->query($checkSql);

                    if ($result->num_rows > 0) {
                        setSessionMessage("Airport already exists in the system");
                        header('location:airports.php');
                    } else {
                        // Insert the airport if it doesn't exist
                        $insertSql = "INSERT INTO airport(airport_name) VALUES ('$newAirportName')";
                        if ($con->query($insertSql) === TRUE) {
                            setSessionMessage("Airport added successfully");
                            header('location:airports.php');
                        } else {
                            echo "<script>showModal('errorModal', 'Error adding airport: " . $con->error . "');</>";
                        }
                    }
                }


                // Delete Airport Operation
                if (isset($_POST["confirm_delete_airport"])) {
                    $deleteAirportId = $_POST["delete_airport_id"];
                    $deleteSql = "DELETE FROM airport WHERE airport_id = '$deleteAirportId'";
                    if ($con->query($deleteSql) === TRUE) {
                        setSessionMessage("Airport deleted successfully");
                        header('location:airports.php');
                    } else {
                        echo "<script>showModal('errorModal', 'Error deleting airport: " . $con->error . "');</script>";
                    }
                }

                // Displaying airports in the table
                $sqlAirports = "SELECT * FROM airport";
                $resultAirports = $con->query($sqlAirports);
                if($resultAirports->num_rows > 0){
                while ($rowAirport = $resultAirports->fetch_assoc()) {
                    echo "<tr>";
                    echo "<td>" . $rowAirport["airport_name"] . "</td>";
                    echo "<td>";
                    echo "<button class='btn btn-danger btn-sm delete-airport' data-id='" . $rowAirport["airport_id"] . "' data-toggle='modal' data-target='#deleteAirportModal'>Delete</button>";
                    echo "</td>";
                    echo "</tr>";
                }
            } else {
                echo "<tr><td colspan='6' class='text-center'><h3>No airports available</h3></td></tr>";
            }
                ?>
            </tbody>
        </table>

        <!-- Add Airport Modal -->
        <div class="modal fade" id="addAirportModal" tabindex="-1" role="dialog" aria-labelledby="addAirportModalLabel"
            aria-hidden="true">
            <div class="modal-dialog" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="addAirportModalLabel">Add Airport</h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body">
                        <form action="" method="POST">
                            <div class="form-group">
                                <label for="airport_name">Airport Name</label>
                                <input type="text" class="form-control" id="airport_name" name="airport_name" required>
                            </div>
                            <div class="modal-footer">
                                <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancel</button>
                                <button type="submit" class="btn btn-primary" name="add_airport">Save</button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>

        <!-- Delete Airport Form -->
        <form action="" method="POST">
            <input type="hidden" name="delete_airport_id" id="delete_airport_id" value="">
            <div class="modal fade" id="deleteAirportModal" tabindex="-1" role="dialog"
                aria-labelledby="deleteAirportModalLabel" aria-hidden="true">
                <div class="modal-dialog" role="document">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h5 class="modal-title" id="deleteAirportModalLabel">Confirm Delete</h5>
                            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                <span aria-hidden="true">&times;</span>
                            </button>
                        </div>
                        <div class="modal-body">
                            Are you sure you want to delete this airport?
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancel</button>
                            <button type="submit" class="btn btn-danger" name="confirm_delete_airport">Delete</button>
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
        // Function to show a modal with the specified message
        // function showModal(modalId, message) {
        //     $('#' + modalId + ' .modal-body').text(message);
        //     $('#' + modalId).modal('show');
        // }

        // Click event handler for delete buttons
        $(document).on("click", ".delete-airport", function () {
            var deleteAirportId = $(this).data('id');
            $('#delete_airport_id').val(deleteAirportId);
        });
    </script>
</body>

</html>