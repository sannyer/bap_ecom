<?php

namespace Tests\Unit;

use App\Services\DueDateCalculatorService;
use DateTime;
use PHPUnit\Framework\TestCase;

class DueDateCalculatorServiceTest extends TestCase
{
    public function testCalculateDueDate()
    {
        $calculator = new DueDateCalculatorService();
    
        // Test valid input with integer turnaround time ending just with the day
        $submitDate = new DateTime('2023-08-28 13:15');
        $dueDate = $calculator->calculateDueDate($submitDate, 3.75);
        $this->assertEquals('2023-08-28 17:00:00', $dueDate->format('Y-m-d H:i:s'));

        // Test valid input with integer turnaround time just overflowing to next day
        $submitDate = new DateTime('2023-08-28 13:15');
        $dueDate = $calculator->calculateDueDate($submitDate, 3.77);
        $this->assertEquals('2023-08-29 09:01:00', $dueDate->format('Y-m-d H:i:s'));

        $submitDate = new DateTime('2023-08-28 13:15');
        $dueDate = $calculator->calculateDueDate($submitDate, 12);
        $this->assertEquals('2023-08-30 09:15:00', $dueDate->format('Y-m-d H:i:s'));
    
        // Test valid input with decimal turnaround time
        $submitDate = new DateTime('2023-08-28 13:15');
        $dueDate = $calculator->calculateDueDate($submitDate, 27.7);
        $this->assertEquals('2023-08-31 16:56:00', $dueDate->format('Y-m-d H:i:s'));
    
        // Test valid input with decimal turnaround time
        $submitDate = new DateTime('2023-08-28 13:15');
        $dueDate = $calculator->calculateDueDate($submitDate, 27.8);
        $this->assertEquals('2023-09-01 09:03:00', $dueDate->format('Y-m-d H:i:s'));
    
        // Test valid input with decimal turnaround time
        $submitDate = new DateTime('2023-08-28 13:15');
        $dueDate = $calculator->calculateDueDate($submitDate, 35.7);
        $this->assertEquals('2023-09-01 16:57:00', $dueDate->format('Y-m-d H:i:s'));
    
        // Test valid input with decimal turnaround time
        $submitDate = new DateTime('2023-08-28 13:15');
        $dueDate = $calculator->calculateDueDate($submitDate, 35.8);
        $this->assertEquals('2023-09-04 09:02:00', $dueDate->format('Y-m-d H:i:s'));
    
        // Test invalid input with negative turnaround time
        $submitDate = new DateTime('2023-08-28 13:15');
        $this->expectException(\App\Services\InvalidTurnaroundTimeException::class);
        $calculator->calculateDueDate($submitDate, -1);
    
        // Test invalid input with zero turnaround time
        $submitDate = new DateTime('2023-08-28 13:15');
        $this->expectException(\App\Services\InvalidTurnaroundTimeException::class);
        $calculator->calculateDueDate($submitDate, 0);
    
        // Test invalid input with non-numeric turnaround time
        $submitDate = new DateTime('2023-08-28 13:15');
        $this->expectException(\App\Services\InvalidTurnaroundTimeException::class);
        $calculator->calculateDueDate($submitDate, 'asd');
    }
}