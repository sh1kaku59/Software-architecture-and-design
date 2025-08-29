<?php
session_start();
if (!isset($_SESSION['user_type'])) {
    header('location: login.php');
}
require_once('includes/showMessage.php');
require 'includes/functions.php';
displaySessionMessage();

if (isset($_GET['id'])) {
    $flightId = $_GET['id'];

    include("connection.php");

    $sql = "SELECT * FROM flight WHERE id = '$flightId'";
    $result = $con->query($sql);

    if ($result->num_rows > 0) {
        $row = $result->fetch_assoc();

        // Get airline options
        $sqlAirline = "SELECT airline_name FROM airline";
        $resultAirline = $con->query($sqlAirline);

        // Get airport options
        $sqlAirport = "SELECT airport_name FROM airport";
        $resultAirport = $con->query($sqlAirport);
        
        // Process form submission
        if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST["update_flight"])) {
            // Retrieve form data
            $airlineName = $_POST["airline_name"];
            $depAirport = $_POST["dep_airport"];
            $arrAirport = $_POST["arr_airport"];
            $sourceDate = $_POST["source_date"];
            $sourceTime = $_POST["source_time"];
            $destDate = $_POST["dest_date"];
            $destTime = $_POST["dest_time"];
            $seats = $_POST["seats"];
            $price = $_POST["price"];
            $flightClass = $_POST["flight_class"];
            $airlineEmail = $_POST["airline_email"];

            // Update flight in the database
            $updateSql = "UPDATE flight SET airline_name='$airlineName', dep_airport='$depAirport', arr_airport='$arrAirport', source_date='$sourceDate', source_time='$sourceTime', dest_date='$destDate', dest_time='$destTime', seats='$seats', price='$price', flight_class='$flightClass', airline_email='$airlineEmail' WHERE id='$flightId'";
            
            if ($con->query($updateSql) === TRUE) {
                setSessionMessage("Flight updated successfully");
                header('location: show-flight.php');
            } else {
                echo "<script>showModal('errorModal', 'Error updating flight: " . $con->error . "');</script>";
            }
        }
    } else {
        setSessionMessage("Flight not found");
        header('location: show-flight.php');
    }
} else {
    setSessionMessage("Flight ID not provided");
    header('location: show-flight.php');
}
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit Flight</title>
    <link rel="stylesheet" href="css/style.css"/>
    <!-- Fontawesome CDN Link -->
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.2.1/css/all.min.css" />
</head>

<body>
    <?php include('includes/admin-nav.php'); ?>

    <div class="container mt-5">
        <h2>Edit Flight</h2>

        <form action="" method="POST">
            <div class="form-group">
                <label for="airline_name">Airline</label>
                <select class="form-control" id="airline_name" name="airline_name">
                    <?php
                    if ($resultAirline->num_rows > 0) {
                        while ($rowAirline = $resultAirline->fetch_assoc()) {
                            $selected = ($rowAirline['airline_name'] == $row['airline_name']) ? 'selected' : '';
                            echo "<option value='" . $rowAirline['airline_name'] . "' $selected>" . $rowAirline['airline_name'] . "</option>";
                        }
                    }
                    ?>
                </select>
            </div>
            <div class="form-group">
                <label for="dep_airport">Departure Airport</label>
                <select class="form-control" id="dep_airport" name="dep_airport">
                    <?php
                    if ($resultAirport->num_rows > 0) {
                        while ($rowAirport = $resultAirport->fetch_assoc()) {
                            $selected = ($rowAirport['airport_name'] == $row['dep_airport']) ? 'selected' : '';
                            echo "<option value='" . $rowAirport['airport_name'] . "' $selected>" . $rowAirport['airport_name'] . "</option>";
                        }
                    }
                    ?>
                </select>
            </div>
            <div class="form-group">
                <label for="arr_airport">Arrival Airport</label>
                <select class="form-control" id="arr_airport" name="arr_airport">
                    <?php
                    $resultAirport->data_seek(0); // Reset pointer to start
                    if ($resultAirport->num_rows > 0) {
                        while ($rowAirport = $resultAirport->fetch_assoc()) {
                            $selected = ($rowAirport['airport_name'] == $row['arr_airport']) ? 'selected' : '';
                            echo "<option value='" . $rowAirport['airport_name'] . "' $selected>" . $rowAirport['airport_name'] . "</option>";
                        }
                    }
                    ?>
                </select>
            </div>
            <div class="form-group">
                <label for="source_date">Departure Date</label>
                <input type="date" class="form-control" id="source_date" name="source_date" value="<?php echo $row['source_date']; ?>">
            </div>
            <div class="form-group">
                <label for="source_time">Departure Time</label>
                <input type="time" class="form-control" id="source_time" name="source_time" value="<?php echo $row['source_time']; ?>">
            </div>
            <div class="form-group">
                <label for="dest_date">Arrival Date</label>
                <input type="date" class="form-control" id="dest_date" name="dest_date" value="<?php echo $row['dest_date']; ?>">
            </div>
            <div class="form-group">
                <label for="dest_time">Arrival Time</label>
                <input type="time" class="form-control" id="dest_time" name="dest_time" value="<?php echo $row['dest_time']; ?>">
            </div>
            <div class="form-group">
                <label for="seats">Seats</label>
                <input type="number" class="form-control" id="seats" name="seats" value="<?php echo $row['seats']; ?>">
            </div>
            <div class="form-group">
                <label for="price">Price</label>
                <input type="number" class="form-control" id="price" name="price" value="<?php echo $row['price']; ?>">
            </div>
            <div class="form-group">
                <label for="flight_class">Flight Class</label>
                <select class="form-control" id="flight_class" name="flight_class">
                    <option value="Economy" <?php if ($row['flight_class'] == 'Economy') echo 'selected'; ?>>Economy</option>
                    <option value="Business" <?php if ($row['flight_class'] == 'Business') echo 'selected'; ?>>Business</option>
                    <option value="First Class" <?php if ($row['flight_class'] == 'First Class') echo 'selected'; ?>>First Class</option>
                </select>
            </div>
            <div class="form-group">
                <label for="airline_email">Airline Email</label>
                <input type="email" class="form-control" id="airline_email" name="airline_email" value="<?php echo $row['airline_email']; ?>">
            </div>

            <button type="submit" class="btn btn-primary" name="update_flight">Save</button>
            <a href="show-flight.php" class="btn btn-secondary">Cancel</a>
        </form>
    </div>

    <!-- Bootstrap and jQuery Scripts -->
    <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@1.16.1/dist/umd/popper.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
</body>

</html>
