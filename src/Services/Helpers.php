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

    public static function sendToController(string $url = "/", array $urlParams = null)
    {
        //* Add parametros de url a la sesion
        if (!empty($urlParams))
        {
            $_SESSION['URL_PARAMS'] = $urlParams;
        }

        header("Location: $url");
        die();
    }

    public static function filesToArray($files) :array
    {
        $filesList = array();
        foreach ($files as $property => $array) {
            foreach ($array as $index => $value) {
                $filesList[$index][$property] = $value;
            }
        }
        
        return $filesList;
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
