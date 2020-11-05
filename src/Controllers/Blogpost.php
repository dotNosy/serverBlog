<?php

declare(strict_types=1);

namespace ServerBlog\Controllers;

use ServerBlog\Models as Models;
use ServerBlog\Services\Helpers;

use ServerBlog\Models\User;

class Blogpost extends Controller
{
    //! Desde la URL este controlador tiene de alias post
    public function __construct(array $params = null) 
    {
        parent::__construct($params);
    }

    protected function index(array $params = null) 
    {
        Helpers::sendToController("/post/all");
    }

    protected function view(array $params)
    {
        //? La posicion 2 es el id del post
        //? Si la posicion 2 esta vacia o no es un numero
        if(empty($params[2]) || !is_int(intval($params[2])))
        {
            //* Redireccion a all con mensaje "No existe este post"
            Helpers::sendToController("/post/all",
            [
                "error" => "No existe este post."
            ]);
        }
        //? Si la posicion 2 no esta vacia y es un numero
        else
        {
            $user = User::getUser();

            //? Si el usuario existe
            if(!empty($user))
            {
                $view = Models\BlogPostModel::viewConInvisibles(intval($params[2]));

                //? Si existe el post
                if(!empty($view))
                {
                    $comments = Models\BlogPostModel::getComments(intval($view->id));
                    
                    $categorias=Models\BlogPostModel::getCategoriasByPostID(intval($view->id));

                    $categoriasTodas=Models\BlogPostModel::getCategorias();

                    $imgs = Models\BlogPostModel::getImgsByPostId(intval($view->id));

                    //? Si el post encontrado es invisible (privado)
                    if(!$view->visible)
                    {
                        //? Si el id logeado es el creador del post
                        if($view->user_id==$user->id)
                        {
                            //* Te enseña el post
                            parent::sendToView([
                                "titulo" => "POST"
                                ,"css" => array("post.css")
                                ,"blogPost" => $view
                                ,"autor" => User::getUsernameById(intval($view->user_id))
                                ,"comments" => $comments
                                ,"categorias" => $categorias
                                ,"categoriasTodas" => $categoriasTodas
                                ,"imgs" => $imgs
                                ,"page" => __DIR__ . '/../Views/BlogPost/View.php'
                            ]);
                        }
                        //? Si el id logeado NO es el creador del post
                        else
                        {
                            Helpers::sendToController("/post/all",
                                [
                                "error" => "No existe este post."
                            ]);
                        }
                    }
                    //? Si el post encontrado es visible (publico)
                    else
                    {
                        parent::sendToView([
                            "titulo" => "POST"
                            ,"css" => array("post.css")
                            ,"blogPost" => $view
                            ,"autor" => User::getUsernameById(intval($view->user_id))
                            ,"comments" => $comments
                            ,"categorias" => $categorias
                            ,"categoriasTodas" => $categoriasTodas
                            ,"imgs" => $imgs
                            ,"page" => __DIR__ . '/../Views/BlogPost/View.php'
                        ]);
                    }
                }
                //? Si el post no ha sido encontrado
                else
                {
                    Helpers::sendToController("/post/all",
                    [
                        "error" => "No existe este post."
                    ]);
                }          
            }
            //? Si el usuario no esta logeado
            else
            {
                //* Coger solo visibles
                $view = Models\BlogPostModel::view(intval($params[2]));
                if(!empty($view))
                {
                    $comments = Models\BlogPostModel::getComments(intval($view->id));

                    $categorias=Models\BlogPostModel::getCategoriasByPostID(intval($view->id));

                    $categoriasTodas=Models\BlogPostModel::getCategorias();

                    $imgs = Models\BlogPostModel::getImgsByPostId(intval($view->id));

                    parent::sendToView([
                        "titulo" => "POST"
                        ,"css" => array("post.css")
                        ,"blogPost" => $view
                        ,"autor" => User::getUsernameById(intval($view->user_id))
                        ,"comments" => $comments
                        ,"categorias" => $categorias
                        ,"categoriasTodas" => $categoriasTodas
                        ,"imgs" => $imgs
                        ,"page" => __DIR__ . '/../Views/BlogPost/View.php'
                    ]);
                }
                else
                {
                    Helpers::sendToController("/post/all",
                    [
                        "error" => "No existe este post."
                    ]);
                }
            }
        }   
    }

