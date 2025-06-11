<?php

$servername = "localhost";
$username = "root";
$password = "";
$dbname = "hotel_reservation_system";

$conn = mysqli_connect($servername, $username, $password, $dbname);

// Check connection
if (!$conn) {
    die("Connection failed: " . mysqli_connect_error());
}

// Get current date and time
$currentDate = date('Y-m-d');
$currentTime = date('H:i:s');

// Check if the current time is past 7:00 PM
if ($currentTime >= '19:00:00') {
    // Query to update pending reservations after 7 PM
    $sql = "UPDATE reservations 
            SET status = 'cancelled' 
            WHERE status = 'pending' 
            AND check_in_date = '$currentDate' 
            AND payment_status = 'unpaid'";

    // Execute query
    if (mysqli_query($conn, $sql)) {
        echo "Pending reservations cancelled successfully.";
    } else {
        echo "Error: " . mysqli_error($conn);
    }
}

// Close connection
mysqli_close($conn);
?>
