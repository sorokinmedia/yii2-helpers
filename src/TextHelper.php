<?php

namespace sorokinmedia\helpers;

/**
 * Class TextHelper
 * @package sorokinmedia\helpers
 *
 * работа с текстом
 */
class TextHelper
{
    /**
     * транслитерация
     * @param string $string
     * @return string
     */
    public static function translitString(string $string): string
    {
        $replace = array(
            "'" => "",
            "`" => "",
            "а" => "a", "А" => "a",
            "б" => "b", "Б" => "b",
            "в" => "v", "В" => "v",
            "г" => "g", "Г" => "g",
            "д" => "d", "Д" => "d",
            "е" => "e", "Е" => "e",
            "ё" => "e", "Ё" => "e",
            "ж" => "zh", "Ж" => "zh",
            "з" => "z", "З" => "z",
            "и" => "i", "И" => "i",
            "й" => "y", "Й" => "y",
            "к" => "k", "К" => "k",
            "л" => "l", "Л" => "l",
            "м" => "m", "М" => "m",
            "н" => "n", "Н" => "n",
            "о" => "o", "О" => "o",
            "п" => "p", "П" => "p",
            "р" => "r", "Р" => "r",
            "с" => "s", "С" => "s",
            "т" => "t", "Т" => "t",
            "у" => "u", "У" => "u",
            "ф" => "f", "Ф" => "f",
            "х" => "h", "Х" => "h",
            "ц" => "c", "Ц" => "c",
            "ч" => "ch", "Ч" => "ch",
            "ш" => "sh", "Ш" => "sh",
            "щ" => "sch", "Щ" => "sch",
            "ъ" => "", "Ъ" => "",
            "ы" => "y", "Ы" => "y",
            "ь" => "", "Ь" => "",
            "э" => "e", "Э" => "e",
            "ю" => "yu", "Ю" => "yu",
            "я" => "ya", "Я" => "ya",
            "і" => "i", "І" => "i",
            "ї" => "yi", "Ї" => "yi",
            "є" => "e", "Є" => "e"
        );
        $str = iconv("UTF-8", "UTF-8//IGNORE", strtr($string, $replace));
        $str = preg_replace("/[^a-z0-9-]/i", " ", $str);
        $str = preg_replace("/ +/", "-", trim($str));
        return strtolower($str);
    }

