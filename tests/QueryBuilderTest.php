<?php

use PHPUnit\Framework\TestCase;

require_once __DIR__ . '/../src/QueryBuilder.php';

class QueryBuilderTest extends TestCase
{
    public function testBuildReservationsQueryWithoutBranch()
    {
        // Mock the $conn object
        $conn = $this->createMock(mysqli::class);

        // Call the function without a branch parameter
        $sql = buildReservationsQuery($conn);

        // Assert that the generated query is correct without the branch condition
        $expectedSql = "SELECT reservation_id, customer_id, customer_email, check_in_date, check_out_date, occupants, status, payment_status, room_id, room_type, created_at, actual_check_in_time, actual_check_out_time, expected_check_in_time, expected_check_out_time, Branch
                        FROM reservations
                        WHERE 1=1
                        ORDER BY check_in_date DESC";
        $this->assertEquals($expectedSql, $sql);
    }

    public function testBuildReservationsQueryWithBranch()
    {
        // Mock the $conn object
        $conn = $this->createMock(mysqli::class);

        // Mock the real_escape_string method
        $conn->method('real_escape_string')->willReturn('Colombo');

        // Call the function with a branch parameter
        $sql = buildReservationsQuery($conn, 'Colombo');

        // Assert that the generated query includes the branch condition
        $expectedSql = "SELECT reservation_id, customer_id, customer_email, check_in_date, check_out_date, occupants, status, payment_status, room_id, room_type, created_at, actual_check_in_time, actual_check_out_time, expected_check_in_time, expected_check_out_time, Branch
                        FROM reservations
                        WHERE 1=1 AND Branch = 'Colombo'
                        ORDER BY check_in_date DESC";
        $this->assertEquals($expectedSql, $sql);
    }
}
