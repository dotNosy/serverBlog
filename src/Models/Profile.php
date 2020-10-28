<?php

declare(strict_types=1);

namespace ServerBlog\Models;

use ServerBlog\Services as Services;
use \PDO;

class Profile
{
    private string $username;
    private int $id;

    public function __construct($id) 
    {
        $this->id = $id;
    }

    public static function add(int $id, PDO &$PDOconnection)
    {
        try
        {   
            //* Se intenta insertar un perfil con el id del usuario recien registrado
            $query = $PDOconnection->prepare("INSERT INTO profile (user_id) VALUES (:id)");
            $query->bindValue("id", $id);
            if($query->execute())
            {
                //* Si la query funciona se hacen un commit
                $PDOconnection->commit();
            }else
            {
                $PDOconnection->rollback();
            }            
        } 
        catch (PDOException $e) 
        {
            //! Si no funciona la query se hace un rollback tanto de perfil como de usuario
            echo "Connection failed: " . $e->getMessage();
            die();
            $PDOconnection->rollback();
            throw $e;
        }
    }
}