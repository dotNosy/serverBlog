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
            $user = Models\User::getUser();
            //* El profile se crea 
            $profile = Models\Profile::getProfile(intval($user->id));
            parent::sendToView([
                "titulo" => "TEST CONTROLLER"
                ,"profile" => json_encode($profile)
                ,"page" =>  __DIR__ . '/../Views/Profile.php'
            ]);
        }
        else
        {
            //* No es un post tuyo
            Services\Helpers::sendToController("/login",
            [
                "error" => "Para ver tu perfil debes estar logeado."
            ]); 
        }
    }

    public function edit($params = null)
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

            //updateProfile($user->id, $name, $surname, $email, $birthdate);
            if(Models\Profile::edit($user->id, $name, $surname, $email, $birthdate)){
                echo "Se han modificado los datos";
            }
            else
            {
                echo "Los datos no se han modificado";
            }
        }
        else {
            Services\Helpers::sendTo404();
        }
    }

    public function editPassword($params = null)
    {
        $user = Models\User::getUser();
        //echo $user->id;
        //die();
        if ($_SERVER['REQUEST_METHOD'] == "POST" && isset($_POST['cambiarContraseña']))
        {
            
            //? Error credenciales vacias
            $password = Services\Helpers::cleanInput($_POST['password']);
            $repeatPassword = Services\Helpers::cleanInput($_POST['repeatPassword']);

            //updateProfile($user->id, $name, $surname, $email, $birthdate);
            if($password == $repeatPassword){
                if(Models\Profile::changePassword($user->id, $password)){
                    echo "Se han modificado los datos";
                    
                }
                else
                {
                    echo "Los datos no se han modificado";
                }
            }else{
                echo "Las contraseñas no son iguales";
            }
        }
        else {
            Services\Helpers::sendTo404();
        }
    }


}

    