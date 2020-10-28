<?php

declare(strict_types=1);

namespace ServerBlog\Controllers;

use ServerBlog\Models as Models;

use ServerBlog\Models\User;

use ServerBlog\Services\Helpers;

class Blogpost extends Controller
{
    public function __construct(array $params = null) 
    {
        parent::__construct($params);
    }

    protected function index(array $params = null) 
    {
        echo "index";
    }

    protected function view(array $params)
    {

    }

    protected function list(array $params = null)
    {
        //* Se recoge el id del usuario en la sesion actual
        $user = User::getUser();

        if(!empty($user))
        {
            //* Me devuelve de la BD todos los registros del usuario del id
            $list = Models\BlogPostModel::list(intval($user->id));
            
            if(!empty($list))
            {
                //* Te envia a la view de la lista
                parent::sendToView([
                    "titulo" => "LIST"
                    ,"list" => $list
                    ,"page" => __DIR__ . '/../Views/BlogPost/List.php'
                ]);
            }
            else
            {
                //TODO: No tienes posts
            }
        }
        //? Usuario no logueado
        else
        {
            Helpers::sendToController("/login/index"
            ,[
                "error" => "Para ver tus posts debes estar registrado."
             ]);
        }  
    }

    protected function add(array $params = null)
    {
        $user = User::getUser();

        if(!empty($user))
        {
            if ($_SERVER['REQUEST_METHOD'] == "POST" && isset($_POST['add']))
            {
                $tituloPost = Helpers::cleanInput($_POST['titulo']);
                $mensajePost = Helpers::cleanInput($_POST['mensaje']);
                $radioPost = Helpers::cleanInput($_POST['visibleRadio']);
    
                //? Si el radibutton no devulve 0 o 1
                if($radioPost!=0 && $radioPost!=1){
                    //TODO: Poner datos incorrectos en rojo
                }
                //? Error titulo o mensaje vacio
                else if (empty($tituloPost) || empty($mensajePost)) 
                {   
                    // TODO: Ponerle en rojo los datos que estan vacios
                }
                //? Creacion del post
                else
                {
                    //* Va al model para intentar crear el post
                    if(Models\BlogPostModel::add(intval($user->id),$tituloPost,$mensajePost,intval($radioPost)))
                    {
                        echo "insertado";
                    }
                    else
                    {
                        echo "no insertado";
                    }
    
                    //TODO: Enviar a la funcion view que te devuelve el post que se acaba de crear
                    echo $tituloPost . "<br>";
                    echo $mensajePost . "<br>";
                    echo $radioPost . "<br>";
    
                }
            }
            //? Si no se ha enviado el formulario de creacion de post te devuelve la vista para crearlo
            else 
            {
                parent::sendToView([
                    "titulo" => "ADD POST"
                    ,"page" => __DIR__ . '/../Views/BlogPost/Add.php'
                ]);
            }
        }
        //? Usuario no logueado
        else
        {
            Helpers::sendToController("/login/index"
            ,[
                "error" => "Para crear un post debes estar logeado."
             ]);
        }     
    }

    protected function edit(array $params)
    {

    }
}