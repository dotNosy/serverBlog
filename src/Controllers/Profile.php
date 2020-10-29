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

    public function updateProfile($params = null)
    {
        $user = Models\User::getUser();
        //echo $user->id;
        //die();

        if ($_SERVER['REQUEST_METHOD'] == "POST" && isset($_POST['update']))
        {
            //? Error credenciales vacias
            $name = Services\Helpers::cleanInput($_POST['name']);
            $surname = Services\Helpers::cleanInput($_POST['surname']);
            $email = Services\Helpers::cleanInput($_POST['email']);
            $birthdate = Services\Helpers::cleanInput($_POST['date']);
            if (empty($name)) {   
                selectName();
            }

            if(!empty($surname)){
                
            }

            if(!empty($email)){
                
            }

            if(!empty($birthdate)){
                
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
}

    