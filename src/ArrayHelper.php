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
}