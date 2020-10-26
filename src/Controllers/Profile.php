<?php

declare(strict_types=1);

namespace ServerBlog\Controllers;

class Profile extends Controller
{
    public function __construct(array $params = null) 
    {
        parent::__construct($params);
    }

    protected function index(array $params = null) 
    {
        if (!empty($_SESSION['user']))
        {
            parent::sendToView([
                "titulo" => "TEST CONTROLLER"
               ,"page" =>  __DIR__ . '/../Views/Profile.php'
           ]);
        }
        else
        {
            //TODO: Crear pagina de sesion expired
            echo "sesion no encontrada";
        }
    }
}