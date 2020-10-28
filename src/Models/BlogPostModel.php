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
}