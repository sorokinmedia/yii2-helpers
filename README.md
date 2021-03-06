# yii2-helpers

[![Total Downloads](https://img.shields.io/packagist/dt/sorokinmedia/yii2-helpers.svg)](https://packagist.org/packages/sorokinmedia/yii2-helpers)

***
Sorokin.Media repository
***

Компонент содержит несколько хелперов, которые могут быть использованы в проектах.

***
### ArrayHelper
#### список методов
+ `public static function convertArrayToArrayOfObject(array $array, string $type = 'int') : array {}` - конвертирует массив key=>value в массив объектов {'id', 'name'}
+ `public static function costHourValues() : array {}` -  формирует список временных интервалов для выбора в зарплатном модуле      

***
### DateHelper
#### список констант
+ `const TIME_SECOND_ONE`  - одна секунда
+ `const TIME_MINUTE_ONE` - одна минута
+ `const TIME_HOUR_ONE` - один час
+ `const TIME_HOUR_TWELVE` - 12 часов
+ `const TIME_DAY_ONE` - один день
+ `const TIME_DAY_FIFTEEN` - 15 дней
+ `const TIME_DAY_THIRTY` - 30 дней
+ `const TIME_YEAR_ONE` - один год
+ `const WEEKEND_DAYS` - массив выходных дней

#### список методов
+ `public static function getExtendTime(int $extend_time) : int` - получает кол-во часов, учитывая выходные
+ `public static function getListOfTimezones(string $locale = '') : array` - список временных зон
+ `public static function getLeftTime(int $time = null)` - трансформирует секунды(unixstamp) в текст (1 час 20 минут)
+ `public static function getTzOffset(string $timezone) : int` - получает отступ таймзоны от UTC в секундах
+ `public static function hoursArray() : array` - формирует список временных интервалов для селекта на форме
+ `public static function getStartEndMonth() : array` - первый и последний день месяца
+ `public static function getStartEndPrevMonth() : array` - первый и последний день прошлого месяца

***
### PluralHelper
#### список методов
+ `public static function convert(int $n, string $type = "hours") : string` - конвертирует число в слово в нужной словоформе. доступные варианты:
    + `days` - дни
    + `months` - месяцы
    + `years` - годы 
    + `hours` - часы
    + `minutes` - минуты
    + `seconds` - секунды
    + `text` - тексты
    + `notification` - уведомления
    + `tests` - разы
    + `tasks` - задачи
    + `rubl` - рубли
    
***
### TextHelper
#### список методов
+ `public static function translitString(string $string) : string` - транслитерация строки
+ `public static function autop( $pee, $br = true )` - обертка из Worpdress в теги p,br и т.д.
+ `public static function checkCardNumber(string $str) : bool` - проверка номера карты по методу Луна
+ `public static function clearText(string $text = null) : string` - очистка текста от пробелов и тегов
+ `public static function clearTextAllowedTags(string $text = null) : string` - чистит текст от тегов, оставляя указанные теги
+ `public static function trimToLowerText($text) : string` - убирает пробелы и приводит к нижнему регистру
+ `public static function array2string(array $array) : string` - конвертирует массив в строку (для JSON)
+ `public static function getCountLinkPost(string $text) : int` - подсчет кол-ва тегов а в тексте
+ `public static function filterResponse(string $response) : string` - конвертирует JSON ответ в строку (для тестов API)
+ `public static function makeUrls(string $text) : string` - автоматическая простановка тегов ссылок в тексте
+ `public static function removeNbsp(string $text) : string` - очистка текста от непрерывных пробелов (nbsp)

***
### TestHelper
#### список методов
+ `public static function filterResponse(string $response) : string` - приводит API ответ к нужному виду. для тестов API

***
### StatsHelper
#### список методов
+ `public static function makeInt(array $array) : array` - приводит значение массива к целым числам (int)
+ `public static function makeFloat(array $array) : array` - приводит значения массива к дробным числам (float)
+ `public static function makeRound(array $array) : array` - округляет все значения массива до 2 знаков после запятой
+ `public static function makeMinutes(array $array) : array` - минуты из секунд
+ `public static function date_range(string $from, string $to, string $step = '+1 day', string $output_format = 'd-m-Y' ) : array` - формирует массив дат заданного интервала

***
### CacheHelper
###Работа с кешем

Чтобы завернуть ответ в API в кеш необходимо сделать:

- Определить уникальный ключ исходя из параметров запроса, например `"User.$user->id.new-messages"`

- Перед вызовом основного функционала в апи методе добавить проверку наличия кеша:
```
$cache_key = "User.$user->id.new-messages";
if (Yii::$app->cache->exists($cache_key)){
    return new ApiAnswerLogFromCache(null, Yii::$app->cache->get($cache_key));
}
```

- Перед отправкой ответа после основного функицонала метода добавлять ответ в кеш:
```
Yii::$app->cache->set($cache_key, $messages, CacheHelper::CACHE_TIME_FIVE_MINUTES);
return new ApiAnswerLogInsertCache(null, $messages);
```

- Добавить метод очистки кеша по заданному ключу в `CacheHelper`

- Добавить вызовы метода очистки кеша по коду, там где это требуется

- Описать закешированный метод в локальном `readme.MD`
