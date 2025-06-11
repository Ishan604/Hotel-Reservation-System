<?php

use PHPUnit\Framework\TestCase;

require_once __DIR__ . '/../src/reservation_sub.php';  // Corrected path

class ReservationFunctionsTest extends TestCase
{
    public function testCalculateStayNightsReturnsCorrectDays()
    {
        $checkIn = '2025-06-10';
        $checkOut = '2025-06-15';
        $expectedNights = 5;

        $actualNights = calculateStayNights($checkIn, $checkOut);

        $this->assertEquals($expectedNights, $actualNights);
    }

    public function testCalculateStayNightsWithSameDay()
    {
        $this->assertEquals(0, calculateStayNights('2025-06-10', '2025-06-10'));
    }

    public function testCalculateStayNightsWithEmptyDates()
    {
        $this->assertEquals(0, calculateStayNights('', ''));
        $this->assertEquals(0, calculateStayNights('2025-06-10', ''));
        $this->assertEquals(0, calculateStayNights('', '2025-06-15'));
    }
}
