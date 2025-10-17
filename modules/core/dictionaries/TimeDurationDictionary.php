<?php

declare(strict_types=1);

namespace app\modules\core\dictionaries;

/**
 * Dictionary of common time durations in seconds.
 *
 * Provides constants for frequently used time intervals and a method to get a human-readable description for each duration.
 */
class TimeDurationDictionary
{
    /**
     * @var int 1 second
     */
    public const ONE_SECOND = 1;

    /**
     * @var int 5 seconds
     */
    public const FIVE_SECONDS = 5;

    /**
     * @var int 15 seconds
     */
    public const FIFTEEN_SECONDS = 15;

    /**
     * @var int 1 minute (in seconds)
     */
    public const ONE_MINUTE = 60;

    /**
     * @var int 2 minutes (in seconds)
     */
    public const TWO_MINUTES = 2 * 60;

    /**
     * @var int 5 minutes (in seconds)
     */
    public const FIVE_MINUTES = 5 * 60;

    /**
     * @var int 10 minutes (in seconds)
     */
    public const TEN_MINUTES = 10 * 60;

    /**
     * @var int 15 minutes (in seconds)
     */
    public const FIFTEEN_MINUTES = 15 * 60;

    /**
     * @var int 25 minutes (in seconds)
     */
    public const TWENTY_FIVE_MINUTES = 25 * 60;

    /**
     * @var int 30 minutes (in seconds)
     */
    public const THIRTY_MINUTES = 30 * 60;

    /**
     * @var int 1 hour (in seconds)
     */
    public const ONE_HOUR = 60 * 60;

    /**
     * @var int 2 hours (in seconds)
     */
    public const TWO_HOURS = 2 * 60 * 60;

    /**
     * @var int 3 hours (in seconds)
     */
    public const THREE_HOURS = 3 * 60 * 60;

    /**
     * @var int 1 day (in seconds)
     */
    public const ONE_DAY = 24 * 60 * 60;

    /**
     * @var int 2 weeks (in seconds)
     */
    public const TWO_WEEKS = 14 * 24 * 60 * 60;

    /**
     * @var int 1 month (in seconds, approx.)
     */
    public const ONE_MONTH = 30 * 24 * 60 * 60;

    /**
     * Returns a human-readable description for the given duration in seconds.
     *
     * @param int $seconds duration in seconds
     *
     * @return string description of the duration
     */
    public static function getDescription(int $seconds): string
    {
        $map = [
            self::ONE_SECOND => '1 second',
            self::FIVE_SECONDS => '5 seconds',
            self::FIFTEEN_SECONDS => '15 seconds',
            self::ONE_MINUTE => '1 minute',
            self::TWO_MINUTES => '2 minutes',
            self::FIVE_MINUTES => '5 minutes',
            self::TEN_MINUTES => '10 minutes',
            self::FIFTEEN_MINUTES => '15 minutes',
            self::TWENTY_FIVE_MINUTES => '25 minutes',
            self::THIRTY_MINUTES => '30 minutes',
            self::ONE_HOUR => '1 hour',
            self::TWO_HOURS => '2 hours',
            self::THREE_HOURS => '3 hours',
            self::ONE_DAY => '1 day',
            self::TWO_WEEKS => '2 weeks',
            self::ONE_MONTH => '1 month',
        ];

        return $map[$seconds] ?? $seconds . ' seconds';
    }
}
