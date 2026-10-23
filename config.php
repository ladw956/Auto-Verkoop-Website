<?php

define('DEV_ADMIN_MODE', false);

define('DB_HOST', getenv('DB_HOST') ?: 'localhost');
define('DB_PORT', getenv('DB_PORT') ?: '3306');
define('DB_NAME', getenv('DB_NAME') ?: 'autoverkoop');
define('DB_USER', getenv('DB_USER') ?: 'root');
define('DB_Pass', getenv('DB_PASSWORD') ?: '');
?>
