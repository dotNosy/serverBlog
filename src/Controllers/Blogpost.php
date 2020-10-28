<?php

declare(strict_types=1);

namespace ServerBlog\Controllers;

use ServerBlog\Models as Models;

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

    }

    protected function list(array $params = null)
    {
        //* Se recoge el id del usuario en la sesion actual
        $user = User::getUser();

        if(!empty($user)){
            echo "debug";
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
        else
        {
            parent::sendToView([
                "titulo" => "SIGNING UP"
                ,"css" => array("login.css")
                ,"error" => "Para ver tus posts debes estar registrado."
                ,"page" => __DIR__ . '/../Views/Login.php'
            ]);
        }  
    }

    protected function create(array $params = null)
    {
        
    }

    protected function edit(array $params)
    {

    }
}