<?php

declare(strict_types=1);

namespace ServerBlog\Models;

use ServerBlog\Services as Services;
use \PDO;

class BlogPostModel
{
    private int $id;

    public function __construct($id) 
    {
        $this->id = $id;
    }

    public static function list(int $id)
    {
        $connObj = new Services\Connection(Services\Helpers::getEnviroment());

        $pdo_conn = $connObj->getConnection();

        //* Se recogen los posts de la persona logeada actualmente
        $query = $pdo_conn->prepare("SELECT post.id, post.user_id , u.username, title, text, date 
                                    FROM post
                                    INNER JOIN user u on u.id = post.user_id 
                                    WHERE user_id = :user_id");
        $query->bindValue("user_id", $id);

        if ($query->execute())
        {
            //* Mete todos los datos en un array
            $list = $query->fetchAll();

            //* Si esta vacia te devuelve NULL y sino te devuelve un array con todos los posts del usuario logeado
            return $list;
        }
        else {
            return null;
        }

        $pdo_conn = NULL;
    }

    public static function all()
    {
        $connObj = new Services\Connection(Services\Helpers::getEnviroment());

        $pdo_conn = $connObj->getConnection();

        //* Se recogen todos los posts de la base de datos
        $query = $pdo_conn->prepare("SELECT p.id, p.user_id,u.username, p.title, p.text, p.date 
                                    FROM post p
                                    INNER JOIN user u on u.id = p.user_id
                                    WHERE p.visible = 1
                                    ORDER BY date DESC");
        
        if ($query->execute())
        {
            //* Mete todos los datos en un array
            $list = $query->fetchAll();

            //* Si esta vacia te devuelve NULL y sino te devuelve un array con todos los posts del usuario logeado
            return $list;
        }
        else {
            return null;
        }

        $pdo_conn = NULL;
    }

    public static function view(int $id)
    {
        $connObj = new Services\Connection(Services\Helpers::getEnviroment());

        $pdo_conn = $connObj->getConnection();

        //* Se recogen los posts de la persona logeada actualmente
        $query = $pdo_conn->prepare("SELECT id, user_id, title, text, date FROM post WHERE id = :post_id AND visible=1");
        $query->bindValue("post_id", $id);

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

        $pdo_conn = NULL;
    }

    public static function viewConInvisibles(int $id)
    {
        $connObj = new Services\Connection(Services\Helpers::getEnviroment());

        $pdo_conn = $connObj->getConnection();

        //* Se recogen los posts de la persona logeada actualmente
        $query = $pdo_conn->prepare("SELECT p.id, user_id, title, text, date, visible FROM post p WHERE p.id = :post_id");
        $query->bindValue("post_id", $id);

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

        $pdo_conn = NULL;
    }

    public static function favorites(int $id)
    {
        $connObj = new Services\Connection(Services\Helpers::getEnviroment());

        $pdo_conn = $connObj->getConnection();

        //* Se hacen dos querys 
        $query = $pdo_conn->prepare("SELECT p.id,p.user_id, u.username, fav.`date` as date, p.title as title, p.`text` as text 
                                    FROM post p
                                    INNER JOIN `like` as fav ON p.id = fav.post_id
                                    INNER JOIN user u on u.id = p.user_id 
                                    WHERE fav.user_id = :user_id ORDER BY `date` DESC");

        $query->bindValue("user_id", $id);

        if ($query->execute())
        {
            //* Mete todos los datos en un array
            $feed = $query->fetchAll();

            //* Si esta vacia te devuelve NULL y sino te devuelve un array con todos los posts del usuario logeado
            return $feed;
        }
        else {
            return null;
        }

        $pdo_conn = NULL;
    }

    public static function addToFavorites(int $id, int $user_id)
    { 
        $connObj = new Services\Connection(Services\Helpers::getEnviroment());

        $pdo_conn = $connObj->getConnection();
        $pdo_conn->beginTransaction();

        try
        {
            //* Se recogen los posts de la persona logeada actualmente
            $query = $pdo_conn->prepare("INSERT INTO `like` (post_id, user_id, date) VALUES(:post, :user, NOW())");
            $query->bindValue("post", $id);
            $query->bindValue("user", $user_id);

            if ($query->execute())
            {
                //* Mete todos los datos en un array
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
            $pdo_conn->rollback();
            throw $e;
            return false;
        }

        $pdo_conn = NULL;
    }

    public static function deleteFromFavorites(int $id, int $user_id)
    { 
        $connObj = new Services\Connection(Services\Helpers::getEnviroment());

        $pdo_conn = $connObj->getConnection();
        $pdo_conn->beginTransaction();

        try
        {
            //* Se recogen los posts de la persona logeada actualmente
            $query = $pdo_conn->prepare("DELETE FROM `like` WHERE post_id = :post AND user_id = :user");
            $query->bindValue("post", $id);
            $query->bindValue("user", $user_id);

            if ($query->execute())
            {
                //* Mete todos los datos en un array
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
            $pdo_conn->rollback();
            throw $e;
            return false;
        }

        $pdo_conn = NULL;
    }

    public static function isInFavorites(int $id, int $user_id)
    {
        $connObj = new Services\Connection(Services\Helpers::getEnviroment());

        $pdo_conn = $connObj->getConnection();
        try
        {
            //* Se recogen los posts de la persona logeada actualmente
            $query = $pdo_conn->prepare("SELECT ID FROM `like` WHERE post_id = :post AND user_id = :user");
            $query->bindValue("post", $id);
            $query->bindValue("user", $user_id);

            if ($query->execute())
            {   
                //* Mete todos los datos en un array
                $post = $query->fetch(PDO::FETCH_OBJ);
                
                if (!empty($post)) {
                    return true;
                }
                else {
                    return false;
                }
            }
        }   
        catch (Exception $e)
        {
            throw $e;
            return false;
        }

        $pdo_conn = NULL;
    }

    public static function feed(string $username)
    {
        $connObj = new Services\Connection(Services\Helpers::getEnviroment());

        $pdo_conn = $connObj->getConnection();

        //* Se hacen dos querys 
        $query = $pdo_conn->prepare("(SELECT p.id, p.user_id, u.username , date, title , text 
        FROM post p
        INNER JOIN user u on u.id = p.user_id 
        WHERE u.username = :username)
        UNION
        (SELECT post.id, post.user_id, u2.username ,retweet.`date` as date, post.title as title, post.`text` as text 
        FROM post 
        INNER JOIN retweet ON post.id = retweet.post_id
        INNER JOIN user u on u.id = retweet.user_id
        INNER JOIN user u2 on u2.id  = post.user_id
        WHERE retweet.user_id = u.id  and u.username = :username) ORDER BY `date` DESC");

        $query->bindValue("username", $username);
        $query->bindValue("user_id", $username);


        if ($query->execute())
        {
            //* Mete todos los datos en un array
            $feed = $query->fetchAll();

            //* Si esta vacia te devuelve NULL y sino te devuelve un array con todos los posts del usuario logeado
            return $feed;
        }
        else {
            return null;
        }

        $pdo_conn = NULL;
    }

    public static function addToFeed(int $id, int $user_id)
    {
        $connObj = new Services\Connection(Services\Helpers::getEnviroment());

        $pdo_conn = $connObj->getConnection();
        $pdo_conn->beginTransaction();

        try
        {
            //* Se recogen los posts de la persona logeada actualmente
            $query = $pdo_conn->prepare("INSERT INTO retweet (post_id, user_id, date) VALUES(:post, :user, NOW()) ");
            $query->bindValue("post", $id);
            $query->bindValue("user", $user_id);

            if ($query->execute())
            {
                //* Mete todos los datos en un array
                $pdo_conn->commit();
                return true;
            }
            else {
                $pdo_conn->rollback();
                return false;
            }
        }
        catch (Exception $e)
        {
            throw $e;
            return false;
        }

        $pdo_conn = NULL;
    }

    public static function deleteFromFeed(int $id, int $user_id)
    { 
        $connObj = new Services\Connection(Services\Helpers::getEnviroment());

        $pdo_conn = $connObj->getConnection();
        $pdo_conn->beginTransaction();

        try
        {
            //* Se recogen los posts de la persona logeada actualmente
            $query = $pdo_conn->prepare("DELETE FROM retweet WHERE post_id = :post AND user_id = :user");
            $query->bindValue("post", $id);
            $query->bindValue("user", $user_id);

            if ($query->execute())
            {
                //* Mete todos los datos en un array
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
            $pdo_conn->rollback();
            throw $e;
            return false;
        }

        $pdo_conn = NULL;
    }

    public static function isInFeed(int $id, int $user_id)
    {
        $connObj = new Services\Connection(Services\Helpers::getEnviroment());

        $pdo_conn = $connObj->getConnection();

        //* Se recogen los posts de la persona logeada actualmente
        $query = $pdo_conn->prepare("SELECT ID FROM retweet WHERE post_id = :post AND user_id = :user");
        $query->bindValue("post", $id);
        $query->bindValue("user", $user_id);

        if ($query->execute())
        {
            //* Mete todos los datos en un array
            $post = $query->fetch(PDO::FETCH_OBJ);

            if (!empty($post)) {
                return true;
            }
            else {
                return false;
            }
        }
        else {
            return false;
        }

        $pdo_conn = NULL;
    }

    public static function add(int $id, string $titulo, string $mensaje, int $visible, array $imgsContent)
    {
        // //* Se recoge el id del usuario en la sesion actual

        $today = date("Y/m/d h:i:s");

        $connObj = new Services\Connection(Services\Helpers::getEnviroment());

        $pdo_conn = $connObj->getConnection();

        $pdo_conn->beginTransaction();

        try {
            //* Se hace un insert con los datos del post a crear
            $query = $pdo_conn->prepare("INSERT INTO post (user_id, title, text, date, visible) VALUES (:user_id, :titulo, :mensaje, NOW(), :visible)");
            $query->bindValue("user_id", $id);
            $query->bindValue("titulo", $titulo);
            $query->bindValue("mensaje", $mensaje);
            $query->bindValue("visible", $visible, PDO::PARAM_INT);

            
            //* Si la query funciona se hacen un commit
            if($query->execute())
            {
                //* Se coge el id del post insertado para abrirlo al crearlo

                $id_post = $pdo_conn->lastInsertId();

                $pdo_conn->commit();

                if (!empty($imgsContent)) {
                    self::addImgs($imgsContent, intval($id_post));
                }

                return $id_post;
            }
            else
            {
                $pdo_conn->rollback();
                return false;
            }  
        } 
        catch (\Throwable $th) {
            echo $th;
            $pdo_conn->rollback();
            return false;
        }
        $pdo_conn = NULL; 
    }

    public static function addImgs(array $imgsContent, int $id_post)
    {
        //? Si la imagen no está vacia
        if(!empty($imgsContent))
        {
            try 
            {
                $connObj = new Services\Connection(Services\Helpers::getEnviroment());
                $pdo_conn = $connObj->getConnection();

                foreach ($imgsContent as $img) 
                {
                    $pdo_conn->beginTransaction();
                    $query = $pdo_conn->prepare("INSERT INTO multimedia (post_id, path, img) VALUES (:id_post, :path, :img)");
                    $query->bindValue("id_post",$id_post);
                    $query->bindValue("path", $img['name']);
                    $query->bindValue("img", $img['content']);

                    //? Se mete la imagen en la BBDD
                    if($query->execute()) {
                        $pdo_conn->commit();
                    }
                    else {
                        $pdo_conn->rollback();
                    }
                }
            }
            catch (\Throwable $th) {
                $pdo_conn->rollback();
            }

            $pdo_conn = NULL;
        } 
    }

    public static function edit(int $id, string $titulo, string $mensaje, int $visible, array $imgsContent)
    {
        $connObj = new Services\Connection(Services\Helpers::getEnviroment());

        $pdo_conn = $connObj->getConnection();

        $pdo_conn->beginTransaction();

        try {
            //* Se hace un update del post a cambiar
            $query = $pdo_conn->prepare("UPDATE post SET title=:titulo,text=:mensaje,date=NOW(),visible=:visible WHERE id=:post_id");
            $query->bindValue("post_id", $id);
            $query->bindValue("titulo", $titulo);
            $query->bindValue("mensaje", $mensaje);
            $query->bindValue("visible", $visible, PDO::PARAM_INT);

            //* Si la query funciona se hacen un commit
            if($query->execute())
            {
                $pdo_conn->commit();

                if (!empty($imgsContent)) {
                    self::addImgs($imgsContent, $id);
                }
                
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
        $pdo_conn = NULL; 
    }

    public static function editCategorias(int $post_id, array $categoriasViejas, array $categoriasNuevas)
    {
        //* Se ordena la array de categorias nuevas MERAMENTE VISUAL, NO ES NECESARIO
        sort($categoriasNuevas, SORT_NUMERIC);

        $connObj = new Services\Connection(Services\Helpers::getEnviroment());

        $pdo_conn = $connObj->getConnection();

        $pdo_conn->beginTransaction();

        //* Se crean dos arrays, una con los ids de las categorias a insertar y de las categorias a deletear
        $Insertar=array_diff($categoriasNuevas,$categoriasViejas);
        $Deletear=array_diff($categoriasViejas,$categoriasNuevas);

        try {

            $sqlCategorias="";

            //? Si hay categorias a borrar
            if(!empty($Deletear)){
                
                //* Se crea la sentencia SQL:
                foreach ($Deletear as $value) {
                    //? Si es la primera iteracion se debe hacer ligeramente distinto
                    if($sqlCategorias==""){
                        //* Se pone la consulta de manera que cada :categoria_X tenga un bindValue que le meta el valor. (PARA EVITAR INJECCIONES SQL)
                        $sqlCategorias.= ":categoria_$value";
                    }else{
                        $sqlCategorias.=" OR category_id=:categoria_$value";
                    }
                }

                //* Se hace el delete
                $query = $pdo_conn->prepare("DELETE FROM category_post WHERE (category_id=$sqlCategorias) AND post_id=:post_id;");
               
                foreach ($Deletear as $value) {
                    $query->bindValue("categoria_$value", $value);
                }
                $query->bindValue("post_id", $post_id);

                $query->execute();

            }
        } 
        catch (\Throwable $th)
        {
            echo $th;
            die();
            $pdo_conn->rollback();
            return false;
        }

        try {

            $sqlCategorias="";

            if(!empty($Insertar)){

                foreach ($Insertar as $value) {
                    if($sqlCategorias==""){
                        $sqlCategorias.="(:categoria_$value,:post_id)";
                    }else{
                        $sqlCategorias.=", (:categoria_$value,:post_id)";
                    }
                }

                $query = $pdo_conn->prepare("INSERT INTO category_post (category_id, post_id) VALUES $sqlCategorias;");
                foreach ($Insertar as $value) {
                    $query->bindValue("categoria_$value", $value);
                }
                $query->bindValue("post_id", $post_id);

                $query->execute();
            }
        } 
        catch (\Throwable $th)
        {
            echo $th;
            die();
            $pdo_conn->rollback();
            return false;
        }

        $pdo_conn->commit();
        return true;

        $pdo_conn = NULL; 
    }

    public static function author(string $username)
    {
        $connObj = new Services\Connection(Services\Helpers::getEnviroment());

        $pdo_conn = $connObj->getConnection();

        //* Se hacen dos querys 
        $query = $pdo_conn->prepare("SELECT p.id, p.user_id, u.username , date, title , text FROM post p INNER JOIN user u on u.id = p.user_id WHERE u.username = :username AND p.visible=1 ORDER BY date DESC");
        $query->bindValue("username", $username);

        if ($query->execute())
        {
            //* Mete todos los datos en un array
            $author = $query->fetchAll();

            //* Si esta vacia te devuelve NULL y sino te devuelve un array con todos los posts del usuario logeado
            return $author;
        }
        else {
            return null;
        }

        $pdo_conn = NULL;
    }

    public static function authorConInvisibles(string $username)
    {
        $connObj = new Services\Connection(Services\Helpers::getEnviroment());

        $pdo_conn = $connObj->getConnection();

        //* Se hacen dos querys 
        $query = $pdo_conn->prepare("SELECT p.id, p.user_id, u.username , date, title , text FROM post p INNER JOIN user u on u.id = p.user_id WHERE u.username = :username ORDER BY date DESC");
        $query->bindValue("username", $username);

        if ($query->execute())
        {
            //* Mete todos los datos en un array
            $author = $query->fetchAll();

            //* Si esta vacia te devuelve NULL y sino te devuelve un array con todos los posts del usuario logeado
            return $author;
        }
        else {
            return null;
        }
        $pdo_conn = NULL;
    }

    public static function addComment(int $post_id, int $user_id,string $text)
    {
         $connObj = new Services\Connection(Services\Helpers::getEnviroment());
 
         $pdo_conn = $connObj->getConnection();
 
         $pdo_conn->beginTransaction();
 
         try 
         {
            $query = $pdo_conn->prepare("INSERT INTO comment (post_id, user_id, text, date) VALUES (:post_id, :user_id, :text, NOW())");

            $query->bindValue("post_id", $post_id);
            $query->bindValue("user_id", $user_id);
            $query->bindValue("text", $text);

            //* Si la query funciona se hacen un commit
            if($query->execute())
            {
                //* Se coge el id del post insertado para abrirlo al crearlo
                $pdo_conn->commit();
                return true;
            }
            else {
                $pdo_conn->rollback();
                return false;
            }  
         } 
         catch (\Throwable $th) {
             echo $th;
             $pdo_conn->rollback();
             return false;
         }
         
         $pdo_conn = NULL; 
    }

    public static function getComments(int $post_id)
    {
         $connObj = new Services\Connection(Services\Helpers::getEnviroment());
         $pdo_conn = $connObj->getConnection();
 
         try 
         {
            $query = $pdo_conn->prepare("SELECT * FROM comment WHERE post_id = :post_id AND comment_id IS NULL ORDER BY date DESC;");

            $query->bindValue("post_id", $post_id);

            //* Si la query funciona se hacen un commit
            if($query->execute())
            {
                //* Se coge el id del post insertado para abrirlo al crearlo
                $list = $query->fetchAll(PDO::FETCH_OBJ);
                return $list;
            }
            else {
                return false;
            }  
         } 
         catch (\Throwable $th) {
             echo $th;
             return false;
         }
         
         $pdo_conn = NULL; 
    }

    public static function getCommentParent(int $id)
    {
         $connObj = new Services\Connection(Services\Helpers::getEnviroment());
         $pdo_conn = $connObj->getConnection();
 
         try 
         {
            $query = $pdo_conn->prepare("SELECT * FROM comment WHERE id = :id AND comment_id IS NULL ORDER BY date DESC;");

            $query->bindValue("id", $id);

            //* Si la query funciona se hacen un commit
            if($query->execute())
            {
                //* Se coge el id del post insertado para abrirlo al crearlo
                $list = $query->fetch(PDO::FETCH_OBJ);
                if (!empty($list)) {
                    return true;
                }
                else {
                    return false;
                }
            }
            else {
                return false;
            }  
         } 
         catch (\Throwable $th) {
             echo $th;
             return false;
         }
         
         $pdo_conn = NULL; 
    }

    public static function getCommentbyId(int $id)
    {
        $connObj = new Services\Connection(Services\Helpers::getEnviroment());
         $pdo_conn = $connObj->getConnection();
 
         try 
         {
            $query = $pdo_conn->prepare("SELECT * FROM comment WHERE id = :id;");

            $query->bindValue("id", $id);

            //* Si la query funciona se hacen un commit
            if($query->execute())
            {
                //* Se coge el id del post insertado para abrirlo al crearlo
                $comment = $query->fetch(PDO::FETCH_OBJ);

                if (!empty($comment)) {
                    return $comment;
                }
                else {
                    return false;
                }
            }
            else {
                return false;
            }  
         } 
         catch (\Throwable $th) {
             echo $th;
             return false;
         }
         
         $pdo_conn = NULL; 
    }

    public static function deleteComment(int $id)
    {
        $connObj = new Services\Connection(Services\Helpers::getEnviroment());

        $pdo_conn = $connObj->getConnection();

        $pdo_conn->beginTransaction();

        try 
        {
        $query = $pdo_conn->prepare("DELETE FROM comment WHERE id=:id;");

        $query->bindValue("id", $id);

        //* Si la query funciona se hacen un commit
        if($query->execute())
        {
            //* Se coge el id del post insertado para abrirlo al crearlo
            $pdo_conn->commit();
            return true;
        }
        else {
            $pdo_conn->rollback();
            return false;
        }  
        } 
        catch (\Throwable $th) {
            echo $th;
            $pdo_conn->rollback();
            return false;
        }
        
        $pdo_conn = NULL; 
    }

    public static function addAnswer(int $post_id, int $padre_id ,int $user_id,string $text)
    {
         $connObj = new Services\Connection(Services\Helpers::getEnviroment());
 
         $pdo_conn = $connObj->getConnection();
 
         $pdo_conn->beginTransaction();
 
         try 
         {
            $query = $pdo_conn->prepare("INSERT INTO comment (post_id, comment_id, user_id, text, date) VALUES (:post_id, :comment_id, :user_id, :text, NOW())");

            $query->bindValue("post_id", $post_id);
            $query->bindValue("comment_id", $padre_id);
            $query->bindValue("user_id", $user_id);
            $query->bindValue("text", $text);

            //* Si la query funciona se hacen un commit
            if($query->execute())
            {
                //* Se coge el id del post insertado para abrirlo al crearlo
                $pdo_conn->commit();
                return true;
            }
            else {
                $pdo_conn->rollback();
                return false;
            }  
         } 
         catch (\Throwable $th) {
             echo $th;
             $pdo_conn->rollback();
             return false;
         }
         
         //? Usuario no logueado
         $pdo_conn = NULL; 
    }

    public static function getAnswer(int $comment_id)
    {
         $connObj = new Services\Connection(Services\Helpers::getEnviroment());
         $pdo_conn = $connObj->getConnection();
 
         try 
         {
            $query = $pdo_conn->prepare("SELECT * FROM comment WHERE comment_id = :comment_id ORDER BY date DESC;");

            $query->bindValue("comment_id", $comment_id);

            //* Si la query funciona se hacen un commit
            if($query->execute())
            {
                //* Se coge el id del post insertado para abrirlo al crearlo
                $list = $query->fetchAll(PDO::FETCH_OBJ);
                return $list;
            }
            else {
                return false;
            }  
         } 
         catch (\Throwable $th) {
             echo $th;
             return false;
         }
         
         $pdo_conn = NULL; 
    }

    public static function getImgsByPostId(int $post_id)
    {
        $connObj = new Services\Connection(Services\Helpers::getEnviroment());
        $pdo_conn = $connObj->getConnection();

        try 
        {
            $query = $pdo_conn->prepare("SELECT * FROM multimedia WHERE post_id = :post_id;");

            $query->bindValue("post_id", $post_id);

            //* Si la query funciona se hacen un commit
            if($query->execute())
            {
                //* Se coge el id del post insertado para abrirlo al crearlo
                $imgs = $query->fetchAll(PDO::FETCH_OBJ);

                return $imgs;
            }
            else {
                return false;
            }  
        } catch (\Throwable $th) {
            return false;
        }

        $pdo_conn = NULL; 
    }

    public static function getCategorias()
    {
        $connObj = new Services\Connection(Services\Helpers::getEnviroment());
        $pdo_conn = $connObj->getConnection();

        try 
        {
            $query = $pdo_conn->prepare("SELECT id, name FROM category ORDER BY name ASC;");
            
            //* Si la query funciona se hacen un commit
            if($query->execute())
            {
                //* Se coge el id del post insertado para abrirlo al crearlo
                $categorias = $query->fetchAll(PDO::FETCH_OBJ);
                return $categorias;
            }
            else {
                return false;
            }  
        } catch (\Throwable $th) {
            return false;
        }

        $pdo_conn = NULL; 
    }

    public static function getCategoriasByPostID(int $post_id)
    {
        $connObj = new Services\Connection(Services\Helpers::getEnviroment());
        $pdo_conn = $connObj->getConnection();

        try 
        {
            $query = $pdo_conn->prepare("SELECT category_id, name 
                                        FROM category_post cp
                                        INNER JOIN category c2 on c2.id = cp.category_id 
                                        WHERE post_id=:post_id;");
                                        
            $query->bindValue("post_id", $post_id);
            
            //* Si la query funciona se hacen un commit
            if($query->execute())
            {
                //* Se coge el id del post insertado para abrirlo al crearlo
                $categorias = $query->fetchAll(PDO::FETCH_OBJ);
                return $categorias;
            }
            else {
                return false;
            }  
        } catch (\Throwable $th) {
            return false;
        }

        $pdo_conn = NULL; 
    }
}