    //! Los possts del usuario logueado
    protected function list(array $params = null)
    {
        //* Se recoge el id del usuario en la sesion actual
        $user = User::getUser();

        if(!empty($user))
        {
            //* Me devuelve de la BD todos los registros del usuario del id
            $list = Models\BlogPostModel::list(intval($user->id));
            
            if(!empty($list))
            {   
                parent::sendToView([
                    "titulo" => "LIST"
                    ,"list" => $list
                    ,"page" => __DIR__ . '/../Views/BlogPost/List.php'
                ]); 
            }
            else
            {
                parent::sendToView([
                    "titulo" => "LIST"
                    ,"list" => $list
                    ,"error" => "No has publicado ningun post todavia."
                    ,"page" => __DIR__ . '/../Views/BlogPost/List.php'
                ]);
            }
        }
        //? Usuario no logueado
        else
        {
            Helpers::sendToController("/login/index",
            [
                "error" => "Para ver tus posts debes estar registrado."
            ]);
        }  
    }

    //! Todos los posts por orden cronologico
    protected function all(array $params)
    {
        //* Todos los posts, los nuevos primero
        $list = Models\BlogPostModel::all();

        //* Se cogen las categorias
        $categorias = array();

        foreach ($list as $post) {
            $categoriasPorID = Models\BlogPostModel::getCategoriasByPostID(intval($post["id"]));
            if(!empty($categoriasPorID)){
                array_push($categorias,$categoriasPorID);
            }          
        }
        
        if(!empty($list))
        {
            parent::sendToView([
                "titulo" => "LIST"
                ,"list" => $list
                ,"categorias" => $categorias
                ,"page" => __DIR__ . '/../Views/BlogPost/List.php'
            ]); 
        }
        else
        {
            parent::sendToView([
                "titulo" => "LIST"
                ,"list" => $list
                ,"error" => "No se han publicado posts todavia."
                ,"page" => __DIR__ . '/../Views/BlogPost/List.php'
            ]); 
        }
    }

    protected function author(array $params)
    {
        if(!empty($params[2]) && is_int(intval($params[2])))
        {  
            $user = User::getUser();

            //* Me devuelve de la BD todos los registros del usuario del id
            $author = Models\BlogPostModel::author($params[2]);

            //* Se cogen las categorias
            $categorias = array();

            foreach ($author as $post) {
                $categoriasPorID = Models\BlogPostModel::getCategoriasByPostID(intval($post["id"]));
                if(!empty($categoriasPorID)){
                    array_push($categorias,$categoriasPorID);
                }          
            }

            if(!empty($user)){

                if($user->username == $params[2])
                {
                    $author = Models\BlogPostModel::authorConInvisibles($params[2]);

                    //* Se cogen las categorias
                    $categorias = array();

                    foreach ($author as $post) {
                        $categoriasPorID = Models\BlogPostModel::getCategoriasByPostID(intval($post["id"]));
                        if(!empty($categoriasPorID)){
                            array_push($categorias,$categoriasPorID);
                        }          
                    }

                    if(!empty($author))
                    {
                        
                        parent::sendToView([
                            "titulo" => "ADD POST"
                            ,"list" => $author
                            ,"categorias" => $categorias
                            ,"page" => __DIR__ . '/../Views/BlogPost/List.php'
                        ]);
                    }
                    else
                    {
                        Helpers::sendToController("/post/all"
                        ,[
                            "error" => "No existe este usuario."
                        ]);
                    }       
                }
                else
                {
                    if(!empty($author))
                    {
                        parent::sendToView([
                            "titulo" => "ADD POST"
                            ,"list" => $author
                            ,"categorias" => $categorias
                            ,"page" => __DIR__ . '/../Views/BlogPost/List.php'
                        ]);
                    }
                    else
                    {
                        parent::sendToView([
                            "titulo" => "ADD POST"
                            ,"list" => $author
                            ,"categorias" => $categorias
                            ,"error" => "Este autor no tiene posts."
                            ,"page" => __DIR__ . '/../Views/BlogPost/List.php'
                        ]);
                    }
                    
                }

            }
            else
            {
                if(!empty($author))
                {
                    
                    parent::sendToView([
                        "titulo" => "ADD POST"
                        ,"list" => $author
                        ,"categorias" => $categorias
                        ,"page" => __DIR__ . '/../Views/BlogPost/List.php'
                    ]);
                }
                else
                {
                    Helpers::sendToController("/post/all"
                    ,[
                        "error" => "No existe este usuario."
                    ]);
                }
            }
        }
        else
        {
            Helpers::sendToController("/post/all");
        }
    }

