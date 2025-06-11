<?php

function buildReservationsQuery($conn, $branch = '') {
    $sql = "SELECT reservation_id, customer_id, customer_email, check_in_date, check_out_date, occupants, status, payment_status, room_id, room_type, created_at, actual_check_in_time, actual_check_out_time, expected_check_in_time, expected_check_out_time, Branch
            FROM reservations
            WHERE 1=1";

    if (!empty($branch)) {
        $branch = $conn->real_escape_string($branch);
        $sql .= " AND Branch = '$branch'";
    }

    $sql .= " ORDER BY check_in_date DESC";

    return $sql;
}
