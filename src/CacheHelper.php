<?php

namespace sorokinmedia\helpers;

use Yii;

/**
 * Class CacheHelper
 * @package common\helpers
 *
 * набор методов для очистки кешей
 *
 * искать место, где происходит кеширование по названию ключа
 */
class CacheHelper
{
    public const CACHE_TIME_MINUTE = 60;
    public const CACHE_TIME_FIVE_MINUTES = 300;
    public const CACHE_TIME_HALF_HOUR = 1800;
    public const CACHE_TIME_HOUR = 3600;
    public const CACHE_TIME_TWELVE_HOURS = 43200;
    public const CACHE_TIME_DAY = 86400;
    public const CACHE_TIME_THIRTY_DAYS = 2592000;

    /**
     * метод для очистки кеша по ключу
     * @param string $key
     */
    public static function flushByKey(string $key): void
    {
        if (Yii::$app->cache->exists($key)) {
            Yii::$app->cache->delete($key);
        }
    }
}
