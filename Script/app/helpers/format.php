<?php

/**
 * Convert the views number to the K format
 *
 * @param   float   $value  The number to be transformed
 * @return  array
 */
function formatViews($value) {
    // If the number is less than thousands
    if(strlen($value) < 4) {
        return $value;
    }

    $number = round($value);

    // Format the number
    $number = number_format($number);
    $numberParts = explode(',', $number);

    // Subtract one (we're only interested in thousands realm)
    $numberPartsCount = count($numberParts) - 1;

    // Number sections
    $numberSection = ['k', 'm', 'b', 't'];

    $count = $numberParts[0].((int)$numberParts[1][0] !== 0 ? '.'.$numberParts[1][0] : '');

    $decimals = $numberSection[$numberPartsCount - 1];

    return ['count' => $count, 'decimals' => $decimals];
}

/**
 * Convert the time duration to a 01:00:00, 01:00, or 0:10 format
 *
 * @param   string  $value  The string to be formatted
 * @return  string
 */
function formatDuration($value) {
    $time = explode(':', $value);

    if($time[0] == '00' && $time[1] == '00') {
        // Remove the hours completely
        unset($time[0]);

        // Show the minutes as a single digit
        $time[1] = 0;
    } elseif($time[0] == '00') {
        unset($time[0]);
    }

    return implode(':', $time);
}

/**
 * @param   string  $value  The value to be formatted
 * @param   int     $type
 * @return  string
 */
function formatImageUrl($value, $type = null) {
    if($type) {
        $value = URL_PATH.'/image.php?'.urlencode($value);
    }

    return $value;
}