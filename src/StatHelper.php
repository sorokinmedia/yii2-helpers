<?php

namespace sorokinmedia\helpers;

/**
 * Class StatHelper
 * @package sorokinmedia\helpers
 */
class StatHelper
{
    /**
     * приводит значение массива к целым числам (int)
     * @param $array
     * @return mixed
     */
    public static function makeInt(array $array): array
    {
        foreach ($array as $key => $value) {
            $array[$key] = (int)$value;
        }
        return $array;
    }

    /**
     * приводит значения массива к дробным числам (float)
     * @param $array
     * @return mixed
     */
    public static function makeFloat(array $array): array
    {
        foreach ($array as $key => $value) {
            $array[$key] = (float)$value;
        }
        return $array;
    }

    /**
     * округляет все значения массив до 2 знаков после запятой
     * @param $array
     * @return mixed
     */
    public static function makeRound(array $array): array
    {
        foreach ($array as $key => $value) {
            $array[$key] = round($value, 2);
        }
        return $array;
    }

    /**
     * минуты из секунд
     * @param $array
     * @return mixed
     */
    public static function makeMinutes(array $array): array
    {
        foreach ($array as $key => $value) {
            $minutes = floor($value / 60);
            $seconds = $value % 60;
            $array[$key] = (float)($minutes . '.' . $seconds);
        }
        return $array;
    }

    /**
     * формирует массив дат заданного интервала
     * @param string $from дата от, любой формат
     * @param string $to дата до, любой формат
     * @param string $step шаг
     * @param string $output_format формат даты на выходе
     * @return array
     */
    public static function date_range(string $from, string $to, string $step = '+1 day', string $output_format = 'd-m-Y'): array
    {
        $dates = [];
        $date_from = strtotime($from);
        $date_to = strtotime($to);
        while ($date_from <= $date_to) {
            $dates[] = date($output_format, $date_from);
            $date_from = strtotime($step, $date_from);
        }
        return $dates;
    }
}
