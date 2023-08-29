<?php

namespace App\Services;

use DateTime;
use DateInterval;
use App\Exceptions\InvalidSubmitDateException;
use App\Exceptions\InvalidTurnaroundTimeException;

/**
 * Class DueDateCalculatorService
 * @package App\Services
 */
class DueDateCalculatorService
{
    // Working hours are from 9AM to 5PM by default, must be whole hours (integer)
    const WORKING_HOURS_START = 9;
    const WORKING_HOURS_END = 17;
    const WORKING_HOURS_PER_DAY = self::WORKING_HOURS_END - self::WORKING_HOURS_START;

    /**
     * Calculate due date based on submit date and turnaround time.
     * Time is rounded to minutes.
     * @param DateTime $submitDate
     * @param float $turnaroundTime
     * @return DateTime
     * @throws \Exception
     */
    public function calculateDueDate(DateTime $submitDate, float $turnaroundTime): DateTime
    {
        if (!$this->isWorkingTime($submitDate)) {
            throw new InvalidSubmitDateException('Invalid submit date. It should be within working hours from Monday to Friday.');
        };

        if ($turnaroundTime <= 0) {
            throw new InvalidTurnaroundTimeException('Invalid turnaround time. It should be greater than zero.');
        }

        // round submitDate to minutes
        $submitDate = $this->floorMinutes($submitDate);

        $totalDays = intdiv((int)$turnaroundTime, self::WORKING_HOURS_PER_DAY);
        $remainingHours = ((int)$turnaroundTime) % self::WORKING_HOURS_PER_DAY;

        // Add full days first
        for ($i = 0; $i < $totalDays; $i++) {
            $submitDate->add(new DateInterval('P1D'));
            $this->skipWeekend($submitDate);
        }

        // Add remaining full hours
        $submitDate->add(new DateInterval('PT' . floor($remainingHours) . 'H'));

        // If time exceeds working hours, adjust it
        $submitDate = $this->adjustTime($submitDate);

        // Add remaining fractional hours
        $fractionalHours = $turnaroundTime - floor($turnaroundTime);
        $fractionalMinutes = floor($fractionalHours * 60);
        $submitDate->add(new DateInterval('PT' . $fractionalMinutes . 'M'));

        // If time exceeds working hours again, adjust it
        $submitDate = $this->adjustTime($submitDate);

        return $submitDate;
    }

    /**
     * @param DateTime $submitDate
     * @return bool
     * @throws \Exception
     */
    private function isWorkingTime(DateTime $submitDate)
    {
        if ($submitDate->format('N') > 5 ||
            $submitDate->format('H') < self::WORKING_HOURS_START ||
            $submitDate->format('H') >= self::WORKING_HOURS_END) {
            return false;
        }
        return true;
    }

    /**
     * Skip to next Monday if the date is on weekend.
     * @param DateTime $date
     * @return DateTime
     */
    private function skipWeekend(DateTime $date)
    {
        $dow = $date->format('N'); // 1..7 Mon..Sun
        if ($dow > 5) {
            $date->add(new DateInterval('P' . (8 - $dow) . 'D'));
        }
        return $date;
    }

    /**
     * Adjust time if it exceeds working hours.
     * @param DateTime $date
     * @return DateTime
     */
    private function adjustTime(DateTime $date)
    {
        $currentMinutes = ($date->format('H') * 60) + $date->format('i');
        $endOfDayMinutes = self::WORKING_HOURS_END * 60;
    
        if ($currentMinutes > $endOfDayMinutes) {
            $extraMinutes = $currentMinutes - $endOfDayMinutes;
            $date->sub(new DateInterval('PT' . $extraMinutes . 'M'));
            $date->add(new DateInterval('P1D'));
            $date = $this->skipWeekend($date);
            $date->setTime(self::WORKING_HOURS_START, 0);  // Set time to the start of the next working day
            $date->add(new DateInterval('PT' . $extraMinutes . 'M'));
        }
        return $date;
    }

    /**
     * Zero out sub-minute part of the date.
     * @param DateTime $date
     * @return DateTime
     */
    private function floorMinutes(DateTime $date)
    {
        $date->setTime($date->format('H'), $date->format('i'), 0);
        return $date;
    }
}
