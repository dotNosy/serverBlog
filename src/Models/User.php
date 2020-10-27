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

        $query = $pdo_conn->prepare("SELECT id, username, password FROM user WHERE username = :username");
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

    public static function logout() 
    {
        if (isset($_SESSION['user']))
        {
            unset($_SESSION['user']);
        }
        
        session_destroy();
    }

    public static function getUser()
    {
        if (!empty($_SESSION['user'])) {
            return unserialize($_SESSION['user']);
        }
        else {
            return null;
        }
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
        
        $pdo_conn->beginTransaction();

        try
        {
            $query = $pdo_conn->prepare("INSERT INTO user (username, password) VALUES (:username, :password)");
            $query->bindValue("username", $username);
            $query->bindValue("password", password_hash($password, PASSWORD_BCRYPT));

            $query->execute();

            $id = $pdo_conn->lastInsertId();

            Profile::add($id, $pdo_conn);

            $pdo_conn->commit();

            return true;
        }
        catch(Exception $e) 
        {
            $pdo_conn->rollback();
            throw $e;
            return false;
        }

        $pdo_conn = NULL;
    }

    public static function userExists(string $username)
    {
        $connObj = new Services\Connection(Services\Helpers::getEnviroment());
        $pdo_conn = $connObj->getConnection();

        $query = $pdo_conn->prepare("SELECT * FROM user WHERE username=:username");
        $query->bindValue("username", $username);

        if (!$query->execute()) {
            //! Si no se ha podido hacer la consulta
            //TODO: Hacer algo si no se ha podido hacer la consulta
        }
        //? Hay resultado
        if ($query->rowCount() > 0) {
            return true;
        }
        //? No hay resultado
        else {
            return false;
        }

        $pdo_conn = NULL;
    }
}