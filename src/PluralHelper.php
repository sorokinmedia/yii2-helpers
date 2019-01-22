<?php
namespace sorokinmedia\helpers;

/**
 * Class PluralHelper
 * @package sorokinmedia\helpers
 *
 * конвертер цифры в нужное слово с учетом кол-ва
 */
class PluralHelper
{
    private static $_days = ['день', 'дня', 'дней'];
    private static $_months = ['месяц', 'месяца', 'месяцев'];
    private static $_years = ['год', 'года', 'лет'];
    private static $_minutes = ['минуту', 'минуты', 'минут'];
    private static $_seconds = ['секунду', 'секунды', 'секунд'];
    private static $_hours = ['час', 'часа', 'часов'];
    private static $_text = ['текст', 'текста', 'текстов'];
    private static $_rubl = ['рубль', 'рубля', 'рублей'];
    private static $_notification = ['уведомление', 'уведомления', 'уведомлений'];
    private static $_tests = ['раз', 'раза', 'раз'];
    private static $_tasks = ['задача', 'задачи', 'задач'];

    /**
     * конвертация цифры в нужное слово
     * @param int $n Число
     * @param string $type hours, days, minutes, seconds, rubl
     * @return string
     */
    public static function convert(int $n, string $type = 'hours') : string
    {
        $n = ceil($n);
        switch ($type) {
            case 'days':
                return self::$_days[self::plural_type($n)];
            case 'months':
                return self::$_months[self::plural_type($n)];
            case 'years':
                return self::$_years[self::plural_type($n)];
            case 'hours':
                return self::$_hours[self::plural_type($n)];
            case 'minutes':
                return self::$_minutes[self::plural_type($n)];
            case 'seconds':
                return self::$_seconds[self::plural_type($n)];
            case 'text':
                return self::$_text[self::plural_type($n)];
            case 'notification':
                return self::$_notification[self::plural_type($n)];
            case 'tests':
                return self::$_tests[self::plural_type($n)];
            case 'tasks':
                return self::$_tasks[self::plural_type($n)];
            case 'rubl':
            default:
                return self::$_rubl[self::plural_type($n)];
        }
    }

    /**
     * получает нужный ключ массива по указанному числу
     * @param int $n
     * @return int
     */
    public static function plural_type(int $n) : int
    {
        return ($n%10==1 && $n%100!=11 ? 0 : ($n%10>=2 && $n%10<=4 && ($n%100<10 || $n%100>=20) ? 1 : 2));
    }
}
