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
                
            }
            else {
                return "error conexion";
            }
        $pdo_conn = NULL;
    }

    public static function add(int $id, string $titulo, string $mensaje, int $visible)
    {
        // //* Se recoge el id del usuario en la sesion actual

        $today = date("Y/m/d h:i:s");

        $connObj = new Services\Connection(Services\Helpers::getEnviroment());

        $pdo_conn = $connObj->getConnection();

        //    //* Se hace un insert con los datos del post

                $query = $pdo_conn->prepare("INSERT INTO post (user_id, title, text, date, visible) VALUES (:user_id, :titulo, :mensaje, :today, :visible)");
                $query->bindValue("user_id", $id);
                $query->bindValue("titulo", $titulo);
                $query->bindValue("mensaje", $mensaje);
                $query->bindValue("today", $today);
                $query->bindValue("visible", $visible);

            if($query->execute())
            {
                //* Si la query funciona se hacen un commit
                $pdo_conn->commit();
                return true;
            }else
            {
                $pdo_conn->rollback();
                return false;
            }  
            // //? Usuario no logueado
        $pdo_conn = NULL; 
    }
        
}