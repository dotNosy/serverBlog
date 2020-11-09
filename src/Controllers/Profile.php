<?php

declare(strict_types=1);

namespace ServerBlog\Controllers;

use ServerBlog\Services as Services;

use ServerBlog\Models as Models;

use ServerBlog\Models\User;

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

    protected function notifications($params = null)
    {

        $user = User::getUser();

        if(!empty($user))
        {    
            $notificaciones = Models\Profile::getNotifications(intval($user->id));

            if(!empty($notificaciones)){

                $response['notificaciones'] = array();
                $accion = "";
                $color = "";
                foreach($notificaciones as $not)
                {
                    
                    switch ($not->type_id) {
                        case 1:
                            $accion = "añadido a favoritos tu";
                            $color = "danger";
                            break;
                        case 2:
                            $accion = "retweeteado tu";
                            $color = "primary";
                            break;
                        case 3:
                            $accion = "comentado en tu";
                            $color = "warning";
                            break;
                        case 4:
                            $accion = "respondido a tu comentario en el";
                            $color = "dark";
                        break;
                        
                        default:
                            break;
                    }

                    if($not->Leido == 1){
                        $color = "light";
                    }

                    array_push($response['notificaciones'],"
                    <div class='alert alert-". $color ."' role='alert'>
                        <button id='$not->id' type='button' class='deleteNotifications close' data-dismiss='alert' aria-label='Close'>
                            <span class='' aria-hidden='true'>&times;</span>
                        </button>
	                    <h4 class='alert-heading'>Well done!</h4>
                        <p><a style='color:black' href='/post/author/$not->Nombre' class='alert-link'>". $not->Nombre ."</a> 
                        ha ". $accion ." post '<a style='color:black' href='/post/view/$not->post_id' class='alert-link'>". $not->title ."</a>'.</p>
                        <hr>
                        <p class='mb-0'>". $not->date ."</p>
                    </div>");
                }

                $marcadasComoLeido = Models\Profile::marcarNotificacionesComoLeido(intval($user->id));

            }
            else {
                $response['notificaciones'] = ["<div class='alert alert-danger alert-dismissible fade show' role='alert'>
                <strong>No tienes notificaciones pendientes.</strong> 
                <button type='button' class='close' data-dismiss='alert' aria-label='Close'>
                  <span aria-hidden='true'>&times;</span>
                </button>
              </div>"];
            }
                
            echo json_encode($response);
        }
        //? No estas logueado
        else
        {
            Services\Helpers::sendToController("/login",
            [
                "error" => "Tienes que estar logueado para ver tus notificaciones."
            ]);
        }  
    }

    protected function deleteNotificationsByNotificationID($params = null)
    {

        $user = User::getUser();

        if(!empty($user))
        {    

            if ($_SERVER['REQUEST_METHOD'] == "POST" && isset($_POST['id']))
            {

                if(!empty($_POST['id'])){
                    $response['status'] = Models\Profile::deleteNotificationsByNotificationID(intval($_POST["id"]));
                }
                else
                {
                    $response['error'] = "Ha ocurrido un error inesperado";
                }

            }
            else
            {
                $response['error'] = "Ha ocurrido un error inesperado";
            }
            echo json_encode($response);
        }
        //? No estas logueado
        else
        {
            Services\Helpers::sendToController("/login",
            [
                "error" => "Tienes que estar logueado para ver tus notificaciones."
            ]);
        }  
    }

    protected function deleteNotificationsByUserID($params = null)
    {

        $user = User::getUser();

        if(!empty($user))
        {    

            if ($_SERVER['REQUEST_METHOD'] == "POST")
            {

                $response['status'] = Models\Profile::deleteNotificationsByUserID(intval($user->id));

            }
            else
            {
                $response['error'] = "Ha ocurrido un error inesperado";
            }

            echo json_encode($response);
        }
        //? No estas logueado
        else
        {
            Services\Helpers::sendToController("/login",
            [
                "error" => "Tienes que estar logueado para ver tus notificaciones."
            ]);
        }  
    }

}

    