    protected function add(array $params = null)
    {
        $user = User::getUser();

        if(!empty($user))
        {
            if ($_SERVER['REQUEST_METHOD'] == "POST" && isset($_POST['add']))
            {
                $tituloPost = Helpers::cleanInput($_POST['titulo']);
                $mensajePost = Helpers::cleanInput($_POST['mensaje']);
                $radioPost = Helpers::cleanInput($_POST['visibleRadio']);
                $categorias = $_POST['categorias'];

                //? Si el radibutton no devulve 0 o 1
                if($radioPost!=0 && $radioPost!=1) {
                    //TODO: Poner datos incorrectos en rojo
                }
                //? Error titulo o mensaje vacio
                else if (empty($tituloPost) || empty($mensajePost)) {   
                    // TODO: Ponerle en rojo los datos que estan vacios
                }
                //? Creacion del post
                else
                {
                    $imgsContent = array();

                    //! ADD IMAGENES
                    if (!empty($_FILES['imagenes']))
                    {
                        $allowedExtensions = array('jpg','png','gif');
                        $imgsContent = Helpers::getFilesContent($_FILES['imagenes'], $allowedExtensions);
                    }

                    //* Va al model para intentar crear el post
                    $id_post = Models\BlogPostModel::add($user->id,$tituloPost,$mensajePost,intval($radioPost),$imgsContent, $categorias);
                    
                    //* Si ha devuelto un id es que se ha creado, y por lo tanto te devuelve el view del post creado
                    if(!empty($id_post))
                    {
                        Helpers::sendToController("/post/view/$id_post");
                    }
                    else {
                        //TODO: si no se ha podido crear el post
                    }
                }
            }
            //? VISTA GET
            else 
            {
                $categorias = Models\BlogPostModel::getCategorias();
                parent::sendToView([
                    "titulo" => "ADD POST"
                    ,"js" => array("addPost.js")
                    ,"categorias" => $categorias
                    ,"page" => __DIR__ . '/../Views/BlogPost/Add.php'
                ]);
            }
        }
        //? Usuario no logueado
        else
        {
            Helpers::sendToController("/login/index"
            ,[
                "error" => "Para crear un post debes estar logeado."
             ]);
        }     
    }

