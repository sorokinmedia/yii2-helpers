<?php

namespace sorokinmedia\helpers;

/**
 * Class TestHelper
 * @package sorokinmedia\helpers
 */
class TestHelper
{
    /**
     * приводит API ответ к нужному виду. для тестов API
     * @param string $response
     * @return string
     */
    public static function filterResponse(string $response): string
    {
        $response = str_replace(array('{', '}', '":'), array('[', ']', '"=>'), $response);
        return $response;
    }
}
