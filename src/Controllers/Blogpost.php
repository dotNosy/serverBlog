<?php

declare(strict_types=1);

namespace ServerBlog\Controllers;

use ServerBlog\Models as Models;
use ServerBlog\Services\Helpers;

use ServerBlog\Models\User;

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
        if(!empty($params[2]) && is_int($params[2]))
        {
            
            Helpers::sendToController("/Blogpost/list",
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
                Helpers::sendToController("/Blogpost/list",
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
                //? enviar a la view SIN PARAMETROS adicionales
                if(!empty($_SESSION['URL_PARAMS']))
                {
                    parent::sendToView([
                        "titulo" => "LIST"
                        ,"list" => $list
                        ,"page" => __DIR__ . '/../Views/BlogPost/List.php'
                    ]
                    ,$_SESSION["URL_PARAMS"]); 
                }
                //? enviar a la view CON PARAMETROS adicionales
                else
                {
                    parent::sendToView([
                        "titulo" => "LIST"
                        ,"list" => $list
                        ,"page" => __DIR__ . '/../Views/BlogPost/List.php'
                    ]); 
                }
            }
            else
            {
                //TODO: No tienes posts
                parent::sendToView([
                    "titulo" => "LIST"
                    ,"list" => $list
                    ,"error" => "No has publicado ningun post todavia."
                    ,"page" => __DIR__ . '/../Views/BlogPost/List.php'
                ]); 
            }
        }
        else
        {
            Helpers::sendToController("/login",
            [
                "error" => "Tienes que estar logueado para ver tus posts."
            ]);
        }  
    }

    //! Todos los posts por orden cronologico
    protected function all(array $params)
    {
        //* Me devuelve de la BD todos los registros del usuario del id
        $list = Models\BlogPostModel::all();
        
        if(!empty($list))
        {
            //? enviar a la view CON PARAMETROS adicionales
            if(!empty($_SESSION['URL_PARAMS']))
            {
                parent::sendToView([
                    "titulo" => "LIST"
                    ,"list" => $list
                    ,"page" => __DIR__ . '/../Views/BlogPost/List.php'
                ]
                ,$_SESSION["URL_PARAMS"]); 
            }
            //? enviar a la view SIN PARAMETROS adicionales
            else
            {
                parent::sendToView([
                    "titulo" => "LIST"
                    ,"list" => $list
                    ,"page" => __DIR__ . '/../Views/BlogPost/List.php'
                ]); 
            }
        }
        else
        {
            //TODO: No hay posts
            parent::sendToView([
                "titulo" => "LIST"
                ,"list" => $list
                ,"error" => "No se han publicado posts todavia."
                ,"page" => __DIR__ . '/../Views/BlogPost/List.php'
            ]); 
        }
    }

    //! Todos los posts de X autor(user)
    protected function author(array $params)
    {
        
    }

    protected function create(array $params = null)
    {
        
    }

    protected function edit(array $params)
    {

    }
}