    protected function edit(array $params)
    {
        //* Se recoge el usuario
        $user = User::getUser();

        if(!empty($user))
        {
            //? Si se envia el formulario (CON POST)
            if ($_SERVER['REQUEST_METHOD'] == "POST" && isset($_POST['edit']))
            {
                $id_post = Helpers::cleanInput($_POST['id']);
                $view = Models\BlogPostModel::viewConInvisibles(intval($id_post));

                //? El post existe
                if(!empty($view))
                {
                    //? El post pertenece al usuario
                    //? Está aqui para no dejar que harcodeen la URL para editar el de los demas
                    if($user->id == $view->user_id)
                    { 
                        $imgsContent = array();

                         //! ADD IMAGENES
                        if (!empty($_FILES['imagenes']))
                        {
                            $allowedExtensions = array('jpg','png','gif');
                            $imgsContent = Helpers::getFilesContent($_FILES['imagenes'], $allowedExtensions);
                        }
                        
                        //* Se recogen las variables sin caracteres especiales
                        $id = intval(Helpers::cleanInput($_POST['id']));
                        $titulo = Helpers::cleanInput($_POST['titulo']);  
                        $mensaje = Helpers::cleanInput($_POST['mensaje']);
                        $visibleRadio = Helpers::cleanInput($_POST['visibleRadio']);
                        $categorias = !empty($_POST['categorias']) ? $_POST['categorias'] : array();

                        $categoriasNuevas=array();
                        $categoriasViejas=array();
                        
                        foreach ($categorias as $id) {
                            array_push($categoriasNuevas,intval(Helpers::cleanInput($id)));
                        }
                        
                        $categoriasElegidas = Models\BlogPostModel::getCategoriasByPostID(intval($view->id));

                        foreach ($categoriasElegidas as $key => $value) {
                            array_push($categoriasViejas,intval($categoriasElegidas[$key]->category_id));
                        }

                        foreach ($categoriasPost as $key => $value) {
                            array_push($categoriasAnteriores,intval($categoriasPost[$key]->category_id));
                          }

                        //? Se ha updateado correctamente
                        if(Models\BlogPostModel::edit(intval($view->id),$titulo,$mensaje,intval($visibleRadio),$imgsContent) && Models\BlogPostModel::editCategorias(intval($view->id), $categoriasViejas, $categoriasNuevas))
                        {
                           //* La llamada a la vista
                            Helpers::sendToController("/post/view/$view->id"); 
                        }
                        //? Ha fallado el update
                        else
                        {
                            Helpers::sendToController("/post/view/$view->id",
                            [
                                "error" => "No se ha podido cambiar uno o más campos."
                            ]);
                        }
                    }
                    else
                    {
                        //* No es un post tuyo
                        Helpers::sendToController("/post/all",
                        [
                            "error" => "No eres el creador de este post."
                        ]); 
                    }
                }
                else
                {
                    //* No existe un post con ese id
                    Helpers::sendToController("/post/all",
                    [
                            "error" => "No existe este post."
                    ]);
                }                  
            }
            //? Si entra por get (URL)
            else
            {
                //? La posicion 2 es el id del post
                if(!empty($params[2]) && is_int(intval($params[2])))
                {  
                    $view = Models\BlogPostModel::viewConInvisibles(intval($params[2]));
                    if(!empty($view))
                    {
                        //? El post pertenece al usuario
                        //? Está aqui para no dejar que harcodeen la URL para editar el de los demas
                        if($user->id == $view->user_id)
                        {
                            $categoriasElegidas = Models\BlogPostModel::getCategoriasByPostID(intval($view->id));

                            $categorias = Models\BlogPostModel::getCategorias();

                            $categoriasAnteriores = array();

                            //* Se cogen las categorias
                            foreach ($categoriasElegidas as $catElegida) {
                                array_push($categoriasAnteriores,intval($catElegida->category_id));
                            }

                            $imgs = Models\BlogPostModel::getImgsByPostId(intval($view->id));

                            //* Se llama a la vista del edit
                            parent::sendToView([
                                "js" => array("deleteImg.js")
                                ,"titulo" => "EDIT POST"
                                ,"blogPost" => $view
                                ,"categorias" => $categorias
                                ,"categoriasPost" => $categoriasElegidas
                                ,"categoriasAnteriores" => $categoriasAnteriores
                                ,"imgs" => $imgs
                                ,"autor" => User::getUsernameById(intval($view->user_id))
                                ,"page" => __DIR__ . '/../Views/BlogPost/Edit.php'
                            ]);
                        }
                        else
                        {
                            //* Este post no es tuyo
                            Helpers::sendToController("/post/all",
                            [
                                "error" => "No eres el creador de este post."
                            ]); 
                        }
                    }
                }
                else
                {
                    Helpers::sendToController("/post/all",
                    [
                        "error" => "No existe este post."
                    ]);
                }
            } 
        }
        //? Usuario no logueado
        else
        {
            Helpers::sendToController("/login/index",
            [
                "error" => "Para editar un post debes estar logeado."
            ]);
        }
    }

    protected function deleteImg()
    {
        if ($_POST && !empty($_POST['id']))
        {
            $response = array("status" => "");

            if (is_int(intval($_POST['id'])))
            {
               $deleted = Models\BlogPostModel::deleteImg(intval($_POST['id']));

                $response['status'] = $deleted;
            }
            else {
                $response = array("status" => "error");
            }
            
            echo json_encode($response);
        }
    }

