<?php
define('TIMEZONE', 'Europe/Moscow');
date_default_timezone_set(TIMEZONE);
header('Content-Type: text/html; charset=utf-8');

setlocale(LC_ALL, "ru_RU");

define('KB', 1024);
define('MB', 1048576);
define('GB', 1073741824);
define('TB', 1099511627776);

/*ini_set('display_errors', 1);
error_reporting(E_ALL);*/

include '../core/config.php';
include '../core/functions.php';
include '../core/router.php';