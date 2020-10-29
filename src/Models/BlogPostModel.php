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

    public static function favorites(int $id)
    {
        $connObj = new Services\Connection(Services\Helpers::getEnviroment());

        $pdo_conn = $connObj->getConnection();

        //* Se hacen dos querys 
        $query = $pdo_conn->prepare("SELECT post.id, fav.`date` as date, post.title as title, post.`text` as text FROM post
                INNER JOIN `like` as fav ON post.id = fav.post_id
                WHERE fav.user_id = :user_id ORDER BY `date` DESC");

        $query->bindValue("user_id", $id);

        if ($query->execute())
        {
            //* Mete todos los datos en un array
            $feed = $query->fetchAll();

            //* Si esta vacia te devuelve NULL y sino te devuelve un array con todos los posts del usuario logeado
            return $feed;
        }
        else {
            return null;
        }

        $pdo_conn = NULL;
    }

    public static function addToFavorites(int $id, int $user_id)
    { 
        $connObj = new Services\Connection(Services\Helpers::getEnviroment());

        $pdo_conn = $connObj->getConnection();
        $pdo_conn->beginTransaction();

        try
        {
            //* Se recogen los posts de la persona logeada actualmente
            $query = $pdo_conn->prepare("INSERT INTO `like` (post_id, user_id, date) VALUES(:post, :user, NOW())");
            $query->bindValue("post", $id);
            $query->bindValue("user", $user_id);

            if ($query->execute())
            {
                //* Mete todos los datos en un array
                $pdo_conn->commit();

                return true;
            }
            else
            {
                $pdo_conn->rollback();
                return false;
            }
        }
        catch (Exception $e)
        {
            $pdo_conn->rollback();
            throw $e;
            return false;
        }

        $pdo_conn = NULL;
    }

    public static function deleteFromFavorites(int $id, int $user_id)
    { 
        $connObj = new Services\Connection(Services\Helpers::getEnviroment());

        $pdo_conn = $connObj->getConnection();
        $pdo_conn->beginTransaction();

        try
        {
            //* Se recogen los posts de la persona logeada actualmente
            $query = $pdo_conn->prepare("DELETE FROM `like` WHERE post_id = :post AND user_id = :user");
            $query->bindValue("post", $id);
            $query->bindValue("user", $user_id);

            if ($query->execute())
            {
                //* Mete todos los datos en un array
                $pdo_conn->commit();

                return true;
            }
            else
            {
                $pdo_conn->rollback();
                return false;
            }
        }
        catch (Exception $e)
        {
            $pdo_conn->rollback();
            throw $e;
            return false;
        }

        $pdo_conn = NULL;
    }

    public static function isInFavorites(int $id, int $user_id)
    {
        $connObj = new Services\Connection(Services\Helpers::getEnviroment());

        $pdo_conn = $connObj->getConnection();
        try
        {
            //* Se recogen los posts de la persona logeada actualmente
            $query = $pdo_conn->prepare("SELECT ID FROM `like` WHERE post_id = :post AND user_id = :user");
            $query->bindValue("post", $id);
            $query->bindValue("user", $user_id);

            if ($query->execute())
            {   
                //* Mete todos los datos en un array
                $post = $query->fetch(PDO::FETCH_OBJ);
                
                if (!empty($post)) {
                    return true;
                }
                else {
                    return false;
                }
            }
        }   
        catch (Exception $e)
        {
            throw $e;
            return false;
        }

        $pdo_conn = NULL;
    }

    public static function feed(int $id)
    {
        $connObj = new Services\Connection(Services\Helpers::getEnviroment());

        $pdo_conn = $connObj->getConnection();

        //* Se hacen dos querys 
        $query = $pdo_conn->prepare("(SELECT id, date as date, title as title, text as text FROM post WHERE user_id = :user_id)
            UNION
            (SELECT post.id, retweet.`date` as date, post.title as title, post.`text` as text FROM post
            INNER JOIN retweet ON post.id = retweet.post_id
            WHERE retweet.user_id = :user_id) ORDER BY `date` DESC");
        $query->bindValue("user_id", $id);

        if ($query->execute())
        {
            //* Mete todos los datos en un array
            $feed = $query->fetchAll();

            //* Si esta vacia te devuelve NULL y sino te devuelve un array con todos los posts del usuario logeado
            return $feed;
        }
        else {
            return null;
        }

        $pdo_conn = NULL;
    }

    public static function addToFeed(int $id, int $user_id)
    {
        $connObj = new Services\Connection(Services\Helpers::getEnviroment());

        $pdo_conn = $connObj->getConnection();
        $pdo_conn->beginTransaction();

        try
        {
            //* Se recogen los posts de la persona logeada actualmente
            $query = $pdo_conn->prepare("INSERT INTO retweet (post_id, user_id, date) VALUES(:post, :user, NOW()) ");
            $query->bindValue("post", $id);
            $query->bindValue("user", $user_id);

            if ($query->execute())
            {
                //* Mete todos los datos en un array
                $pdo_conn->commit();
                return true;
            }
            else {
                $pdo_conn->rollback();
                return false;
            }
        }
        catch (Exception $e)
        {
            throw $e;
            return false;
        }

        $pdo_conn = NULL;
    }

    public static function deleteFromFeed(int $id, int $user_id)
    { 
        $connObj = new Services\Connection(Services\Helpers::getEnviroment());

        $pdo_conn = $connObj->getConnection();
        $pdo_conn->beginTransaction();

        try
        {
            //* Se recogen los posts de la persona logeada actualmente
            $query = $pdo_conn->prepare("DELETE FROM retweet WHERE post_id = :post AND user_id = :user");
            $query->bindValue("post", $id);
            $query->bindValue("user", $user_id);

            if ($query->execute())
            {
                //* Mete todos los datos en un array
                $pdo_conn->commit();

                return true;
            }
            else
            {
                $pdo_conn->rollback();
                return false;
            }
        }
        catch (Exception $e)
        {
            $pdo_conn->rollback();
            throw $e;
            return false;
        }

        $pdo_conn = NULL;
    }

    public static function isInFeed(int $id, int $user_id)
    {
        $connObj = new Services\Connection(Services\Helpers::getEnviroment());

        $pdo_conn = $connObj->getConnection();

        //* Se recogen los posts de la persona logeada actualmente
        $query = $pdo_conn->prepare("SELECT ID FROM retweet WHERE post_id = :post AND user_id = :user");
        $query->bindValue("post", $id);
        $query->bindValue("user", $user_id);

        if ($query->execute())
        {
            //* Mete todos los datos en un array
            $post = $query->fetch(PDO::FETCH_OBJ);

            if (!empty($post)) {
                return true;
            }
            else {
                return false;
            }
        }
        else {
            return false;
        }

        $pdo_conn = NULL;
    }

    public static function add(int $id, string $titulo, string $mensaje, int $visible)
    {
        // //* Se recoge el id del usuario en la sesion actual

        $today = date("Y/m/d h:i:s");

        $connObj = new Services\Connection(Services\Helpers::getEnviroment());

        $pdo_conn = $connObj->getConnection();

        $pdo_conn->beginTransaction();

        try {
            //* Se hace un insert con los datos del post a crear
            $query = $pdo_conn->prepare("INSERT INTO post (user_id, title, text, date, visible) VALUES (:user_id, :titulo, :mensaje, NOW(), :visible)");
            $query->bindValue("user_id", $id);
            echo $id . "<br>";
            $query->bindValue("titulo", $titulo);
            echo $titulo . "<br>";
            $query->bindValue("mensaje", $mensaje);
            echo $mensaje . "<br>";
            $query->bindValue("visible", $visible, PDO::PARAM_INT);
            echo $visible . "<br>";

            //* Si la query funciona se hacen un commit
            if($query->execute())
            {
                //* Se coge el id del post insertado para abrirlo al crearlo
                $id_post = $pdo_conn->lastInsertId();
                
                $pdo_conn->commit();
                return $id_post;
            }
            else
            {
                
                $pdo_conn->rollback();
                return false;
            }  
        } 
        catch (\Throwable $th) {
            echo $th;
            $pdo_conn->rollback();
            return false;
        }
        
        //? Usuario no logueado
        $pdo_conn = NULL; 
    } 
}