# yii2-helpers

***
Sorokin.Media repository
***

Компонент содержит несколько хелперов, которые могут быть использованы в проектах.

***
### ArrayHelper
#### список методов
+ `public static function convertArrayToArrayOfObject(array $array) : array {}` - конвертирует массив key=>value в массив объектов {'id', 'name'}
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
