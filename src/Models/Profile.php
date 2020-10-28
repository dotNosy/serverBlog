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

    public static function updateId(string $id, PDO $PDOconnection)
    {
        try
        {
            $query = $PDOconnection->prepare("UPDATE profile (user_id) VALUES (:id)");
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

    public static function addName(string $name, PDO $PDOconnection)
    {
        try
        {
            $query = $PDOconnection->prepare("INSERT INTO profile (name) VALUES (:name)");
            $query->bindValue("name", $name);

            $query->execute();

            // $pdo_conn->commit();

            $PDOconnection->commit();

        } catch (Exception $e) {
            $PDOconnection->rollback();
            throw $e;
        }
        $pdo_conn = NULL;
    }

    public static function updateName(string $name, PDO $PDOconnection)
    {
        try
        {
            $query = $PDOconnection->prepare("UPDATE profile (name) VALUES (:name)");
            $query->bindValue("name", $name);

            $query->execute();

            // $pdo_conn->commit();

            $PDOconnection->commit();

        } catch (Exception $e) {
            $PDOconnection->rollback();
            throw $e;
        }
        $pdo_conn = NULL;
    }

    public static function addSurname(string $surname, PDO $PDOconnection)
    {
        try
        {
            $query = $PDOconnection->prepare("INSERT INTO profile (surname) VALUES (:surname)");
            $query->bindValue("surname", $surname);

            $query->execute();

            // $pdo_conn->commit();

            $PDOconnection->commit();

        } catch (Exception $e) {
            $PDOconnection->rollback();
            throw $e;
        }
        $pdo_conn = NULL;
    }

    public static function updateSurname(string $surname, PDO $PDOconnection)
    {
        try
        {
            $query = $PDOconnection->prepare("UPDATE profile (surname) VALUES (:surname)");
            $query->bindValue("surname", $surname);

            $query->execute();

            // $pdo_conn->commit();

            $PDOconnection->commit();

        } catch (Exception $e) {
            $PDOconnection->rollback();
            throw $e;
        }
        $pdo_conn = NULL;
    }

    public static function addEmail(string $email, PDO $PDOconnection)
    {
        try
        {
            $query = $PDOconnection->prepare("INSERT INTO profile (email) VALUES (:email)");
            $query->bindValue("email", $email);

            $query->execute();

            // $pdo_conn->commit();

            $PDOconnection->commit();

        } catch (Exception $e) {
            $PDOconnection->rollback();
            throw $e;
        }
        $pdo_conn = NULL;
    }

    public static function updateEmail(string $email, PDO $PDOconnection)
    {
        try
        {
            $query = $PDOconnection->prepare("UPDATE profile (email) VALUES (:email)");
            $query->bindValue("email", $email);

            $query->execute();

            // $pdo_conn->commit();

            $PDOconnection->commit();

        } catch (Exception $e) {
            $PDOconnection->rollback();
            throw $e;
        }
        $pdo_conn = NULL;
    }

    public static function addBirth(string $birthdate, PDO $PDOconnection)
    {
        try
        {
            $query = $PDOconnection->prepare("INSERT INTO profile (birth_date) VALUES (:birthdate)");
            $query->bindValue("birthdate", $birthdate);

            $query->execute();

            // $pdo_conn->commit();

            $PDOconnection->commit();

        } catch (Exception $e) {
            $PDOconnection->rollback();
            throw $e;
        }
        $pdo_conn = NULL;
    }

    public static function updateBirth(string $birthdate, PDO $PDOconnection)
    {
        try
        {
            $query = $PDOconnection->prepare("UPDATE profile (birth_date) VALUES (:birthdate)");
            $query->bindValue("birthdate", $birthdate);

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