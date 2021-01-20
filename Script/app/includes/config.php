<?php
error_reporting(0);
define('FIR', true);

define('DB_HOST', 'localhost');
define('DB_USER', 'YOURDBUSER');
define('DB_NAME', 'YOURDBNAME');
define('DB_PASS', 'YOURDBPASS');

define('URL_PATH', 'https://example.com');

define('PUBLIC_PATH', 'public');
define('THEME_PATH', 'themes');
define('STORAGE_PATH', 'storage');
define('UPLOADS_PATH', 'uploads');

define('COOKIE_PATH', preg_replace('|https?://[^/]+|i', '', URL_PATH).'/');