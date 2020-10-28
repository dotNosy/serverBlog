<?php

declare(strict_types=1);

namespace ServerBlog\Models;

use ServerBlog\Services as Services;
use \PDO;

class BlogPostModel
{
    private int $id;

    public function __construct($id) 
    {
        $this->id = $id;
    }

    public static function list(int $id)
    {
        $connObj = new Services\Connection(Services\Helpers::getEnviroment());

        $pdo_conn = $connObj->getConnection();

        //* Se recogen los posts de la persona logeada actualmente
        $query = $pdo_conn->prepare("SELECT id, title, text, date FROM post WHERE user_id = :user_id");
        $query->bindValue("user_id", $id);

        if ($query->execute())
        {
            //* Mete todos los datos en un array
            $list = $query->fetchAll();

            //* Si esta vacia te devuelve NULL y sino te devuelve un array con todos los posts del usuario logeado
            return $list;
        }
        else {
            return null;
        }

        $pdo_conn = NULL;
    }

    public static function all()
    {
        $connObj = new Services\Connection(Services\Helpers::getEnviroment());

        $pdo_conn = $connObj->getConnection();

        //* Se recogen todos los posts de la base de datos
        $query = $pdo_conn->prepare("SELECT id, user_id, title, text, date FROM post ORDER BY date DESC");
        
        if ($query->execute())
        {
            //* Mete todos los datos en un array
            $list = $query->fetchAll();

            //* Si esta vacia te devuelve NULL y sino te devuelve un array con todos los posts del usuario logeado
            return $list;
        }
        else {
            return null;
        }

        $pdo_conn = NULL;
    }

    public static function view(int $id)
    {
        $connObj = new Services\Connection(Services\Helpers::getEnviroment());

        $pdo_conn = $connObj->getConnection();

        //* Se recogen los posts de la persona logeada actualmente
        $query = $pdo_conn->prepare("SELECT id, user_id, title, text, date FROM post WHERE id = :post_id AND visible=1");
        $query->bindValue("post_id", $id);

        if ($query->execute())
        {
            //* Mete todos los datos en un array
            $post = $query->fetch(PDO::FETCH_OBJ);

            //* Si esta vacia te devuelve NULL y sino te devuelve un array con todos los posts del usuario logeado
            return $post;
        }
        else {
            return null;
        }

        $pdo_conn = NULL;
    }

    public static function feed(int $id)
    {
        $connObj = new Services\Connection(Services\Helpers::getEnviroment());

        $pdo_conn = $connObj->getConnection();

        //* Se recogen los posts de la persona logeada actualmente
        $query = $pdo_conn->prepare("SELECT id, user_id, title, text, date FROM post WHERE id = :post_id AND visible=1");
        $query->bindValue("post_id", $id);

        if ($query->execute())
        {
            //* Mete todos los datos en un array
            $post = $query->fetch(PDO::FETCH_OBJ);

            //* Si esta vacia te devuelve NULL y sino te devuelve un array con todos los posts del usuario logeado
            return $post;
        }
        else {
            return null;
        }

        $pdo_conn = NULL;
    }
}