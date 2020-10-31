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
        //? Si hay parametros adicionales en la URL
        if (!empty($params) && !empty($_SESSION['URL_PARAMS']))
        {   
            //* Los parametros de Index tienen que ser unicamente para la vista
            parent::sendToView(
            [
                "titulo" => "LOGIN"
                ,"css" => array("login.css")
                ,"page" => __DIR__ . '/../Views/Login.php'
            ]);
        }
        else
        {
            parent::sendToView([
                "titulo" => "LOGIN"
                ,"css" => array("login.css")
                ,"page" => __DIR__ . '/../Views/Login.php'
             ]);
        }
    }

    protected function login()
    {
        if ($_SERVER['REQUEST_METHOD'] == "POST" && isset($_POST['login']))
        {
            $username = Services\Helpers::cleanInput($_POST['username']);
            $password = Services\Helpers::cleanInput($_POST['password']);

            //? Error credenciales vacias
            if (empty($username) || empty($password)) 
            {   
                parent::sendToView([
                    "titulo" => "SIGNING UP"
                    ,"css" => array("login.css")
                    ,"error" => "Uno o mas campos estan vacios"
                    ,"page" => __DIR__ . '/../Views/Login.php'
                ]);
            }
            //? DO LOGIN
            else
            {
                $this->tryLogin($username, $password);
            }
        }
        else {
            Services\Helpers::sendTo404();   
        }
    }

    protected function logout($params = null)
    {
        Models\User::logout();

        Services\Helpers::sendToController("/login");
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
                $errorPassword = "";
                
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
                
                //* Comprobacion contraseña segura
                Services\Controllers\Login::checkPassword($password,$errorPassword);
                //? Si hay errores de critero en la password, devolver vista con errores 
                if(strlen($errorPassword) > 0)
                {
                    parent::sendToView([
                        "titulo" => "SIGNING UP"
                        ,"css" => array("register.css")
                        ,"error" => $errorPassword
                        ,"page" => __DIR__ . '/../Views/Register.php'
                     ]);
                }

                //? Comprobacr que el usuario no exista
                if(Models\User::userExists($username))
                {
                    parent::sendToView([
                        "titulo" => "SIGNING UP"
                        ,"css" => array("register.css")
                        ,"error" => "El usuario elegido ya está registrado. Por favor escoja otro."
                        ,"page" => __DIR__ . '/../Views/Register.php'
                     ]);
                }

                //? Si se pudo crear el usuario, lo logueamos
                if (Models\User::add($username, $password)) 
                {
                    $this->tryLogin($username, $password, __DIR__ . '/../Views/Register.php','register.css');
                }
                //? hubo error al crear el usuario, vovler al registro
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

    private function tryLogin(string $username, string $password, string $error_page = __DIR__ . '/../Views/Login.php', $css = "login.css")
    {
        //*Logear al user y enviar al perfil
        $user = Models\User::login($username, $password);

        if (empty($user)) 
        {
            parent::sendToView([
                "titulo" => "SIGNING UP"
                ,"css" => array($css)
                ,"error" => "Las credenciales son incorrectas."
                ,"page" => $error_page
             ]);
        }
        else if ($user == "error conexion")
        {
            parent::sendToView([
                "titulo" => "SIGNING UP"
                ,"css" => array($css)
                ,"error" => "Hubo un problema de conexion."
                ,"page" => $error_page
             ]);
        }
        else if ($user == "credenciales incorrectas")
        {
            parent::sendToView([
                "titulo" => "SIGNING UP"
                ,"css" => array($css)
                ,"error" => "Las credenciales son incorrectas."
                ,"page" => $error_page
             ]);
        }
        //? USUARIO CORRECTO
        else if (!empty($user) && $user instanceof Models\User) 
        {
            $_SESSION['user'] = json_encode([
                'id' => $user->getId(),
                'username' => $user->getUsername()
                ]);
    
            Services\Helpers::sendToController("/home");
        }
        else 
        {
            parent::sendToView([
                "titulo" => "SIGNING UP"
                ,"css" => array($css)
                ,"error" => "Hubo un error inesperado."
                ,"page" => $error_page
             ]);
        }
    }
}