    /**
     * Replaces double line-breaks with paragraph elements.
     *
     * A group of regex replaces used to identify text formatted with newlines and
     * replace double line-breaks with HTML paragraph tags. The remaining line-breaks
     * after conversion become <<br />> tags, unless $br is set to '0' or 'false'.
     *
     * @param string $pee The text which has to be formatted.
     * @param bool $br Optional. If set, this will convert all remaining line-breaks
     *                    after paragraphing. Default true.
     * @return string Text which has been converted into correct paragraph tags.
     * @since 0.71
     *
     */
    public static function autop($pee, $br = true)
    {
        $pre_tags = array();

        if (trim($pee) === '')
            return '';

        // Just to make things a little easier, pad the end.
        $pee = $pee . "\n";

        /*
         * Pre tags shouldn't be touched by autop.
         * Replace pre tags with placeholders and bring them back after autop.
         */
        if (strpos($pee, '<pre') !== false) {
            $pee_parts = explode('</pre>', $pee);
            $last_pee = array_pop($pee_parts);
            $pee = '';
            $i = 0;

            foreach ($pee_parts as $pee_part) {
                $start = strpos($pee_part, '<pre');

                // Malformed html?
                if ($start === false) {
                    $pee .= $pee_part;
                    continue;
                }

                $name = "<pre wp-pre-tag-$i></pre>";
                $pre_tags[$name] = substr($pee_part, $start) . '</pre>';

                $pee .= substr($pee_part, 0, $start) . $name;
                $i++;
            }

            $pee .= $last_pee;
        }
        // Change multiple <br>s into two line breaks, which will turn into paragraphs.
        $pee = preg_replace('|<br\s*/?>\s*<br\s*/?>|', "\n\n", $pee);

        $allblocks = '(?:table|thead|tfoot|caption|col|colgroup|tbody|tr|td|th|div|dl|dd|dt|ul|ol|li|pre|form|map|area|blockquote|address|math|style|p|h[1-6]|hr|fieldset|legend|section|article|aside|hgroup|header|footer|nav|figure|figcaption|details|menu|summary)';

        // Add a single line break above block-level opening tags.
        $pee = preg_replace('!(<' . $allblocks . '[\s/>])!', "\n$1", $pee);

        // Add a double line break below block-level closing tags.
        $pee = preg_replace('!(</' . $allblocks . '>)!', "$1\n\n", $pee);

        // Standardize newline characters to "\n".
        $pee = str_replace(array("\r\n", "\r"), "\n", $pee);

        // Collapse line breaks before and after <option> elements so they don't get autop'd.
        if (strpos($pee, '<option') !== false) {
            $pee = preg_replace('|\s*<option|', '<option', $pee);
            $pee = preg_replace('|</option>\s*|', '</option>', $pee);
        }

        /*
         * Collapse line breaks inside <object> elements, before <param> and <embed> elements
         * so they don't get autop'd.
         */
        if (strpos($pee, '</object>') !== false) {
            $pee = preg_replace('|(<object[^>]*>)\s*|', '$1', $pee);
            $pee = preg_replace('|\s*</object>|', '</object>', $pee);
            $pee = preg_replace('%\s*(</?(?:param|embed)[^>]*>)\s*%', '$1', $pee);
        }

        /*
         * Collapse line breaks inside <audio> and <video> elements,
         * before and after <source> and <track> elements.
         */
        if (strpos($pee, '<source') !== false || strpos($pee, '<track') !== false) {
            $pee = preg_replace('%([<\[](?:audio|video)[^>\]]*[>\]])\s*%', '$1', $pee);
            $pee = preg_replace('%\s*([<\[]/(?:audio|video)[>\]])%', '$1', $pee);
            $pee = preg_replace('%\s*(<(?:source|track)[^>]*>)\s*%', '$1', $pee);
        }

        // Remove more than two contiguous line breaks.
        $pee = preg_replace("/\n\n+/", "\n\n", $pee);

        // Split up the contents into an array of strings, separated by double line breaks.
        $pees = preg_split('/\n\s*\n/', $pee, -1, PREG_SPLIT_NO_EMPTY);

        // Reset $pee prior to rebuilding.
        $pee = '';

        // Rebuild the content as a string, wrapping every bit with a <p>.
        foreach ($pees as $tinkle) {
            $pee .= '<p>' . trim($tinkle, "\n") . "</p>\n";
        }

        // Under certain strange conditions it could create a P of entirely whitespace.
        $pee = preg_replace('|<p>\s*</p>|', '', $pee);

        // Add a closing <p> inside <div>, <address>, or <form> tag if missing.
        $pee = preg_replace('!<p>([^<]+)</(div|address|form)>!', "<p>$1</p></$2>", $pee);

        // If an opening or closing block element tag is wrapped in a <p>, unwrap it.
        $pee = preg_replace('!<p>\s*(</?' . $allblocks . '[^>]*>)\s*</p>!', "$1", $pee);

        // In some cases <li> may get wrapped in <p>, fix them.
        $pee = preg_replace("|<p>(<li.+?)</p>|", "$1", $pee);

        // If a <blockquote> is wrapped with a <p>, move it inside the <blockquote>.
        $pee = preg_replace('|<p><blockquote([^>]*)>|i', "<blockquote$1><p>", $pee);
        $pee = str_replace('</blockquote></p>', '</p></blockquote>', $pee);

        // If an opening or closing block element tag is preceded by an opening <p> tag, remove it.
        $pee = preg_replace('!<p>\s*(</?' . $allblocks . '[^>]*>)!', "$1", $pee);

        // If an opening or closing block element tag is followed by a closing <p> tag, remove it.
        $pee = preg_replace('!(</?' . $allblocks . '[^>]*>)\s*</p>!', "$1", $pee);

        // Optionally insert line breaks.
        if ($br) {
            // Normalize <br>
            $pee = str_replace(array('<br>', '<br/>'), '<br />', $pee);

            // Replace any new line characters that aren't preceded by a <br /> with a <br />.
            $pee = preg_replace('|(?<!<br />)\s*\n|', "<br />\n", $pee);

            // Replace newline placeholders with newlines.
            $pee = str_replace('<WPPreserveNewline />', "\n", $pee);
        }

        // If a <br /> tag is after an opening or closing block tag, remove it.
        $pee = preg_replace('!(</?' . $allblocks . '[^>]*>)\s*<br />!', "$1", $pee);

        // If a <br /> tag is before a subset of opening or closing block tags, remove it.
        $pee = preg_replace('!<br />(\s*</?(?:p|li|div|dl|dd|dt|th|pre|td|ul|ol)[^>]*>)!', '$1', $pee);
        $pee = preg_replace("|\n</p>$|", '</p>', $pee);

        // Replace placeholder <pre> tags with their original content.
        if (!empty($pre_tags))
            $pee = str_replace(array_keys($pre_tags), array_values($pre_tags), $pee);

        // Restore newlines in all elements.
        if (false !== strpos($pee, '<!-- wpnl -->')) {
            $pee = str_replace(array(' <!-- wpnl --> ', '<!-- wpnl -->'), "\n", $pee);
        }

        return $pee;
    }