    protected function addComment($params = NULL)
    {
        $user = User::getUser();

        if(!empty($user))
        {
            if ($_SERVER['REQUEST_METHOD'] == "POST" && isset($_POST['comment']) && !empty($_POST['id']) && !empty($_POST['text']))
            {
                $comment = "";
                //? hay un id post
                if (!empty($_POST['id'])) 
                {
                    $id = Helpers::cleanInput($_POST['id']);
                    $post = Models\BlogPostModel::view(intval($id));

                    //? El post existe y esta visible
                    if (!empty($post))
                    {
                        $comment = Helpers::cleanInput($_POST['text']);

                        //* COMENTARIO
                        if (empty($_POST['id_padre']))
                        {
                            if (Models\BlogPostModel::addComment(intval($id), intval($user->id), $comment)) {
                                Helpers::sendToController("/post/view/$id",
                                [
                                    "msg_comment" => "El comentario se añadio con exito"
                                ]);
                            }
                            else
                            {
                                Helpers::sendToController("/post/view/$id",
                                [
                                    "error" => "El comentario no se pudo añadir :("
                                ]);
                            }
                        }
                        //* RESPUESTA
                        else
                        {
                            $id_padre = Helpers::cleanInput($_POST['id_padre']);

                            //? Comprobar que el comentario padre existe
                            if (Models\BlogPostModel::getCommentParent(intval($id_padre)))
                            {
                                //? Try insert
                                if (Models\BlogPostModel::addAnswer(intval($id), intval($id_padre) ,intval($user->id), $comment))
                                {
                                    Helpers::sendToController("/post/view/$id",
                                    [
                                        "msg_comment" => "La respuesta se añadio con exito."
                                    ]);
                                }
                                //? No se pudo añadir
                                else
                                {
                                    Helpers::sendToController("/post/view/$id",
                                    [
                                        "error" => "La respuesta no se pudo añadir :("
                                    ]);
                                }
                            }
                            //? El comentario padre no existe
                            else
                            {
                                Helpers::sendToController("/post/view/$id",
                                [
                                    "error" => "El comentario al que intentas responder no existe :("
                                ]);
                            }
                        }
                    }
                    //? el post a comentar no existe
                    else 
                    {
                        Helpers::sendToController("/post/all",
                        [
                            "error" => "El post no se pudo encontrar"
                        ]);
                    }
                }
                //? ID no enviado en formulario
                else 
                {
                    Helpers::sendToController("/post/all",
                    [
                        "error" => "El post no se pudo encontrar"
                    ]);
                }
            }
            //? No vienes por POST
            else {
                Helpers::sendTo404();
            }
        }
        //? Usuario no logueado
        else
        {
            Helpers::sendToController("/login/index"
            ,[
                "error" => "Para comentar debes estar logeado."
             ]);
        }
    }

    protected function deleteComment()
    {
        if ($_SERVER['REQUEST_METHOD'] == "POST" && isset($_POST['comment']) && !empty($_POST['id']))
        {
            $user = User::getUser();
            if(!empty($user))
            {
                $id = Helpers::cleanInput($_POST['id']);

                $comment = Models\BlogPostModel::getCommentbyId(intval($id));

                //? El comment existe
                if (!empty($comment))
                {
                    $view = Models\BlogPostModel::view(intval($comment->post_id));
                    //? La vista existe y esta visible
                    if(!empty($view))
                    {
                        //? El usuario es el propietario del comentario o del post
                        if ($user->id == $comment->user_id || $user->id == $view->user_id) 
                        {
                            if (Models\BlogPostModel::deleteComment(intval($id))) {
                                Helpers::sendToController("/post/view/$comment->post_id",
                                [
                                    "msg_comment" => "El comentario se borro con exito"
                                ]);
                            }
                            //? Error eliminar
                            else {
                                Helpers::sendToController("/post/view/$comment->post_id",
                                [
                                    "error" => "No se pudo eliminar el comentario :("
                                ]);
                            }
                        }
                        else {
                            Helpers::sendToController("/post/view/$comment->post_id",
                            [
                                "error" => "No estas autorizado para borrar este post :("
                            ]);
                        }
                    }
                    //? El post no existe o no esta visible
                    else {
                        Helpers::sendToController("/post/all",
                        [
                            "error" => "Ese post no esta disponible."
                        ]);
                    }
                }
                //? el comentario no existe
                else 
                {
                    Helpers::sendToController("/post/all",
                    [
                        "error" => "El comentario no se pudo borrar"
                    ]);
                }
            }
            //? Usuario no logueado
            else
            {
                Helpers::sendToController("/login/index",
                [
                    "error" => "Para comentar debes estar logeado."
                ]);
            }
        }
        //? No vienes por POST
        else {
            Helpers::sendTo404();
        }
    }

