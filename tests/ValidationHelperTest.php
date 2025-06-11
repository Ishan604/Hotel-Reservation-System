<?php

use PHPUnit\Framework\TestCase;

require_once __DIR__ . '/../src/ValidationHelper.php';

class ValidationHelperTest extends TestCase
{
    public function testValidateCustomerInputsValid()
    {
        // Test valid inputs: name, email, and check-in date
        $result = validateCustomerInputs('John Doe', 'john@example.com', '2023-06-10');

        // Assert that the result is null (no errors)
        $this->assertNull($result);
    }

    public function testValidateCustomerInputsInvalidName()
    {
        // Test empty name
        $result = validateCustomerInputs('', 'john@example.com', '2023-06-10');

        // Assert that the result contains the name error message
        $this->assertEquals("âŒ Please enter valid name and email.", $result);
    }

    public function testValidateCustomerInputsInvalidEmail()
    {
        // Test invalid email format
        $result = validateCustomerInputs('John Doe', 'invalid-email', '2023-06-10');

        // Assert that the result contains the email error message
        $this->assertEquals("âŒ Please enter valid name and email.", $result);
    }

    public function testValidateCustomerInputsInvalidCheckinDate()
    {
        // Test invalid check-in date format
        $result = validateCustomerInputs('John Doe', 'john@example.com', '2023/06/10');

        // Assert that the result contains the check-in date error message
        $this->assertEquals("âŒ Invalid check-in date format.", $result);
    }

    public function testValidateCustomerInputsEmptyCheckinDate()
    {
        // Test empty check-in date
        $result = validateCustomerInputs('John Doe', 'john@example.com', '');

        // Assert that the result contains the check-in date error message
        $this->assertEquals("âŒ Invalid check-in date format.", $result);
    }
}
