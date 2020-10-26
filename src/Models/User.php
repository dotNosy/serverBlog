<?php

declare(strict_types=1);

namespace ServerBlog\Models;

use ServerBlog\Services as Services;
use \PDO;

class User
{
    private string $username;
    private int $id;

    public function __construct($id, $username) 
    {
        $this->id = $id;

        $this->username = $username;
    }

    public static function login($username, $password)
    {
        $connObj = new Services\Connection(Services\Helpers::getEnviroment());

        $pdo_conn = $connObj->getConnection();

        $query = $pdo_conn->prepare("SELECT id, username, password FROM USER WHERE USERNAME = :username");
        $query->bindValue("username", $username);

        if ($query->execute())
        {
            $user = $query->fetch(PDO::FETCH_OBJ);

            if (!empty($user)) 
            {
                if (password_verify($password, $user->password)) {
                    return new self(intval($user->id), $user->username);
                }
                else {
                    return "credenciales incorrectas";
                }
            }
            else {
                return "credenciales incorrectas";
            }
        }
        else {
            return "error conexion";
        }

        $pdo_conn = null;
    }

    public function logout() 
    {

    }

    public function getUsername () :string
    {
        return $this->username;
    }

    public function getId () :int
    {
        return $this->id;
    }

    public static function add(string $username, string $password)
    {
        $connObj = new Services\Connection(Services\Helpers::getEnviroment());

        $pdo_conn = $connObj->getConnection();

        $query = $pdo_conn->prepare("INSERT INTO user (username, password) VALUES (:username, :password)");
        $query->bindValue("username", $username);
        $query->bindValue("password", password_hash($password, PASSWORD_BCRYPT));
        
        if ($query->execute()) {
            return true;
        }
        else{
            return false;
        }

        $pdo_conn = NULL;
    }
}