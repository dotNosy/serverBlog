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
            $PDOconnection->rollback();
            throw $e;
        }
        finally{
            $pdo_conn = NULL; 
        }
    }

    public static function getProfile(int $id)
    {
        try{
            $connObj = new Services\Connection(Services\Helpers::getEnviroment());

            $pdo_conn = $connObj->getConnection();
    
            //* Se hace un update del post a cambiar
            $query = $pdo_conn->prepare("SELECT name, surname, birth_date, email, avatar FROM profile WHERE user_id=:user_id");
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
        }
        catch(Exception $e){
            echo $e;
            return false;
        }
        finally{
            $pdo_conn = NULL;
        }
    }

    public static function edit(int $id, string $name, string $surname, string $email,string $birthdate, array $imgsContent)
    {
        try 
        {
            $connObj = new Services\Connection(Services\Helpers::getEnviroment());
            $pdo_conn = $connObj->getConnection();
            $pdo_conn->beginTransaction();

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

            if(!empty($imgsContent)){
                if(!empty($stm)){
                    $stm .= ", avatar=:avatar";
                }else{
                    $stm .= "avatar=:avatar";
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
                if(!empty($imgsContent)){
                    $query->bindValue("avatar", $imgsContent[0]["content"]);
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
        catch (Exception $e)
        {
            echo $e;
            $pdo_conn->rollback();
            return false;
        }
        finally{
            $pdo_conn = NULL;
        }
    }

    public static function changePassword(int $id, string $password)
    {
        try 
        {
            $connObj = new Services\Connection(Services\Helpers::getEnviroment());
            $pdo_conn = $connObj->getConnection();
            $pdo_conn->beginTransaction();

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
        catch (Exception $e)
        {
            echo $e;
            $pdo_conn->rollback();
            return false;
        }finally{
            $pdo_conn = NULL;
        }
    }

    public static function getNotifications(int $id)
    {
        try 
        {
            $connObj = new Services\Connection(Services\Helpers::getEnviroment());
            $pdo_conn = $connObj->getConnection();

            $query = $pdo_conn->prepare("SELECT n.id, n.user_id, u.username as 'Nombre', notification_user_id, n.post_id, p.title, type_id, n.date, nt.name as 'Tipo', Leido
                                        FROM notification n
                                        INNER JOIN `user` u
                                        ON u.id = n.notification_user_id
                                        INNER JOIN post p 
                                        ON n.post_id = p.id
                                        INNER JOIN notification_type nt
                                        ON n.type_id = nt.id 
                                        WHERE n.user_id = :user_id
                                        ORDER BY date DESC;");
            //El :name guarda el contenido de $name
            $query->bindValue("user_id", $id);
            


            //* Si la query funciona se hacen un commit
            if($query->execute())
            {
                //* Se coge el id del post insertado para abrirlo al crearlo
                $notificaciones = $query->fetchAll(PDO::FETCH_OBJ);
                return $notificaciones;
            }
            else
            {   
                return false;
            }
            
        } 
        catch (Exception $e)
        {
            echo $e;
            return false;
        }finally{
            $pdo_conn = NULL;
        }
    }

    public static function getCountNotifications(int $id)
    {
        try 
        {
            $connObj = new Services\Connection(Services\Helpers::getEnviroment());
            $pdo_conn = $connObj->getConnection();

            $query = $pdo_conn->prepare("SELECT count(n.id) count_nots
                                        FROM notification n
                                        INNER JOIN `user` u
                                        ON u.id = n.notification_user_id
                                        INNER JOIN post p 
                                        ON n.post_id = p.id
                                        INNER JOIN notification_type nt
                                        ON n.type_id = nt.id 
                                        WHERE n.user_id = :user_id AND Leido = 0;");
            //El :name guarda el contenido de $name
            $query->bindValue("user_id", $id);
            


            //* Si la query funciona se hacen un commit
            if($query->execute())
            {
                //* Se coge el id del post insertado para abrirlo al crearlo
                $notificaciones = $query->fetch(PDO::FETCH_OBJ);
                return $notificaciones->count_nots;
            }
            else
            {   
                return false;
            }
            
        } 
        catch (Exception $e)
        {
            echo $e;
            return false;
        }finally{
            $pdo_conn = NULL;
        }
    }

    public static function marcarNotificacionesComoLeido(int $id)
    {
        try 
        {
            $connObj = new Services\Connection(Services\Helpers::getEnviroment());
            $pdo_conn = $connObj->getConnection();
            $pdo_conn->beginTransaction();


            $query = $pdo_conn->prepare("UPDATE notification
                                        SET Leido=b'1'
                                        WHERE user_id = :user_id;");
            //El :name guarda el contenido de $name
            $query->bindValue("user_id", $id);
            


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
        catch (Exception $e)
        {
            echo $e;
            $pdo_conn->rollback();
            return false;
        }finally{
            $pdo_conn = NULL;
        }
    }

    public static function deleteNotificationsByNotificationID(int $id)
    {
        try 
        {
            $connObj = new Services\Connection(Services\Helpers::getEnviroment());
            $pdo_conn = $connObj->getConnection();
            $pdo_conn->beginTransaction();


            $query = $pdo_conn->prepare("DELETE FROM notification WHERE id=:notification_id;");
            //El :name guarda el contenido de $name
            $query->bindValue("notification_id", $id);
            


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
        catch (Exception $e)
        {
            echo $e;
            $pdo_conn->rollback();
            return false;
        }finally{
            $pdo_conn = NULL;
        }
    }

    public static function deleteNotificationsByUserID(int $id)
    {
        try 
        {
            $connObj = new Services\Connection(Services\Helpers::getEnviroment());
            $pdo_conn = $connObj->getConnection();
            $pdo_conn->beginTransaction();


            $query = $pdo_conn->prepare("DELETE FROM notification WHERE user_id=:user_id;");
            //El :name guarda el contenido de $name
            $query->bindValue("user_id", $id);
            


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
        catch (Exception $e)
        {
            echo $e;
            $pdo_conn->rollback();
            return false;
        }finally{
            $pdo_conn = NULL;
        }
    }

    public static function getProfileImg(int $idProfile)
    {
        try{
            $connObj = new Services\Connection(Services\Helpers::getEnviroment());

            $pdo_conn = $connObj->getConnection();
    
            //* Se hace un update del post a cambiar
            $query = $pdo_conn->prepare("SELECT avatar FROM profile WHERE id=:profile_id");
            //El :name guarda el contenido de $name
            $query->bindValue("profile_id", $idProfile);
            
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
        }
        catch(Exception $e){
            echo $e;
            return false;
        }
        finally{
            $pdo_conn = NULL;
        }
    }
}