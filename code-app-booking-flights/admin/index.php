<?php include('includes/header.php'); ?>

<div>
    <div class="after-nav">
        <div class="fixed-text">WELCOME EASYFLY</div>
        <div id="text-transition" class="transition-text"></div> <!--span texts from js-->
    </div>
    <script src="js/after-nav-script.js"></script>
</div>

<main>
    <div class="bg-image"></div>
</main>

<div class="partner-airline">
    <h1>Partners airline</h1>

    <?php
    // Include the connection.php file
    include('connection.php');

    // SQL query to select all airline from the airline table
    $sql = "SELECT * FROM airline";

    // Execute the query
    $result = mysqli_query($con, $sql);

    if (mysqli_num_rows($result) > 0) {
        while ($row = mysqli_fetch_assoc($result)) {
            echo '<div class="partner-airline">';
            echo '<img src="' . $row['logo'] . '" alt="' . $row['airline_name'] . '">';
            echo '<p>' . $row['airline_name'] . '</p>';
            echo '</div>';
        }
    } else {
        echo "No records found";
    }

    // Close the database connection
    mysqli_close($con);
    ?>
</div>

<?php include('includes/footer.php')?>