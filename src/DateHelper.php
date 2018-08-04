<?php
namespace sorokinmedia\helpers;

use yii\helpers\ArrayHelper;

/**
 * Class DateHelper
 * @package sorokinmedia\helpers
 *
 * работа с датами
 */
class DateHelper
{
    const TIME_ONE_MINUTE = 60;
    const TIME_ONE_HOUR = 3600;
    const TIME_12 = 43200;
    const TIME_24 = 86400;
    const TIME_DAY_15 = 1296000;
    const TIME_YEAR = 31536000;
    const WEEKEND_DAYS = [5,6,7];

    /**
     * получает кол-во часов, учитывая выходные
     * @param int $extend_time
     * @return int
     */
    public static function getExtendTime(int $extend_time) : int
    {
        if (in_array(date('w'), self::WEEKEND_DAYS)){ // если выходные
            $extend_time = $extend_time * 3;
        }
        return $extend_time;
    }

    /**
     * список временных зон
     * @param string $locale
     * @return array
     */
    public static function getListOfTimezones(string $locale = '') : array
    {
        date_default_timezone_set('UTC');
        $identifiers = \DateTimeZone::listIdentifiers();
        foreach($identifiers as $i) {
            // create date time zone from identifier
            $dtz = new \DateTimeZone($i);
            // create timezone from identifier
            $tz = \IntlTimeZone::createTimeZone($i);
            // if IntlTimeZone is unaware of timezone ID, use identifier as name, else use localized name
            if ($tz->getID() === 'Etc/Unknown' or $i === 'UTC') $name = $i;
            else $name =  $tz->getDisplayName(false, 3, $locale);
            // time offset
            $offset = $dtz->getOffset(new \DateTime());
            $sign   = ($offset < 0) ? '-' : '+';

            $tzs[] = [
                'code'   => $i,
                'name'   => $name . '(UTC' . $sign . date('H:i', abs($offset)) . ') ',
                'offset' => $offset,
            ];
        }
        ArrayHelper::multisort($tzs, ['offset', 'name']);
        // sort by offset
        //    usort($tzs, function($a, $b){
        //        if ($a['offset'] > $b['offset']) {
        //            return 1;
        //        }
        //        elseif ($a['offset'] < $b['offset']) {
        //            return -1;
        //        }
        //        elseif ($a['name'] > $b['name']) {
        //            return 1;
        //        }
        //        elseif ($a['name'] < $b['name']) {
        //            return -1;
        //        }
        //        return 0;
        //    });
        return array_column($tzs, 'name', 'code');
    }

    /**
     * трансформирует секунды в текст (1 час 20 минут)
     * @param integer $time
     * @return string
     */
    public static function getLeftTime(int $time = null)
    {
        $days = floor($time/self::TIME_24);
        $hours = floor(($time - ($days*self::TIME_24))/self::TIME_ONE_HOUR);
        $sec = $time - ($hours * self::TIME_ONE_HOUR3600);
        $mins = floor($sec/self::TIME_ONE_MINUTE);
        if ($days > 0) {
            $time_interval = $days . ' ' . PluralHelper::convert($days, 'days') . ' и ' . $hours . ' ' . PluralHelper::convert($hours, 'hours');
        } elseif ($hours > 0) {
            $time_interval = $hours . ' ' . PluralHelper::convert($hours, 'hours') . ' и ' . $mins . ' ' . PluralHelper::convert($mins, 'minutes');
        } else {
            $time_interval = $mins . ' ' . PluralHelper::convert($mins, 'minutes');
        }
        return $time_interval;
    }

    /**
     * получает отступ таймзоны от UTC в секундах
     * @param string $timezone
     * @return int
     */
    public static function getTzOffset(string $timezone) : int
    {
        $dateTimeZone = new \DateTimeZone($timezone);
        $dateTimeZoneUtc = new \DateTimeZone('Europe/London');
        $dateTime = new \DateTime("now", $dateTimeZoneUtc);
        return $dateTimeZone->getOffset($dateTime);
    }

    /**
     * формирует список временных интервалов для селекта на форме
     * @return array
     */
    public static function hoursArray() : array
    {
        return ['0.25' => 0.25] + array_combine(range(0.5,24,0.5),range(0.5,24,0.5));
    }

    /**
     * первый и последний день месяца
     * @return array
     */
    public static function getStartEndMonth() : array
    {
        date_default_timezone_set('UTC');
        $begin = mktime(0, 0, 0, date('m'), 1, date("Y"));
        $end = mktime(23, 59, 59, date('m'), date("t"), date("Y"));
        return [
            'begin' => $begin,
            'end' => $end
        ];
    }

    /**
     * первый и последний день прошлого месяца
     * @return array
     */
    public static function getStartEndPrevMonth() : array
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