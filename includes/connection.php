<?php

require_once __DIR__ . '/base-url.php';

$configFile = __DIR__ . '/db-config.php';

if (!is_file($configFile)) {
    http_response_code(500);
    exit('Missing database configuration: create includes/db-config.php (see includes/db-config.example.php).');
}

require $configFile;

$connection = mysqli_connect($DATABASE_HOST, $DATABASE_USER, $DATABASE_PASSWORD, $DATABASE_NAME);
mysqli_report(MYSQLI_REPORT_ERROR | MYSQLI_REPORT_STRICT);