    protected function feed(array $params)
    {
        //? Usuario mirando su feed
        //*param 2 es el username en la url
        if (empty($params[2]))
        {
            //* Se recoge el id del usuario en la sesion actual
            $user = User::getUser();

            $categorias = array();

            if(!empty($user))
            {
                //* Me devuelve de la BD todos los registros del usuario del id
                $feed = Models\BlogPostModel::feed($user->username);
                $categorias = array();

                foreach ($feed as $post) {
                    $categoriasPorID = Models\BlogPostModel::getCategoriasByPostID(intval($post["id"]));
                    if(!empty($categoriasPorID)){
                        array_push($categorias,$categoriasPorID);
                    }          
                }
                
                if(!empty($feed))
                {   
                    parent::sendToView([
                        "titulo" => "FEED"
                        ,"list" => $feed
                        ,"categorias" => $categorias
                        ,"page" => __DIR__ . '/../Views/BlogPost/List.php'
                    ]); 
                }
                else
                {
                    parent::sendToView([
                        "titulo" => "LIST"
                        ,"error" => "Tu feed está vacio."
                        ,"categorias" => $categorias
                        ,"page" => __DIR__ . '/../Views/BlogPost/List.php'
                    ]); 
                }
            }
            //? No estas logueado
            else
            {
                Helpers::sendToController("/login",
                [
                    "error" => "Tienes que estar logueado para ver tu feed."
                ]);
            }  
        }
        //? Usuario (logueado o no) mirando un feed
        else
        {
            //* Me devuelve de la BD todos los registros del usuario del id
            $feed = Models\BlogPostModel::feed($params[2]);
            $categorias = array();
            foreach ($feed as $post) {
                $categoriasPorID = Models\BlogPostModel::getCategoriasByPostID(intval($post["id"]));
                if(!empty($categoriasPorID)){
                    array_push($categorias,$categoriasPorID);
                }          
            }
                
            if(!empty($feed))
            {   
                parent::sendToView([
                    "titulo" => "FEED"
                    ,"list" => $feed
                    ,"categorias" => $categorias
                    ,"page" => __DIR__ . '/../Views/BlogPost/List.php'
                ]); 
            }
            else
            {
                parent::sendToView([
                    "titulo" => "LIST"
                    ,"error" => "El usuario que intentas buscar no existe o no tiene posts."
                    ,"page" => __DIR__ . '/../Views/BlogPost/List.php'
                ]); 
            }
        }
    }

    protected function favoritos($params = null) 
    {
        $user = User::getUser();

        if(!empty($user))
        {
            //* Me devuelve de la BD todos los registros del usuario del id
            $favs = Models\BlogPostModel::favorites(intval($user->id));

            $categorias = array();
            foreach ($favs as $post) {
                $categoriasPorID = Models\BlogPostModel::getCategoriasByPostID(intval($post["id"]));
                if(!empty($categoriasPorID)){
                    array_push($categorias,$categoriasPorID);
                }          
            }
            
            if(!empty($favs))
            {   
                parent::sendToView([
                    "titulo" => "FEED"
                    ,"list" => $favs
                    ,"categorias" => $categorias
                    ,"page" => __DIR__ . '/../Views/BlogPost/List.php'
                ]); 
            }
            else
            {
                parent::sendToView([
                    "titulo" => "LIST"
                    ,"error" => "No tienes ningun post favorito."
                    ,"list" => array()
                    ,"categorias" => $categorias
                    ,"page" => __DIR__ . '/../Views/BlogPost/List.php'
                ]); 
            }
        }
        //? No estas logueado
        else
        {
            Helpers::sendToController("/login",
            [
                "error" => "Tienes que estar logueado para ver tus posts favoritos."
            ]);
        }  
    }

