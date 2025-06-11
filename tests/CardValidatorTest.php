<?php

use PHPUnit\Framework\TestCase;

require_once __DIR__ . '/../src/cardInputValidation.php';  // Corrected path

class CardValidatorTest extends TestCase
{
    public function testValidCardInput()
    {
        $result = validateCardInput('1234567812345678', '12/25', '123');
        $this->assertNull($result);
    }

    public function testInvalidCardNumber()
    {
        $result = validateCardInput('12345678', '12/25', '123');
        $this->assertEquals("Invalid card number. Must be 16 digits.", $result);
    }

    public function testInvalidExpiryFormat()
    {
        $result = validateCardInput('1234567812345678', '2025-12', '123');
        $this->assertEquals("Invalid expiry date format. Use MM/YY.", $result);
    }

    public function testInvalidCVVTooShort()
    {
        $result = validateCardInput('1234567812345678', '12/25', '12');
        $this->assertEquals("Invalid CVV. Must be 3 or 4 digits.", $result);
    }

    public function testInvalidCVVTooLong()
    {
        $result = validateCardInput('1234567812345678', '12/25', '12345');
        $this->assertEquals("Invalid CVV. Must be 3 or 4 digits.", $result);
    }
}
