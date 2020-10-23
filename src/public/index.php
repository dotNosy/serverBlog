<?php

declare(strict_types=1);

require_once __DIR__ . '\..\..\vendor\autoload.php';

define("__PARENT_TEMPLATE__", __DIR__ . '\..\Views\Parent.php');

session_start();

// $conn = new ServerBlog\Services\Connection();
$router = new ServerBlog\Services\Router();

