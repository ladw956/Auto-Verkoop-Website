<?php
require_once 'functies.php';

session_destroy();
header("Location: index.php");
exit;