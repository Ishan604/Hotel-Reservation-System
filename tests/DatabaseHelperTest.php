<?php

use PHPUnit\Framework\TestCase;

require_once __DIR__ . '/../src/DatabaseHelper.php';

class DatabaseHelperTest extends TestCase
{
    // Test successful user deletion
    public function testDeleteUserByIdSuccess()
    {
        // Mock the $conn object
        $conn = $this->createMock(mysqli::class);

        // Mock the prepare method to return a valid statement object
        $stmt = $this->createMock(mysqli_stmt::class);
        $conn->method('prepare')->willReturn($stmt);

        // Mock the bind_param method
        $stmt->method('bind_param')->willReturn(true);

        // Mock the execute method to return true (indicating success)
        $stmt->method('execute')->willReturn(true);

        // Call the function with a user_id
        $result = deleteUserById($conn, 1);

        // Assert that the result is true (successful deletion)
        $this->assertTrue($result);
    }

    // Test failure when prepare method fails
    public function testDeleteUserByIdPrepareFail()
    {
        // Mock the $conn object
        $conn = $this->createMock(mysqli::class);

        // Mock the prepare method to return false
        $conn->method('prepare')->willReturn(false);

        // Call the function with a user_id
        $result = deleteUserById($conn, 1);

        // Assert that the result is the error message from prepare
        $this->assertStringContainsString('Prepare failed', $result);
    }

    // Test failure when execute method fails
    public function testDeleteUserByIdExecuteFail()
    {
        // Mock the $conn object
        $conn = $this->createMock(mysqli::class);

        // Mock the prepare method to return a valid statement object
        $stmt = $this->createMock(mysqli_stmt::class);
        $conn->method('prepare')->willReturn($stmt);

        // Mock the bind_param method
        $stmt->method('bind_param')->willReturn(true);

        // Mock the execute method to return false (indicating failure)
        $stmt->method('execute')->willReturn(false);

        // Mock the error property to simulate the error message
        $stmt->method('error')->willReturn('Error executing query');

        // Call the function with a user_id
        $result = deleteUserById($conn, 1);

        // Assert that the result contains the error message from execute
        $this->assertStringContainsString('Delete failed', $result);
    }
}
