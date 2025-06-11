<?php

use PHPUnit\Framework\TestCase;

require_once __DIR__ . '/../src/DateTimeHelper.php';

class DateTimeHelperTest extends TestCase
{
    public function testGetCurrentDateTimeStructure()
    {
        $result = getCurrentDateTime();

        $this->assertIsArray($result);
        $this->assertArrayHasKey('date', $result);
        $this->assertArrayHasKey('time', $result);
    }

    public function testGetCurrentDateFormat()
    {
        $result = getCurrentDateTime();
        $this->assertMatchesRegularExpression('/^\d{4}-\d{2}-\d{2}$/', $result['date']);
    }

    public function testGetCurrentTimeFormat()
    {
        $result = getCurrentDateTime();
        $this->assertMatchesRegularExpression('/^\d{2}:\d{2}:\d{2}$/', $result['time']);
    }
}
