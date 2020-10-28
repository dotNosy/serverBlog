<?php
declare(strict_types=1);

namespace ServerBlog\Services;

use ServerBlog\Controllers;

class Router 
{
    private static Router $router;
    private string $url;

    private $allowed_controllers = 
    [   
        ""
        ,"home"
        ,"login"
        ,"test"
        ,"profile"
        ,"404"
        ,"blogpost"
    ];

    public function __construct()
    {
        //* Obtener URL clean
        $this->url = htmlspecialchars(urldecode(parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH)));

        //! El substr quita la primera / de la url para que la pos 0 del array sea el controlador
        $url_splitted = explode("/" ,substr($this->url, 1));

        //?si se recogen items de la URL
        if (count($url_splitted) > 0 ) 
        {
            //* pasar a minus todos los parametros de la url
            for($i = 0; $i < count($url_splitted); $i++)
            {
                $url_splitted[$i] = trim(strtolower($url_splitted[$i]));
            }

            $this->manageUrl($url_splitted);
        }
    }

    private function manageUrl(array $url)
    {   
        $isValidController = false;
        
        //? Comprobar que el controlador obtenido es valido
        foreach ($this->allowed_controllers as $ok_controller)
        {
            if ($ok_controller == $url[0]) 
            {
                $isValidController = true;

                if ($url[0] == "") {
                    $url[0] = "home";
                }
            }
        }

        if ($isValidController)
        {
            $controller = "\\ServerBlog\\Controllers\\". ucfirst($url[0]); //Convertir todo a minus y la primera letra a mayus (para que siga el patron de una clase)

            new $controller($url);
        }
        else
        {   
           Helpers::sendTo404();
        }
    }
}
