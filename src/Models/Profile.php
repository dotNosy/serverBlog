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

    
        // $connObj = new Services\Connection(Services\Helpers::getEnviroment());

        // $pdo_conn = $connObj->getConnection();

        // $query = $pdo_conn->prepare("SELECT id, username, password FROM USER WHERE USERNAME = :username");
        // $query->bindValue("username", $username);

        

    public static function add(string $id, PDO $PDOconnection)
    {
        try
        {
            $query = $PDOconnection->prepare("INSERT INTO profile (user_id) VALUES (:id)");
            $query->bindValue("id", $id);

            $query->execute();

            // $pdo_conn->commit();

            $PDOconnection->commit();

        } catch (Exception $e) {
            $PDOconnection->rollback();
            throw $e;
        }
        $pdo_conn = NULL;
    }

}