<?php

declare(strict_types=1);

namespace ServerBlog\Controllers;

use ServerBlog\Services as Services;

use ServerBlog\Models as Models;

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

    function addName()
    {
        if ($_SERVER['REQUEST_METHOD'] == "POST" && isset($_POST['addName']))
        {
            $name = Services\Helpers::cleanInput($_POST['addName']);

            //? Error credenciales vacias
            if (empty($name)) 
            {   
            echo  "Los campos estan vacios";
            }
            //? DO LOGIN
            else
            {
                $this->addName($name);
                echo "los datos se han insertado correctamente";
            }
        }
        else {
            Services\Helpers::sendTo404();
        }
    }