<?php
session_start();


// Calculate nights
function calculateStayNights($checkIn, $checkOut)
{
    if (!empty($checkIn) && !empty($checkOut)) 
    {
        $checkInDate = new DateTime($checkIn);
        $checkOutDate = new DateTime($checkOut);
        return $checkInDate->diff($checkOutDate)->days;
    }
    return 0;
}

// Create DB connection
function connectDB()
{
    $conn = mysqli_connect("localhost", "root", "", "hotel_reservation_system");
    if (!$conn) {
        throw new Exception("DB connection failed.");
    }
    return $conn;
}

// Check if customer exists
function getCustomerID($conn, $email)
{
    $query = "SELECT customer_id FROM customer WHERE email='$email'";
    $result = mysqli_query($conn, $query);
    if ($row = mysqli_fetch_assoc($result)) {
        return $row['customer_id'];
    }
    return null;
}

// Insert reservation
function insertReservation($conn, $customerID, $email, $checkIn, $checkOut, $occupants)
{
    $query = "INSERT INTO reservations (customer_id, customer_email, check_in_date, check_out_date, occupants, status)
              VALUES ('$customerID', '$email', '$checkIn', '$checkOut', '$occupants', 'pending')";
    if (mysqli_query($conn, $query)) {
        return mysqli_insert_id($conn);
    }
    throw new Exception("Error inserting reservation: " . mysqli_error($conn));
}

// Insert room details
function insertRoomDetails($conn, $customerID, $roomNo, $roomType, $capacity)
{
    $query = "INSERT INTO rooms (customer_id, room_no, room_type, is_available, capacity)
              VALUES ('$customerID', '$roomNo', '$roomType', 0, '$capacity')";
    if (!mysqli_query($conn, $query)) {
        throw new Exception("Error inserting room: " . mysqli_error($conn));
    }
}

// Insert credit card and confirm reservation
function insertCardAndConfirm($conn, $customerID, $reservationID, $cardNo, $expiry, $cvv)
{
    $query = "INSERT INTO credit_cards (customer_id, card_number, expiry, cvv)
              VALUES ('$customerID', '$cardNo', '$expiry', '$cvv')";
    if (mysqli_query($conn, $query)) {
        $update = "UPDATE reservations SET status='confirmed' WHERE reservation_id='$reservationID'";
        mysqli_query($conn, $update);
    } else {
        throw new Exception("Error inserting card: " . mysqli_error($conn));
    }
}

?>
