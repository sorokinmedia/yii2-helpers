<?php

namespace sorokinmedia\helpers;

use DateTime;
use DateTimeZone;
use Exception;
use IntlTimeZone;
use yii\helpers\ArrayHelper;

/**
 * Class DateHelper
 * @package sorokinmedia\helpers
 *
 * работа с датами
 */
class DateHelper
{
    public const TIME_SECOND_ONE = 1;
    public const TIME_MINUTE_ONE = self::TIME_SECOND_ONE * 60;
    public const TIME_HOUR_ONE = self::TIME_MINUTE_ONE * 60;
    public const TIME_HOUR_TWELVE = self::TIME_HOUR_ONE * 12;
    public const TIME_DAY_ONE = self::TIME_HOUR_ONE * 24;
    public const TIME_DAY_FIFTEEN = self::TIME_DAY_ONE * 15;
    public const TIME_DAY_THIRTY = self::TIME_DAY_ONE * 30;
    public const TIME_YEAR_ONE = self::TIME_DAY_ONE * 365;
    public const WEEKEND_DAYS = [5, 6, 7];

    /**
     * получает кол-во часов, учитывая выходные
     * @param int $extend_time
     * @return int
     */
    public static function getExtendTime(int $extend_time): int
    {
        if (in_array(date('w'), self::WEEKEND_DAYS, true)) { // если выходные
            $extend_time *= 3;
        }
        return $extend_time;
    }

    /**
     * список временных зон
     * @param string $locale
     * @return array
     * @throws Exception
     */
    public static function getListOfTimezones(string $locale = ''): array
    {
        date_default_timezone_set('UTC');
        $identifiers = DateTimeZone::listIdentifiers();
        foreach ($identifiers as $i) {
            // create date time zone from identifier
            $dtz = new DateTimeZone($i);
            // create timezone from identifier
            $tz = IntlTimeZone::createTimeZone($i);
            // if IntlTimeZone is unaware of timezone ID, use identifier as name, else use localized name
            if ($i === 'UTC' || $tz->getID() === 'Etc/Unknown') {
                $name = $i;
            } else {
                $name = $tz->getDisplayName(false, 3, $locale);
            }
            // time offset
            $offset = $dtz->getOffset(new DateTime());
            $sign = ($offset < 0) ? '-' : '+';
            $tzs[] = [
                'code' => $i,
                'name' => $name . '(UTC' . $sign . date('H:i', abs($offset)) . ') ',
                'offset' => $offset,
            ];
        }
        ArrayHelper::multisort($tzs, ['offset', 'name']);
        return array_column($tzs, 'name', 'code');
    }

    /**
     * трансформирует секунды в текст (1 час 20 минут)
     * @param integer $time
     * @return string
     */
    public static function getLeftTime(int $time = null): string
    {
        $days = floor($time / self::TIME_DAY_ONE);
        $hours = floor(($time - ($days * self::TIME_DAY_ONE)) / self::TIME_HOUR_ONE);
        $sec = $time - ($hours * self::TIME_HOUR_ONE);
        $minutes = floor($sec / self::TIME_MINUTE_ONE);
        if ($days > 0) {
            $time_interval = $days . ' ' . PluralHelper::convert($days, 'days') . ' и ' . $hours . ' ' . PluralHelper::convert($hours, 'hours');
        } elseif ($hours > 0) {
            $time_interval = $hours . ' ' . PluralHelper::convert($hours, 'hours') . ' и ' . $minutes . ' ' . PluralHelper::convert($minutes, 'minutes');
        } else {
            $time_interval = $minutes . ' ' . PluralHelper::convert($minutes, 'minutes');
        }
        return $time_interval;
    }

    /**
     * получает отступ таймзоны от UTC в секундах
     * @param string $timezone
     * @return int
     * @throws Exception
     */
    public static function getTzOffset(string $timezone): int
    {
        $dateTimeZone = new DateTimeZone($timezone);
        $dateTimeZoneUtc = new DateTimeZone('Europe/London');
        $dateTime = new DateTime('now', $dateTimeZoneUtc);
        return $dateTimeZone->getOffset($dateTime);
    }

    /**
     * формирует список временных интервалов для селекта на форме
     * @return array
     */
    public static function hoursArray(): array
    {
        return ['0.25' => 0.25] + array_combine(range(0.5, 24, 0.5), range(0.5, 24, 0.5));
    }

    /**
     * первый и последний день месяца
     * @return array
     */
    public static function getStartEndMonth(): array
    {
        date_default_timezone_set('UTC');
        $begin = mktime(0, 0, 0, date('m'), 1, date('Y'));
        $end = mktime(23, 59, 59, date('m'), date('t'), date('Y'));
        return [
            'begin' => $begin,
            'end' => $end
        ];
    }

    /**
     * первый и последний день прошлого месяца
     * @return array
     */
    public static function getStartEndPrevMonth(): array
    {
        date_default_timezone_set('UTC');
        $begin = strtotime('first day of previous month midnight');
        $end = strtotime('first day of this month midnight');
        return [
            'begin' => $begin,
            'end' => $end
        ];
    }
}
