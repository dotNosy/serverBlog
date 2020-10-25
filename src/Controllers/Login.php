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
        //! Añadir la informacion para recogerla en las plantillas
        $_SESSION["titulo"] = "LOGIN";
        $_SESSION["css"] = array("login.css");

        //!  Vista a devolver
        $_SESSION["page"] =  __DIR__ . '/../Views/Login.php';

        require_once __PARENT_TEMPLATE__;
        die();
    }

    protected function register($params = null) 
    {
        //? VISTA FORMULARIO
        if ($_SERVER['REQUEST_METHOD'] == "GET")
        {
            //! Añadir la informacion para recogerla en las plantillas
            $_SESSION["titulo"] = "SIGNING UP";
            $_SESSION["css"] = array("register.css");

            //!  Vista a devolver
            $_SESSION["page"] =  __DIR__ . '/../Views/Register.php';

            require_once __PARENT_TEMPLATE__;
            die();
        }

        //? PROCESAR FORMULARIO
        else if ($_SERVER['REQUEST_METHOD'] == "POST")
        {
            if (isset($_POST['register']))
            {
                $username = Services\Helpers::cleanInput($_POST['username']);
                $password = Services\Helpers::cleanInput($_POST['password']);
                $rpassword = Services\Helpers::cleanInput($_POST['rpassword']);

                if (empty($username) || empty($password) || empty($rpassword)) 
                {
                    //! Añadir la informacion para recogerla en las plantillas
                    $_SESSION["titulo"] = "SIGNING UP";
                    $_SESSION["css"] = array("register.css");
                    $_SESSION['error'] = "Uno o mas campos estan vacios";

                    //!  Vista a devolver
                    $_SESSION["page"] =  __DIR__ . '/../Views/Register.php';

                    require_once __PARENT_TEMPLATE__;
                    die();
                }
                else if ($password !== $rpassword)
                {
                    //! Añadir la informacion para recogerla en las plantillas
                    $_SESSION["titulo"] = "SIGNING UP";
                    $_SESSION["css"] = array("register.css");
                    $_SESSION['error'] = "Las contraseñas no coinciden";

                    //!  Vista a devolver
                    $_SESSION["page"] =  __DIR__ . '/../Views/Register.php';

                    require_once __PARENT_TEMPLATE__;
                    die();
                }

                //TODO: Comprobar que la password sea segura

                //TODO: Comprobar si el username existe ya

               if (Models\User::add($username, $password)) 
               {
                    //Logear al user y enviar al perfil
                    $user = Models\User::login($username, $password);

                    if (empty($user)) {
                        $_SESSION["titulo"] = "SIGNING UP";
                        $_SESSION["css"] = array("register.css");
                        $_SESSION['error'] =  "Las credenciales son incorrectas.";
    
                        //!  Vista a devolver
                        $_SESSION["page"] =  __DIR__ . '/../Views/Register.php';
    
                        require_once __PARENT_TEMPLATE__;
                        die();
                    }
                    else if ($user == "error conexion"){
                        $_SESSION["titulo"] = "SIGNING UP";
                        $_SESSION["css"] = array("register.css");
                        $_SESSION['error'] = "Hubo un problema de conexion.";
    
                        //!  Vista a devolver
                        $_SESSION["page"] =  __DIR__ . '/../Views/Register.php';
    
                        require_once __PARENT_TEMPLATE__;
                        die();
                    }
                    else if ($user == "credenciales incorrectas"){
                        $_SESSION["titulo"] = "SIGNING UP";
                        $_SESSION["css"] = array("register.css");
                        $_SESSION['error'] = "Las credenciales son incorrectas.";
    
                        //!  Vista a devolver
                        $_SESSION["page"] =  __DIR__ . '/../Views/Register.php';
    
                        require_once __PARENT_TEMPLATE__;
                        die();
                    }
                    else if (!empty($user) && $user instanceof Models\User) {
                        $_SESSION['user'] = serialize($user);

                        $_SESSION["titulo"] = "HOME";
                        unset($_SESSION["css"]);
    
                        //!  Vista a devolver
                        $_SESSION["page"] =  __DIR__ . '/../Views/Home.php';
    
                        require_once __PARENT_TEMPLATE__;
                        die();
                    }
                    else {
                        $_SESSION["titulo"] = "SIGNING UP";
                        $_SESSION["css"] = array("register.css");
                        $_SESSION['error'] = "Hubo un error inesperado.";
    
                        //!  Vista a devolver
                        $_SESSION["page"] =  __DIR__ . '/../Views/Register.php';
    
                        require_once __PARENT_TEMPLATE__;
                        die();
                    }
               }
               else
               {
                    //! Añadir la informacion para recogerla en las plantillas
                    $_SESSION["titulo"] = "SIGNING UP";
                    $_SESSION["css"] = array("register.css");
                    $_SESSION['error'] = "Hubo un problema de conexion.";

                    //!  Vista a devolver
                    $_SESSION["page"] =  __DIR__ . '/../Views/Register.php';

                    require_once __PARENT_TEMPLATE__;
                    die();
               }
            }
        }
    }
}