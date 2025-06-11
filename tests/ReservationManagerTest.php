<?php

use PHPUnit\Framework\TestCase;

require_once __DIR__ . '/../src/ReservationManager.php';

class ReservationManagerTest extends TestCase
{
    private $conn;

    protected function setUp(): void
    {
        // Use in-memory SQLite for testing (mock alternative)
        $this->conn = new mysqli("localhost", "root", "", "hotel_reservation_system");

        // Ensure tables and data exist for test
        $this->conn->query("DELETE FROM reservations");
        $this->conn->query("DELETE FROM rooms");

        $this->conn->query("INSERT INTO reservations (reservation_id, customer_email, check_in_date, check_out_date, occupants) 
                            VALUES (1, 'test@example.com', '2025-06-01', '2025-06-05', 2)");

        $this->conn->query("INSERT INTO rooms (room_id, customer_id, room_type, capacity, room_no) 
                            VALUES (1, 101, 'Deluxe', 2, 'D12')");
    }

    public function testUpdateReservationAndRoomSuccess()
    {
        $result = updateReservationAndRoom(
            $this->conn,
            1,                       // reservation_id
            'test@example.com',     // email
            '2025-06-10',           // new check-in
            '2025-06-15',           // new check-out
            3,                      // new guests
            101                     // customer_id
        );

        $this->assertTrue($result, "Reservation and room update should succeed");

        $res = $this->conn->query("SELECT * FROM reservations WHERE reservation_id=1");
        $reservation = $res->fetch_assoc();

        $this->assertEquals('2025-06-10', $reservation['check_in_date']);
        $this->assertEquals('2025-06-15', $reservation['check_out_date']);
        $this->assertEquals(3, $reservation['occupants']);

        $roomRes = $this->conn->query("SELECT * FROM rooms WHERE customer_id=101");
        $room = $roomRes->fetch_assoc();

        $this->assertEquals(3, $room['capacity']);
    }
}
