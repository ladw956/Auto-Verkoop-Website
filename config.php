<?php

define('DEV_ADMIN_MODE', false);

define('DB_DRIVER', getenv('DB_DRIVER') ?: 'mysql');
define('DB_HOST', getenv('DB_HOST') ?: 'localhost');
define('DB_PORT', getenv('DB_PORT') ?: '3306');
define('DB_NAME', getenv('DB_NAME') ?: 'autoverkoop');
define('DB_USER', getenv('DB_USER') ?: 'root');
define('DB_Pass', getenv('DB_PASSWORD') ?: '');
define('DB_PATH', getenv('DB_PATH') ?: (__DIR__ . '/data/autoverkoop.sqlite'));
?>
