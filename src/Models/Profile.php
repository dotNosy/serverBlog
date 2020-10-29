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

            if($query->execute()) {
                //* Si la query funciona se hacen un commit
                $PDOconnection->commit();
            }
            else {
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

    public static function updateProfile(int $id, string $name, string $surname, string $email,string $birthdate, PDO $PDOconnection)
    {
        try
        {   
            $stm = "";
            //name=:name, surname=:surname, email=:email, birth_date=:birth_date

            if (!empty($name)) {
                $stm += " name=:name";
            }
            if (!empty($surname)) {
                $stm += " ,surname=:surname";
            }
            if (!empty($email)) {
                $stm += " ,email=:email";
            }
            if (!empty($birthdate)) {
                $stm += " ,birth_date=:birth_date";
            }

            $query = $PDOconnection->prepare("UPDATE profile SET $stm WHERE user_id=:user_id");
            //El :name guarda el contenido de $name
            $query->bindValue("name", $name);
            $query->bindValue("surname", $surname);
            $query->bindValue("email", $email);
            $query->bindValue("birth_date", $birthdate);
            $query->bindValue("user_id", $id);

            $query->execute();

            $PDOconnection->commit();

        } catch (Exception $e) {
            $PDOconnection->rollback();
            throw $e;
        }

        $PDOconnection = NULL;
    }

    public static function prueba()
    {
        try
        {   
            $name= "juantxo";
            $userid = "76";

            $query = $PDOconnection->prepare("UPDATE profile SET name=:name WHERE user_id=:userid");
            //El :name guarda el contenido de $name
            $query->bindValue("name", $name);
            $query->bindValue("userid", $userid);

            $query->execute();

            $PDOconnection->commit();

        } catch (Exception $e) {
            $PDOconnection->rollback();
            throw $e;
        }

        $PDOconnection = NULL;
    }


    
    //Recoge NAME de la DB en caso de hacer un update con el NAME null
    /*
    public static function selectName(int $id){
        try
        {
            $query = $PDOconnection->prepare("SELECT name FROM profile WHERE user_id = :id");
            $query->bindValue("id", $id);

            $query->execute();

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

            $PDOconnection->commit();

        } catch (Exception $e) {
            $PDOconnection->rollback();
            throw $e;
        }

        $PDOconnection = NULL;
    }

    public static function selectSurname(int $id){
        try
        {
            $query = $PDOconnection->prepare("SELECT surname FROM profile WHERE user_id = :id");
            $query->bindValue("id", $id);

            $query->execute();

            $PDOconnection->commit();

        } catch (Exception $e) {
            $PDOconnection->rollback();
            throw $e;
        }

        $PDOconnection = NULL;
    }

    public static function selectEmail (int $id){
        try
        {
            $query = $PDOconnection->prepare("SELECT email FROM profile WHERE user_id = :id");
            $query->bindValue("id", $id);

            $query->execute();

            $PDOconnection->commit();

        } catch (Exception $e) {
            $PDOconnection->rollback();
            throw $e;
        }

        $PDOconnection = NULL;
    }

    public static function selectBirthdate(int $id){
        try
        {
            $query = $PDOconnection->prepare("SELECT birth_date FROM profile WHERE user_id = :id");
            $query->bindValue("id", $id);

            $query->execute();

            $PDOconnection->commit();

        } catch (Exception $e) {
            $PDOconnection->rollback();
            throw $e;
        }

        $PDOconnection = NULL;
    }
    */
    /*
    public static function updateSurname(string $surname, PDO $PDOconnection)
    {
        try
        {
            $query = $PDOconnection->prepare("UPDATE profile (surname) VALUES (:surname)");
            $query->bindValue("surname", $surname);

            $query->execute();

            $PDOconnection->commit();

        } catch (Exception $e) {
            $PDOconnection->rollback();
            throw $e;
        }

        $PDOconnection = NULL;
    }

    public static function updateEmail(string $email, PDO $PDOconnection)
    {
        try
        {
            $query = $PDOconnection->prepare("UPDATE profile (email) VALUES (:email)");
            $query->bindValue("email", $email);

            $query->execute();

            $PDOconnection->commit();

        } catch (Exception $e) {
            $PDOconnection->rollback();
            throw $e;
        }

        $PDOconnection = NULL;
    }

    public static function updateBirth(string $birthdate, PDO $PDOconnection)
    {
        try
        {
            $query = $PDOconnection->prepare("UPDATE profile (birth_date) VALUES (:birthdate)");
            $query->bindValue("birthdate", $birthdate);

            $query->execute();

            $PDOconnection->commit();

        } catch (Exception $e) {
            $PDOconnection->rollback();
            throw $e;
        }

        $PDOconnection = NULL;
    }
    */
}