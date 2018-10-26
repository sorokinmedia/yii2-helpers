<?php
namespace sorokinmedia\helpers;

/**
 * Class ArrayHelper
 * @package sorokinmedia\helpers
 * 
 * работа с массивами
 */
class ArrayHelper
{
    /**
     * конвертит массив key=>value в массив объектов {'id', 'name'}
     * @param array $array
     * @return array
     */
    public static function convertArrayToArrayOfObject(array $array) : array
    {
        $extended_array = [];
        foreach ($array as $key => $value){
            $extended_array[] = [
                'id' => $key,
                'name' => $value,
            ];
        }
        return $extended_array;
    }

    /**
     * формирует список временных интервалов для выбора в зарплатном модуле
     * @return array
     */
    public static function costHourValues() : array
    {
        return ['0.25' => 0.25] + array_combine(range(0.5,24,0.5),range(0.5,24,0.5));
    }
}