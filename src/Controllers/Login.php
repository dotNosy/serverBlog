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
        $decPasswd = "";
        //Desencriptar password de
        if (!empty($_COOKIE['passwd'])){
            $decPasswd = openssl_decrypt ($_COOKIE['passwd'], "AES-128-CTR", 
            "ServerBlogKey101813112020", 0, "1234567891011121");
        }

        parent::sendToView([
            "titulo" => "LOGIN"
            ,"css" => array("login.css")
            ,"page" => __DIR__ . '/../Views/Login.php'
            ,"script" => "
                $('input[name=username]').val(localStorage.getItem('login'));
                $('input[name=password]').val('$decPasswd');
            "
        ]);
    }

    protected function login()
    {
        if ($_POST && isset($_POST['login']))
        {
            $username = Services\Helpers::cleanInput($_POST['username']);
            $password = Services\Helpers::cleanInput($_POST['password']);

            //? Error credenciales vacias
            if (empty($username) || empty($password)) 
            {   
                parent::sendToView([
                    "titulo" => "SIGNING UP"
                    ,"css" => array("login.css")
                    ,"js" => array("validateUser.js")
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
                ,"js" => array("validateUser.js")
                ,"css" => array("register.css")
                ,"page" => __DIR__ . '/../Views/Register.php'
             ]);
        }
        //? PROCESAR FORMULARIO
        else if (isset($_POST['register']))
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
                    ,"js" => array("validateUser.js")
                    ,"css" => array("register.css")
                    ,"error" => "Uno o mas campos estan vacios"
                    ,"page" => __DIR__ . '/../Views/Register.php'
                    ]);
            }
            else if ($password !== $rpassword)
            {
                parent::sendToView([
                    "titulo" => "SIGNING UP"
                    ,"js" => array("validateUser.js")
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
                    ,"js" => array("validateUser.js")
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
                    ,"js" => array("validateUser.js")
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
                    ,"js" => array("validateUser.js")
                    ,"css" => array("register.css")
                    ,"error" => "Hubo un problema de conexion."
                    ,"page" => __DIR__ . '/../Views/Register.php'
                ]);
            }
        }
    }

    private function tryLogin(string $username, string $password, string $error_page = __DIR__ . '/../Views/Login.php', $css = "login.css")
    {
        //*Logear al user y enviar al perfil
        $user = Models\User::login($username, $password);

        //! USUARIO CORRECTO
        if (!empty($user) && $user instanceof Models\User) 
        {
            $_SESSION['user'] = json_encode([
                'id' => $user->getId(),
                'username' => $user->getUsername()
                ]);

            //Encriptar password y añadirla a una cookie
            $localPasswd = openssl_encrypt($password, "AES-128-CTR", 
            "ServerBlogKey101813112020", 0, "1234567891011121");
            setcookie("passwd", $localPasswd, time()+60*60*24*7);
    
            Services\Helpers::sendToController("/post/feed",[
                "script" => "
                    localStorage.setItem('login', '$username');
                    localStorage.setItem('passwd', '$localPasswd');
                "
            ]);
        }
        else if (empty($user)) 
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

    protected function comprobarUsuario($params = null) 
    {
        if ($_SERVER['REQUEST_METHOD'] == "POST")
        {
            $username = Services\Helpers::cleanInput($_POST['username']);
            $user = Models\User::userExists($username);

            if(!empty($user)){
                //? Si el usuario existe = false
                $response = true;
                echo json_encode($response);
            }else{
                //? Si el usuario no existe = true
                $response = false;
                echo json_encode($response);
            }
        }
    }

    protected function comprobarContraseñaDiferente($params = null) 
    {
        if ($_SERVER['REQUEST_METHOD'] == "POST")
        {
            $password = Services\Helpers::cleanInput($_POST['password']);
            $rpassword = Services\Helpers::cleanInput($_POST['rpassword']);
            
            if(empty($password) || empty($rpassword)){
                //? Si alguno de los dos campos esta vacio
                $response = false;
                echo json_encode($response);
            }else if($password != $rpassword){
                //? Si las contraseñas no son iguales
                $response = true;
                echo json_encode($response);
            }else{
                //? Si esta todo correcto
                $response = false;
                echo json_encode($response);
            }
        }
    }
}