    /**
     * Проверка банковских карт (Луна)
     * @param string $str
     * @return bool
     */
    public static function checkCardNumber(string $str): bool
    {
        $str = strrev(preg_replace('/[^0-9]/', '', $str));
        $chk = 0;
        for ($i = 0, $iMax = strlen($str); $i < $iMax; $i++) {
            $tmp = (int)$str[$i] * (1 + $i % 2);
            $chk += $tmp - ($tmp > 9 ? 9 : 0);
        }
        return !($chk % 10);
    }

    /**
     * чистит текст от пробелов и тегов
     * @param string $text
     * @return string
     */
    public static function clearText(string $text = null): string
    {
        if ($text === null) {
            return '';
        }
        return strip_tags(trim($text));
    }

    /**
     * чистит текст от пробелов и оставляет указанные теги
     * @param string|null $text
     * @return string
     */
    public static function clearTextAllowedTags(string $text = null): string
    {
        if ($text === null) {
            return '';
        }
        return strip_tags(trim($text), '<p><a><strong><em><ul><li><ol><h1><h2><h3>');
    }

    /**
     * обрезает пробелы и приводит к нижнему регистру
     * @param $text
     * @return string
     */
    public static function trimToLowerText($text): string
    {
        return mb_strtolower(trim($text));
    }

    /**
     * массив в строку
     * @param array $array
     * @return string
     */
    public static function array2string(array $array): string
    {
        $log_a = "[\n";
        foreach ($array as $key => $value) {
            if (is_array($value)) {
                $log_a .= "\t'" . $key . "' => [" . self::array2string($value) . "], \n";
            } elseif ($value === null) {
                $log_a .= "\t'" . $key . "' => null,\n";
            } elseif ($value === '') {
                $log_a .= "\t'" . $key . "' => '',\n";
            } elseif (preg_match('^[а-яА-ЯёЁ]+$^', $value)
                || preg_match('/[^A-Za-z0-9]/', $value)
                || preg_match('/[A-Za-z]/', $value)) {
                $log_a .= "\t'" . $key . "' => '$value',\n";
            } else {
                $log_a .= "\t'" . $key . "' => " . $value . ",\n";
            }
        }
        return $log_a . "],\n";
    }

    /**
     * Считает кол-во ссылок в тексте
     * @param string $text
     * @return int
     */
    public static function getCountLinkPost(string $text): int
    {
        return substr_count($text, '<a');
    }

    /**
     * конвертирует json ответ в строку
     * @param string $response
     * @return string
     */
    public static function filterResponse(string $response): string
    {
        $response = str_replace(array('{', '}', '":'), array('[', ']', '"=>'), $response);
        return $response;
    }

    /**
     * автоматическая простановка ссылок в тексте
     * @param string $text
     * @return string
     * @deprecated
     */
    public static function makeUrls(string $text): string
    {
        $urlPattern = "/(http|https|ftp|ftps)\:\/\/[a-zA-Z0-9\-\.]+\.[a-zA-Z]{2,6}(\/\S*)?/";
        preg_match_all($urlPattern, $text, $matches);
        $usedPatterns = array();
        foreach ($matches[0] as $pattern) {
            if (!array_key_exists($pattern, $usedPatterns)) {
                $usedPatterns[$pattern] = true;
                // now try to catch last thing in text
                $text = str_replace($pattern, '<a href="' . $pattern . '" target="_blank">' . $pattern . '</a>', $text);
            }
        }
        return $text;
    }

    /**
     * очистка текст от неразрывных пробелов
     * @param string $text
     * @return string
     */
    public static function removeNbsp(string $text): string
    {
        //$text = preg_replace('/\s&nbsp;\s\ig', ' ', $text);
        return str_replace('&nbsp;', ' ', $text);
    }

}
