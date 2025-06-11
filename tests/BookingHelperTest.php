<?php

use PHPUnit\Framework\TestCase;

require_once __DIR__ . '/../src/BookingHelper.php';

class BookingHelperTest extends TestCase
{
    public function testCalculateTotalCostValidInput()
    {
        // Test valid inputs: rooms = 3, nights = 5, discounted_rate = 4000
        $rooms = 3;
        $nights = 5;
        $discounted_rate = 4000;
        
        // Calculate the expected total cost
        $expectedCost = 3 * 5 * 4000; // 60000

        // Call the function
        $result = calculateTotalCost($rooms, $nights, $discounted_rate);

        // Assert that the result is equal to the expected total cost
        $this->assertEquals($expectedCost, $result);
    }

    public function testCalculateTotalCostZeroRooms()
    {
        // Test with 0 rooms (should return 0)
        $rooms = 0;
        $nights = 5;
        $discounted_rate = 4000;

        // The total cost should be 0
        $expectedCost = 0;

        // Call the function
        $result = calculateTotalCost($rooms, $nights, $discounted_rate);

        // Assert that the result is 0
        $this->assertEquals($expectedCost, $result);
    }

    public function testCalculateTotalCostZeroNights()
    {
        // Test with 0 nights (should return 0)
        $rooms = 3;
        $nights = 0;
        $discounted_rate = 4000;

        // The total cost should be 0
        $expectedCost = 0;

        // Call the function
        $result = calculateTotalCost($rooms, $nights, $discounted_rate);

        // Assert that the result is 0
        $this->assertEquals($expectedCost, $result);
    }

    public function testCalculateTotalCostZeroDiscountedRate()
    {
        // Test with 0 discounted_rate (should return 0)
        $rooms = 3;
        $nights = 5;
        $discounted_rate = 0;

        // The total cost should be 0
        $expectedCost = 0;

        // Call the function
        $result = calculateTotalCost($rooms, $nights, $discounted_rate);

        // Assert that the result is 0
        $this->assertEquals($expectedCost, $result);
    }

    public function testCalculateTotalCostNegativeValues()
    {
        // Test with negative values for rooms, nights, or discounted_rate (should return a negative result)
        $rooms = -3;
        $nights = 5;
        $discounted_rate = 4000;

        // The total cost should be negative
        $expectedCost = -3 * 5 * 4000; // -60000

        // Call the function
        $result = calculateTotalCost($rooms, $nights, $discounted_rate);

        // Assert that the result is equal to the expected total cost
        $this->assertEquals($expectedCost, $result);
    }
}
