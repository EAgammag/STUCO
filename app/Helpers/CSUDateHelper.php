<?php

namespace App\Helpers;

use Carbon\Carbon;

/**
 * CSU Date Helper
 *
 * Helper class for calculating "class days" as specified in the CSU Student Manual.
 * Class days exclude weekends and university holidays.
 */
class CSUDateHelper
{
    /**
     * University Holidays (to be configured per academic year)
     */
    private static array $universityHolidays = [
        // Example holidays - these should be loaded from database/config
        '2026-04-03', // Maundy Thursday
        '2026-04-04', // Good Friday
        '2026-05-01', // Labor Day
        '2026-06-12', // Independence Day
        '2026-08-21', // Ninoy Aquino Day
        '2026-11-30', // Bonifacio Day
        '2026-12-25', // Christmas Day
        '2026-12-30', // Rizal Day
        '2026-12-31', // New Year's Eve
    ];

    /**
     * Add class days to a given date.
     *
     * Class days exclude weekends (Saturday & Sunday) and university holidays.
     *
     * @param  Carbon|string  $startDate  Starting date
     * @param  int  $classDays  Number of class days to add
     * @return Carbon Resulting date after adding class days
     */
    public static function addClassDays($startDate, int $classDays): Carbon
    {
        $date = Carbon::parse($startDate);
        $daysAdded = 0;

        while ($daysAdded < $classDays) {
            $date->addDay();

            // Skip weekends and holidays
            if (! $date->isWeekend() && ! self::isUniversityHoliday($date)) {
                $daysAdded++;
            }
        }

        return $date;
    }

    /**
     * Calculate the number of class days between two dates.
     *
     * @param  Carbon|string  $startDate  Start date
     * @param  Carbon|string  $endDate  End date
     * @return int Number of class days between dates
     */
    public static function classDaysBetween($startDate, $endDate): int
    {
        $start = Carbon::parse($startDate);
        $end = Carbon::parse($endDate);
        $classDays = 0;

        $current = $start->copy();
        while ($current->lte($end)) {
            if (! $current->isWeekend() && ! self::isUniversityHoliday($current)) {
                $classDays++;
            }
            $current->addDay();
        }

        return $classDays;
    }

    /**
     * Check if a date is a university holiday.
     *
     * @param  Carbon  $date  Date to check
     * @return bool True if it's a university holiday
     */
    public static function isUniversityHoliday(Carbon $date): bool
    {
        return in_array($date->format('Y-m-d'), self::$universityHolidays);
    }

    /**
     * Calculate answer deadline (5 class days from charge filing per CSU G.6).
     *
     * @param  Carbon|string  $chargeFiledDate  Date when charge was filed
     * @return Carbon Answer deadline date
     */
    public static function calculateAnswerDeadline($chargeFiledDate): Carbon
    {
        return self::addClassDays($chargeFiledDate, 5);
    }

    /**
     * Calculate decision deadline (15 class days after final submission per CSU G.13).
     *
     * @param  Carbon|string  $finalSubmissionDate  Date of final submission
     * @return Carbon Decision deadline date
     */
    public static function calculateDecisionDeadline($finalSubmissionDate): Carbon
    {
        return self::addClassDays($finalSubmissionDate, 15);
    }

    /**
     * Calculate appeal deadline (10 class days from decision receipt per CSU G.15).
     *
     * @param  Carbon|string  $decisionDate  Date of decision
     * @return Carbon Appeal deadline date
     */
    public static function calculateAppealDeadline($decisionDate): Carbon
    {
        return self::addClassDays($decisionDate, 10);
    }

    /**
     * Check if a deadline has passed.
     *
     * @param  Carbon|string|null  $deadline  Deadline date
     * @return bool True if deadline has passed, false otherwise
     */
    public static function isDeadlinePassed($deadline): bool
    {
        if (! $deadline) {
            return false;
        }

        return Carbon::parse($deadline)->isPast();
    }

    /**
     * Get countdown message for a deadline.
     *
     * @param  Carbon|string|null  $deadline  Deadline date
     * @return string Human-readable countdown message
     */
    public static function getDeadlineCountdown($deadline): string
    {
        if (! $deadline) {
            return 'No deadline set';
        }

        $deadline = Carbon::parse($deadline);
        $now = now();

        if ($deadline->isPast()) {
            $classDays = self::classDaysBetween($deadline, $now);

            return "Overdue by {$classDays} class days";
        }

        $classDays = self::classDaysBetween($now, $deadline);

        return "{$classDays} class days remaining";
    }

    /**
     * Set university holidays (usually called at the start of academic year).
     *
     * @param  array  $holidays  Array of holiday dates (Y-m-d format)
     */
    public static function setUniversityHolidays(array $holidays): void
    {
        self::$universityHolidays = $holidays;
    }

    /**
     * Get all configured university holidays.
     *
     * @return array Array of holiday dates
     */
    public static function getUniversityHolidays(): array
    {
        return self::$universityHolidays;
    }
}
