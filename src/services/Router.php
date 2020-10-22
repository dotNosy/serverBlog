<?php
declare(strict_types=1);

namespace ServerBlog\Services;

class Router 
{
    private static Router $router;
    private string $url;

    public function __construct() 
    {
        $this->url = urldecode(parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH));
        echo $this->url;
    }

    public static function getInstance()
    {
        if (!self::$router instanceof self) {
            self::$router = new self();
        }

        return self::$router;
    }
}
