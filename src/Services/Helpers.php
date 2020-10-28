<?php
declare(strict_types=1);

namespace ServerBlog\Services;

class Helpers
{
    public static function sendTo404()
    {
        require_once  __DIR__ . '/../Views/404.php';
        die();
    }

    public static function sendToController(string $url = "/")
    {
        header("Location: $url");
        die();
    }

    public static function cleanInput($input)
    {
        return htmlspecialchars(trim($input));
    }

    public static function getEnviroment()
    {
        return "dev";
    }
}
