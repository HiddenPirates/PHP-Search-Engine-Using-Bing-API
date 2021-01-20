<?php

/**
 * Convert the characters into HTML entities
 *
 * @param   string  $value  The string to be escaped
 * @return  string
 */
function e($value) {
    return htmlspecialchars($value, ENT_QUOTES, 'UTF-8');
}

/**
 * Limit the amount of characters in a string and provide an excerpt of it
 *
 * @param   string  $value      The string to extract the substring from
 * @param   int     $start      The character number to start from, counting from 0
 * @param   int     $length     The maximum number of characters to be used from the string
 * @param   array   $options    The string to be appended
 * @return  string
 */
function str_limit($value, $start = 0, $length = null, $options = []) {
    $result = mb_substr($value, $start, $length);

    if(mb_strlen($value) > $length && is_null($length) == false) {
        if(isset($options['ellipsis'])) {
            $result = $result.$options['ellipsis'];
        }
    }

    return $result;
}

/**
 * Truncates text.
 *
 * Cuts a string to the length of $length and replaces the last characters
 * with the ellipsis if the text is longer than length.
 *
 * ### Options:
 *
 * - `ellipsis` Will be used as ending and appended to the trimmed string
 * - `exact` If false, $text will not be cut mid-word
 * - `html` If true, HTML tags would be handled correctly
 *
 * @param   string  $text       String to truncate.
 * @param   int     $length     Length of returned string, including ellipsis.
 * @param   array   $options    An array of HTML attributes and options.
 * @return  string
 */
function truncate($text, $length = 100, $options = []) {
    $default = ['ellipsis' => '...', 'exact' => true, 'html' => false];

    $options += $default;

    $prefix = '';
    $suffix = $options['ellipsis'];

    if($options['html']) {
        $ellipsisLength = mb_strlen(strip_tags($options['ellipsis']));

        $truncateLength = 0;
        $totalLength = 0;
        $openTags = [];
        $truncate = '';

        preg_match_all('/(<\/?([\w+]+)[^>]*>)?([^<>]*)/', $text, $tags, PREG_SET_ORDER);
        foreach($tags as $tag) {
            $contentLength = 0;
            $defaultHtmlNoCount = ['style', 'script'];
            if (!in_array($tag[2], $defaultHtmlNoCount, true)) {
                $contentLength = mb_strlen($tag[3]);
            }

            if($truncate === '') {
                if(!preg_match('/img|br|input|hr|area|base|basefont|col|frame|isindex|link|meta|param/i', $tag[2])) {
                    if(preg_match('/<[\w]+[^>]*>/', $tag[0])) {
                        array_unshift($openTags, $tag[2]);
                    } elseif(preg_match('/<\/([\w]+)[^>]*>/', $tag[0], $closeTag)) {
                        $pos = array_search($closeTag[1], $openTags);
                        if($pos !== false) {
                            array_splice($openTags, $pos, 1);
                        }
                    }
                }

                $prefix .= $tag[1];

                if($totalLength + $contentLength + $ellipsisLength > $length) {
                    $truncate = $tag[3];
                    $truncateLength = $length - $totalLength;
                } else {
                    $prefix .= $tag[3];
                }
            }

            $totalLength += $contentLength;
            if($totalLength > $length) {
                break;
            }
        }

        if($totalLength <= $length) {
            return $text;
        }

        $text = $truncate;
        $length = $truncateLength;

        foreach($openTags as $tag) {
            $suffix .= '</'.$tag.'>';
        }
    } else {
        if(mb_strlen($text) <= $length) {
            return $text;
        }
        $ellipsisLength = mb_strlen($options['ellipsis']);
    }

    $result = mb_substr($text, 0, $length - $ellipsisLength);

    if(!$options['exact']) {
        if(mb_substr($text, $length - $ellipsisLength, 1) !== ' ') {
            $result = removeLastWord($result);
        }

        // If result is empty, then we don't need to count ellipsis in the cut.
        if(!strlen($result)) {
            $result = mb_substr($text, 0, $length);
        }
    }

    return $prefix.$result.$suffix;
}

/**
 * Removes the last word from the input text.
 *
 * @param   string  $text   The input text
 * @return  string
 */
function removeLastWord($text) {
    $spacePos = mb_strrpos($text, ' ');

    if($spacePos !== false) {
        $lastWord = mb_strrpos($text, $spacePos);

        // Some languages are written without word separation.
        // We recognize a string as a word if it doesn't contain any full-width characters.
        if(mb_strwidth($lastWord) === mb_strlen($lastWord)) {
            $text = mb_substr($text, 0, $spacePos);
        }

        return $text;
    }

    return '';
}