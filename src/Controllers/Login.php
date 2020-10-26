<?php

declare(strict_types=1);

namespace ServerBlog\Controllers;

use ServerBlog\Services as Services;

use ServerBlog\Models as Models;

class Login extends Controller
{
    public function __construct(array $params = null) 
    {
        parent::__construct($params);
    }

    protected function index(array $params = null) 
    {   
        parent::sendToView([
           "titulo" => "LOGIN"
           ,"css" => array("login.css")
           ,"page" => __DIR__ . '/../Views/Login.php'
        ]);
    }

    protected function register($params = null) 
    {
        //? VISTA FORMULARIO
        if ($_SERVER['REQUEST_METHOD'] == "GET")
        {
            parent::sendToView([
                "titulo" => "SIGNING UP"
                ,"css" => array("register.css")
                ,"page" => __DIR__ . '/../Views/Register.php'
             ]);
        }

        //? PROCESAR FORMULARIO
        else if ($_SERVER['REQUEST_METHOD'] == "POST")
        {
            if (isset($_POST['register']))
            {
                $username = Services\Helpers::cleanInput($_POST['username']);
                $password = Services\Helpers::cleanInput($_POST['password']);
                $rpassword = Services\Helpers::cleanInput($_POST['rpassword']);

                //? En vez de hacer 2 calls diferentes, se podria hacer solo poner el mensaje de error en una variable y hacer una sola llamada al sendToView()

                if (empty($username) || empty($password) || empty($rpassword)) 
                {   
                    parent::sendToView([
                        "titulo" => "SIGNING UP"
                        ,"css" => array("register.css")
                        ,"error" => "Uno o mas campos estan vacios"
                        ,"page" => __DIR__ . '/../Views/Register.php'
                     ]);
                }
                else if ($password !== $rpassword)
                {
                    parent::sendToView([
                        "titulo" => "SIGNING UP"
                        ,"css" => array("register.css")
                        ,"error" => "Las contraseñas no coinciden"
                        ,"page" => __DIR__ . '/../Views/Register.php'
                     ]);
                }

                //TODO: Comprobar que la password sea segura

                // if(){

                // }

                //TODO: Comprobar si el username existe ya

               if (Models\User::add($username, $password)) 
               {
                    //Logear al user y enviar al perfil
                    $user = Models\User::login($username, $password);

                    if (empty($user)) {
                        parent::sendToView([
                            "titulo" => "SIGNING UP"
                            ,"css" => array("register.css")
                            ,"error" => "Las credenciales son incorrectas."
                            ,"page" => __DIR__ . '/../Views/Register.php'
                         ]);
                    }
                    else if ($user == "error conexion"){
                        parent::sendToView([
                            "titulo" => "SIGNING UP"
                            ,"css" => array("register.css")
                            ,"error" => "Hubo un problema de conexion."
                            ,"page" => __DIR__ . '/../Views/Register.php'
                         ]);
                    }
                    else if ($user == "credenciales incorrectas"){
                        parent::sendToView([
                            "titulo" => "SIGNING UP"
                            ,"css" => array("register.css")
                            ,"error" => "Las credenciales son incorrectas."
                            ,"page" => __DIR__ . '/../Views/Register.php'
                         ]);
                    }
                    else if (!empty($user) && $user instanceof Models\User) {
                        parent::sendToView([
                            "user" => serialize($user)
                            ,"titulo" => "HOME"
                            ,"page" => __DIR__ . '/../Views/Home.php'
                         ]);
                    }
                    else {
                        parent::sendToView([
                            "titulo" => "SIGNING UP"
                            ,"css" => array("register.css")
                            ,"error" => "Hubo un error inesperado."
                            ,"page" => __DIR__ . '/../Views/Register.php'
                         ]);
                    }
               }
               else
               {
                parent::sendToView([
                    "titulo" => "SIGNING UP"
                    ,"css" => array("register.css")
                    ,"error" => "Hubo un problema de conexion."
                    ,"page" => __DIR__ . '/../Views/Register.php'
                 ]);
               }
            }
        }
    }
}