<?php

declare(strict_types=1);

require_once __DIR__ . '/../../vendor/autoload.php';

define("__PARENT_TEMPLATE__", __DIR__ . '/../Views/Parent.php');

session_start();

use ServerBlog\Services\Router;

//! echo memory_get_usage(); //Saber cuanta memoria se le asigno al script ejecutado (controlador)

$router = new Router();