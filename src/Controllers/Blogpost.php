<?php

declare(strict_types=1);

namespace ServerBlog\Controllers;

use ServerBlog\Models as Models;
use ServerBlog\Services\Helpers;

use ServerBlog\Models\User;

class Blogpost extends Controller
{
    //! Desde la URL este controlador tiene de alias post
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
        //? La posicion 2 es el id del post
        if(!empty($params[2]) && is_int($params[2]))
        {
            Helpers::sendToController("/post/all",
            [
                "error" => "No existe este post."
            ]);
        }
        else
        {
            $view = Models\BlogPostModel::view(intval($params[2]));
            if(!empty($view))
            {
                parent::sendToView([
                    "titulo" => "POST"
                    ,"css" => array("post.css")
                    ,"blogPost" => json_encode($view)
                    ,"autor" => User::getUsernameById(intval($view->user_id))
                    ,"page" => __DIR__ . '/../Views/BlogPost/View.php'
                ]);
            }
            else
            {
                Helpers::sendToController("/post/all",
                [
                    "error" => "No existe este post."
                ]);
            }
        }   
    }

    //! Los possts del usuario logueado
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
                parent::sendToView([
                    "titulo" => "LIST"
                    ,"list" => $list
                    ,"page" => __DIR__ . '/../Views/BlogPost/List.php'
                ]); 
            }
            else
            {
                parent::sendToView([
                    "titulo" => "LIST"
                    ,"list" => $list
                    ,"error" => "No has publicado ningun post todavia."
                    ,"page" => __DIR__ . '/../Views/BlogPost/List.php'
                ]); 
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
    }

    //! Todos los posts por orden cronologico
    protected function all(array $params)
    {
        //* Todos los posts, los nuevos primero
        $list = Models\BlogPostModel::all();
        
        if(!empty($list))
        {
            parent::sendToView([
                "titulo" => "LIST"
                ,"list" => $list
                ,"page" => __DIR__ . '/../Views/BlogPost/List.php'
            ]); 
        }
        else
        {
            parent::sendToView([
                "titulo" => "LIST"
                ,"list" => $list
                ,"error" => "No se han publicado posts todavia."
                ,"page" => __DIR__ . '/../Views/BlogPost/List.php'
            ]); 
        }
    }

    protected function feed(array $params)
    {
        //* Se recoge el id del usuario en la sesion actual
        $user = User::getUser();

        if(!empty($user))
        {
            //* Me devuelve de la BD todos los registros del usuario del id
            $feed = Models\BlogPostModel::feed(intval($user->id));
            
            if(!empty($feed))
            {   
                parent::sendToView([
                    "titulo" => "FEED"
                    ,"feed" => $feed
                    ,"page" => __DIR__ . '/../Views/BlogPost/Feed.php'
                ]); 
            }
            else
            {
                parent::sendToView([
                    "titulo" => "LIST"
                    ,"feed" => $feed
                    ,"error" => "Tu feed está vacio."
                    ,"page" => __DIR__ . '/../Views/BlogPost/Feed.php'
                ]); 
            }
        }
        //? No estas logueado
        else
        {
            Helpers::sendToController("/login",
            [
                "error" => "Tienes que estar logueado para ver tu feed."
            ]);
        }  
    }

    //! Todos los posts de X autor(user)
    protected function author(array $params)
    {
        
    }

    protected function create(array $params = null)
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

    protected function favorites($params = null)
    {
        if ($_SERVER['REQUEST_METHOD'] == "POST" && isset($_POST['type']))
        {
            $type = Helpers::cleanInput($_POST['type']);
            
            if ($type == "favoritos")
            {
                echo "favoritos stuff";
            }
            else if($type == "feed")
            {
                echo "feed stuff";
            }
        }
    }
}