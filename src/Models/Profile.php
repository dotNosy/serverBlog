<?php

declare(strict_types=1);

namespace ServerBlog\Models;

use ServerBlog\Services as Services;
use ServerBlog\Models as Models;
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

    public static function getProfile(int $id){
        $connObj = new Services\Connection(Services\Helpers::getEnviroment());

        $pdo_conn = $connObj->getConnection();

        //* Se hace un update del post a cambiar
        $query = $pdo_conn->prepare("SELECT name, surname, birth_date, email FROM profile WHERE user_id=:user_id");
        //El :name guarda el contenido de $name
        $query->bindValue("user_id", $id);
        
        //* Si la query funciona se hacen un commit
        if($query->execute())
        {
            
            //Crea el objeto con la linea de la base de datos
            $profile = $query->fetch(PDO::FETCH_OBJ);
            return $profile;
        }
        else
        {   
            
            return false;
        }
        
        //? Usuario no logueado
        $pdo_conn = NULL;
    }

    public static function edit(int $id, string $name, string $surname, string $email,string $birthdate)
    {
        $connObj = new Services\Connection(Services\Helpers::getEnviroment());

        $pdo_conn = $connObj->getConnection();

        $pdo_conn->beginTransaction();

        try 
        {
            //* Se hace un update del post a cambiar
            $stm = "";
            //name=:name, surname=:surname, email=:email, birth_date=:birth_date

            if (!empty($name)) {
                $stm .= " name=:name"; 
            }

            if (!empty($surname)) {
                if(!empty($stm)){
                    $stm .= ", surname=:surname";
                }else{
                    $stm .= "surname=:surname";
                }   
            }

            if (!empty($email))
            {
                if(!empty($stm)){
                    $stm .= ", email=:email";
                }else{
                    $stm .= "email=:email";
                }   
            }

            if (!empty($birthdate)) 
            {
                if(!empty($stm)){
                    $stm .= ", birth_date=:birth_date";
                }else{
                    $stm .= "birth_date=:birth_date";
                }  
            }

            if(!empty($stm))
            {
                $query = $pdo_conn->prepare("UPDATE profile SET $stm WHERE user_id=:user_id");
                //El :name guarda el contenido de $name
                $query->bindValue("user_id", $id);
                if(!empty($name)){
                    $query->bindValue("name", $name);
                }
                if(!empty($surname)){
                    $query->bindValue("surname", $surname);
                }
                if(!empty($email)){
                    $query->bindValue("email", $email);
                }
                if(!empty($birthdate)){
                    $query->bindValue("birth_date", $birthdate);
                }

                //* Si la query funciona se hacen un commit
                if($query->execute())
                {
                    //* Se coge el id del post insertado para abrirlo al crearlo
                    
                    $pdo_conn->commit();
                    return true;
                }
                else
                {   
                    $pdo_conn->rollback();
                    return false;
                }
            }else{
                return false;
            }
        } 
        catch (\Throwable $th)
        {
            echo $th;
            $pdo_conn->rollback();
            return false;
        }


        
        //? Usuario no logueado
        $pdo_conn = NULL;
    }

    public static function changePassword(int $id, string $password)
    {
        $connObj = new Services\Connection(Services\Helpers::getEnviroment());

        $pdo_conn = $connObj->getConnection();

        $pdo_conn->beginTransaction();

        try 
        {
            $query = $pdo_conn->prepare("UPDATE user SET password=:password WHERE id=:user_id");
            //El :name guarda el contenido de $name
            $query->bindValue("user_id", $id);
            if(!empty($password)){
                $query->bindValue("password", password_hash($password, PASSWORD_BCRYPT));
            }

            //* Si la query funciona se hacen un commit
            if($query->execute())
            {
                //* Se coge el id del post insertado para abrirlo al crearlo
                $pdo_conn->commit();
                return true;
            }
            else
            {   
                 $pdo_conn->rollback();
                return false;
            }
            
        } 
        catch (\Throwable $th)
        {
            echo $th;
            $pdo_conn->rollback();
            return false;
        }

        //? Usuario no logueado
        $pdo_conn = NULL;
    }
    
}