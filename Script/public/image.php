<?php

/**
 * This file serves as a proxy between the end-user and the requested resource.
 */

require_once('../app/includes/config.php');

$referrer = parse_url($_SERVER['HTTP_REFERER'] ?? null);
$image = parse_url($_SERVER['REQUEST_URI'] ?? null);
$host = parse_url(URL_PATH);

// If there's no referrer set
if(isset($_SERVER['HTTP_REFERER']) == false || empty($_SERVER['HTTP_REFERER'])) {
    exit();
}

// If the referrer is not the same with the host
if($referrer['host'] !== $host['host']) {
    exit();
}

// If there's no image url set
if(isset($image['query']) == false || empty($image['query'])) {
    exit();
}

// Image URL
$url = urldecode($image['query']);

// Parse the path info
$path = pathinfo($url);

$response = null;

if(function_exists('curl_exec')) {
    $ch = curl_init($url);
    curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 2);
    curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
    curl_setopt($ch, CURLOPT_FOLLOWLOCATION, TRUE);
    curl_setopt($ch, CURLOPT_USERAGENT, 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/72.0.3626.121 Safari/537.36');
    curl_setopt($ch, CURLOPT_TIMEOUT, 3);
    $response = curl_exec($ch);

    if(curl_errno($ch)) {
        header("Request Timeout", true, 408);
        exit();
    }
} else {
    header("Internal Server Error", true, 500);
    exit();
}

// Output the result
header('Content-Type: '.getContentType($path['extension']));
echo $response;

/**
 * @param $extension
 * @return mixed|string
 */
function getContentType($extension) {
    $result = 'image/jpeg';

    $list = [
        'png'   => 'image/png',
        'jpe'   => 'image/jpeg',
        'jpeg'  => 'image/jpeg',
        'jpg'   => 'image/jpeg',
        'gif'   => 'image/gif',
        'bmp'   => 'image/bmp',
        'ico'   => 'image/vnd.microsoft.icon',
        'tiff'  => 'image/tiff',
        'tif'   => 'image/tiff',
        'svg'   => 'image/svg+xml',
        'svgz'  => 'image/svg+xml',
        'webp'  => 'image/webp'
    ];

    foreach($list as $key => $value) {
        if($key == $extension) {
            $result = $value;
            break;
        }
    }

    return $result;
}