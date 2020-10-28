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
            //* obtener la row como objeto
            $user = $query->fetch(PDO::FETCH_OBJ);

            if (!empty($user)) 
            {
                //? Si el hash de las contraseñas coincide, Return una instancia del objeto User
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
        if (isset($_SESSION['user']) || !empty($_SESSION['user']))
        {
            unset($_SESSION['user']);
        }
        
        session_destroy();
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

            //* Se guarda el ultimo id insertado (el del registro)
            $id = $pdo_conn->lastInsertId();

            //* Se añade un perfil para el usuario recien registrado
            Profile::add(intval($id), $pdo_conn);

            //* Si todo sale bien, se hace un commit de las dos tablas
            //$pdo_conn->commit();

            return true;
        }
        catch(Exception $e) 
        {
            //! Si no sale bien se hace un rollback de todas las transacciones (tanto usuario como perfil)
            $pdo_conn->rollback();
            throw $e;
            return false;
        }

        $pdo_conn = NULL;
    }

    //* Funcion que busca si el usuario con el que se intenta registrar existe
    public static function userExists(string $username)
    {
        $connObj = new Services\Connection(Services\Helpers::getEnviroment());
        $pdo_conn = $connObj->getConnection();

        //* Se busca el usuario introducido
        $query = $pdo_conn->prepare("SELECT * FROM user WHERE username=:username");
        $query->bindValue("username", $username);

        //! Si no se ha podido hacer la consulta
        if (!$query->execute()) {
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

    public static function getUser()
    {
        //? Devolver objeto $user como json
        if (!empty($_SESSION['user'])) {
            return json_decode($_SESSION['user']);
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
}