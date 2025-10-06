<?php

declare(strict_types=1);

namespace app\modules\core\dictionaries;

class TimeDurationDictionary
{
    /**
     * @description 1 секунда
     */
    public const int ONE_SECOND = 1;

    /**
     * @description 5 секунд
     */
    public const int FIVE_SECONDS = 5;

    /**
     * @description 15 секунд
     */
    public const int FIFTEEN_SECONDS = 15;

    /**
     * @description Одна минута (в секундах)
     */
    public const int ONE_MINUTE = 60;

    /**
     * @description Две минуты (в секундах)
     */
    public const int TWO_MINUTES = 2 * 60;

    /**
     * @description Пять минут (в секундах)
     */
    public const int FIVE_MINUTES = 5 * 60;

    /**
     * @description Десять минут (в секундах)
     */
    public const int TEN_MINUTES = 10 * 60;

    /**
     * @description Пятнадцать минут (в секундах)
     */
    public const int FIFTEEN_MINUTES = 15 * 60;

    /**
     * @description Двадцать пять минут (в секундах)
     */
    public const int TWENTY_FIVE_MINUTES = 25 * 60;

    /**
     * @description Тридцать минут (в секундах)
     */
    public const int THIRTY_MINUTES = 30 * 60;

    /**
     * @description Один час (в секундах)
     */
    public const int ONE_HOUR = 60 * 60;

    /**
     * @description Два часа (в секундах)
     */
    public const int TWO_HOURS = 2 * 60 * 60;

    /**
     * @description Три часа (в секундах)
     */
    public const int THREE_HOURS = 3 * 60 * 60;

    /**
     * @description Один день (в секундах)
     */
    public const int ONE_DAY = 60 * 60 * 24;

    /**
     * @description Две недели (в секундах)
     */
    public const int TWO_WEEKS = 14 * 24 * 60 * 60;

    /**
     * @description Один месяц (в секундах)
     */
    public const int ONE_MONTH = 30 * 60 * 60 * 24;
}