    protected function categoria($params = null) 
    {
        if(!empty($params[2]))
        {  
            $user = User::getUser();

            if(!empty($user))
            {
                //TODO: Hacer que para los mios salgan tambien invisibles
            }
            //* Se coge la URL (Nombre de la categoria)
            $categoria = Models\BlogPostModel::categoria(strtolower($params[2]));
            $categorias = array();

            foreach ($categoria as $post) {
                $categoriasPorID = Models\BlogPostModel::getCategoriasByPostID(intval($post["id"]));
                if(!empty($categoriasPorID)){
                    array_push($categorias,$categoriasPorID);
                }          
            }
            
            if(!empty($categoria))
            {
                parent::sendToView([
                    "titulo" => $params[2]
                    ,"list" => $categoria
                    ,"categorias" => $categorias
                    ,"page" => __DIR__ . '/../Views/BlogPost/List.php'
                ]); 
            }
            else
            {
                Helpers::sendToController("/post/all",
                    [
                        "error" => "No se encontro esa categoria o no existen posts de ella."
                    ]);
            }
        }
        else
        {
            Helpers::sendToController("/post/all",
                [
                    "error" => "No se encontro esa categoria o no existen posts de ella."
                ]);
        }
    }

    protected function addFavoritesOrFeed($params = null)
    {
        if ($_SERVER['REQUEST_METHOD'] == "POST" && isset($_POST['type']))
        {
            $user = User::getUser();

            if(!empty($user))
            {
                $post_id = Helpers::cleanInput($_POST['id']);

                $post = Models\BlogPostModel::view(intval($post_id));

                if(!empty($post))
                {
                    $type = Helpers::cleanInput($_POST['type']);
            
                    if ($type == "favoritos")
                    {
                        //? BORRAR DE FAVORITOS
                        if (Models\BlogPostModel::isInFavorites(intval($post_id), intval($user->id)))
                        {
                            $deleted = Models\BlogPostModel::deleteFromFavorites(intval($post_id), intval($user->id));

                            if ($deleted) {
                                //TODO: Redirigir a la pagina desde donde se hizo el like
                                Helpers::sendToController("/post/all");
                            }
                            else {
                                Helpers::sendToController("/post/all",
                                [
                                    "error" => "no se pudo eliminar de favoritos :("
                                ]);
                            }
                        }
                        //? AÑADIR A FAVORITOS
                        else
                        {
                            $added = Models\BlogPostModel::addToFavorites(intval($post_id), $user->id);
                        
                            if ($added)
                            {
                                //TODO: Redirigir a la pagina desde donde se hizo el add to feed
                                Helpers::sendToController("/post/all");
                            }
                            else
                            {
                                Helpers::sendToController("/post/all",
                                [
                                    "error" => "no se pudo añadir a favoritos :("
                                ]);
                            }
                        }
                    }
                    else if($type == "feed")
                    {
                        //? borrar del feed
                        if (Models\BlogPostModel::isInFeed(intval($post_id), intval($user->id)))
                        {
                            $deleted = Models\BlogPostModel::deleteFromFeed(intval($post_id), intval($user->id));

                            if ($deleted) {
                                Helpers::sendToController("/post/all");
                            }
                            else {
                                Helpers::sendToController("/post/all",
                                [
                                    "error" => "no se pudo eliminar de favoritos :("
                                ]);
                            }
                        }
                        //? AÑADIR Al FEED
                        else
                        {
                            $added = Models\BlogPostModel::addToFeed(intval($post_id), $user->id);
                        
                            if ($added)
                            {
                                Helpers::sendToController("/post/all");
                            }
                            else
                            {
                                Helpers::sendToController("/post/all",
                                [
                                    "error" => "no se pudo añadir a favoritos :("
                                ]);
                            }
                        }
                    }
                }
                //? No hay post
                else
                {
                    Helpers::sendToController("/post/all",
                    [
                        "error" => "El post que intentas añadir no existe."
                    ]);
                }
            }
            //? No estas logueado
            else
            {
                Helpers::sendToController("/login",
                [
                    "error" => "Tienes que estar logueado para ver tu feed."
                ]);
            }  
        }
    }
}