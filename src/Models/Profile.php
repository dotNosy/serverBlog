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

    public static function add(string $id, PDO $PDOconnection)
    {
        try
        {
            //* Se intenta insertar un perfil con el id del usuario recien registrado
            $query = $PDOconnection->prepare("INSERT INTO profile (user_id) VALUES (:id)");
            $query->bindValue("id", $id);
            $query->execute();

            //* Si la query funciona se hacen un commit
            $PDOconnection->commit();
        } 
        catch (Exception $e) 
        {
            //! Si no funciona la query se hace un rollback tanto de perfil como de usuario
            $PDOconnection->rollback();
            throw $e;
        }

        $pdo_conn = NULL;
    }
}