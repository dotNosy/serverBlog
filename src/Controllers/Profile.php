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
    
    // La funcion index crea un Perfil por cada Usuario registrado
    protected function index(array $params = null) 
    {
        // El perfil se abrirá solo cuando haya un usuario logueado
        if (!empty($_SESSION['user']))
        {
            //* Se guarda el usuario logueado
            $user = Models\User::getUser();
            //* El profile se crea con el id de el usuario
            $profile = Models\Profile::getProfile(intval($user->id));

            parent::sendToView([
                "titulo" => "TEST CONTROLLER"
                ,"profile" => $profile
                ,"page" =>  __DIR__ . '/../Views/Profile.php'
            ]);
        }
        // Si no hay un usuario logueado te enviará al login
        else
        {
            //* No es un post tuyo
            Services\Helpers::sendToController("/login",
            [
                "error" => "Para ver tu perfil debes estar logeado."
            ]); 
        }
    }

    // Para editar los campos de el Perfil del Usuario Logeado
    public function edit($params = null)
    {
        //* Se guarda el usuario logeado
        $user = Models\User::getUser();

        if ($_SERVER['REQUEST_METHOD'] == "POST" && isset($_POST['update']))
        {
            //? Error credenciales vacias
            // Recoge los datos que se usarán para modificar el perfil
            $name = Services\Helpers::cleanInput($_POST['name']);
            $surname = Services\Helpers::cleanInput($_POST['surname']);
            $email = Services\Helpers::cleanInput($_POST['email']);
            $birthdate = Services\Helpers::cleanInput($_POST['date']);

            $imgsContent="";
                //! ADD IMAGENES
                if (!empty($_FILES['avatar']))
                {
                    $allowedExtensions = array('jpg','png','gif');
                    $imgsContent = Services\Helpers::getFilesContent($_FILES['avatar'], $allowedExtensions);      
                }
                
            // Llama a la función edit de parametros que la cual hace la sentencia de UPDATE en mysql con los datos recogidos. Esta solo funciona si los datos se recogen correctamente.
            if(Models\Profile::edit($user->id, $name, $surname, $email, $birthdate, $imgsContent)){
                // Guarda la id de el usuario
                $profile = Models\Profile::getProfile(intval($user->id));

                Services\Helpers::sendToController("/profile/");
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
            if(!empty($password) && !empty($repeatPassword))
            {
                if($password == $repeatPassword)
                {
                    if(Models\Profile::changePassword($user->id, $password))
                    {
                        Services\Helpers::sendToController("/login/logout",
                        [
                        "error" => "Para ver tu perfil debes estar logeado."
                        ]);
                        
                    }
                    else
                    //,"error" => "Uno o mas campos estan vacios"
                    {
                        echo "Los datos no se han modificado";
                        
                        
                    }
                }
                else
                {
                    $profile = Models\Profile::getProfile(intval($user->id));
                        parent::sendToView([
                        "titulo" => "TEST CONTROLLER"
                        ,"profile" => json_encode($profile)
                        ,"errorContraseña" => "Las contraseñas debes ser iguales"
                        ,"page" =>  __DIR__ . '/../Views/Profile.php'
                    ]);
                }
            }
            else{
                $profile = Models\Profile::getProfile(intval($user->id));
                        parent::sendToView([
                        "titulo" => "TEST CONTROLLER"
                        ,"profile" => json_encode($profile)
                        ,"errorContraseña" => "Para cambiar la contraseña rellena los campos"
                        ,"page" =>  __DIR__ . '/../Views/Profile.php'
                    ]);
            }
            
        }
        else 
        {
            Services\Helpers::sendTo404();
        }
    }


}

    