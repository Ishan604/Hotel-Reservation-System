<?php

function updateReservationAndRoom($conn, $reservation_id, $email, $check_in, $check_out, $guests, $customerid) {
    $update_query = "UPDATE reservations SET 
                        check_in_date='$check_in', 
                        check_out_date='$check_out', 
                        occupants='$guests' 
                    WHERE reservation_id='$reservation_id' AND customer_email='$email'";

    $update_rooms = "UPDATE rooms SET capacity='$guests' WHERE customer_id='$customerid'";

    $r1 = mysqli_query($conn, $update_query);
    $r2 = mysqli_query($conn, $update_rooms);
    return ($r1 && $r2);
}
