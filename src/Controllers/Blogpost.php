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

                    $imgs_slider = array();
                    foreach ($imgs as $img) {
                        if ($img->pos == "inline" || $img->pos == "ending") {
                            array_push($imgs_slider, $img);
                        }
                    }

                    //? Si el post encontrado es invisible (privado)
                    if(!$view->visible)
                    {
                        //? Si el id logeado es el creador del post
                        if($view->user_id==$user->id)
                        {
                            //* Te enseña el post
                            parent::sendToView([
                                "titulo" => "POST"
                                ,"js" => array("addComment.js")
                                ,"css" => array("post.css")
                                ,"blogPost" => $view
                                ,"autor" => User::getUsernameById(intval($view->user_id))
                                ,"comments" => $comments
                                ,"categorias" => $categorias
                                ,"categoriasTodas" => $categoriasTodas
                                ,"imgs" => $imgs
                                ,"imgs_slider" => $imgs_slider
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
                            ,"js" => array("addComment.js")
                            ,"css" => array("post.css")
                            ,"blogPost" => $view
                            ,"autor" => User::getUsernameById(intval($view->user_id))
                            ,"comments" => $comments
                            ,"categorias" => $categorias
                            ,"categoriasTodas" => $categoriasTodas
                            ,"imgs" => $imgs
                            ,"imgs_slider" => $imgs_slider
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

                    $imgs_slider = array();
                    foreach ($imgs as $img) {
                        if ($img->pos == "inline" || $img->pos == "ending") {
                            array_push($imgs_slider, $img);
                        }
                    }

                    parent::sendToView([
                        "titulo" => "POST"
                        ,"js" => array("addComment.js")
                        ,"css" => array("post.css")
                        ,"blogPost" => $view
                        ,"autor" => User::getUsernameById(intval($view->user_id))
                        ,"comments" => $comments
                        ,"categorias" => $categorias
                        ,"categoriasTodas" => $categoriasTodas
                        ,"imgs" => $imgs
                        ,"imgs_slider" => $imgs_slider
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
                $categorias = array();
                foreach ($list as $post) 
                {
                    $categoriasPorID = Models\BlogPostModel::getCategoriasByPostID(intval($post["id"]));

                    if(!empty($categoriasPorID)) {
                        array_push($categorias,$categoriasPorID);
                    }          
                }
                parent::sendToView([
                    "js" => array("addFavFeed.js")
                    ,"titulo" => "LIST"
                    ,"categorias" => $categorias
                    ,"list" => $list
                    ,"page" => __DIR__ . '/../Views/BlogPost/List.php'
                ]); 
            }
            else
            {
                parent::sendToView([
                    "titulo" => "LIST"
                    ,"js" => array("addFavFeed.js")
                    ,"list" => $list
                    ,"categorias" => array()
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
                ,"js" => array("addFavFeed.js")
                ,"list" => $list
                ,"categorias" => $categorias
                ,"page" => __DIR__ . '/../Views/BlogPost/List.php'
            ]); 
        }
        else
        {
            parent::sendToView([
                "titulo" => "LIST"
                ,"js" => array("addFavFeed.js")
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
                            ,"js" => array("addFavFeed.js")
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
                            ,"js" => array("addFavFeed.js")
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
                            ,"js" => array("addFavFeed.js")
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
                        ,"js" => array("addFavFeed.js")
                        ,"list" => $author
                        ,"categorias" => $categorias
                        ,"page" => __DIR__ . '/../Views/BlogPost/List.php'
                    ]);
                }
                else
                {
                    Helpers::sendToController("/post/all",
                    [
                        "error" => "No existe este usuario."
                    ]);
                }
            }
        }
        else {
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
                if(!empty($_POST['categorias'])){
                    $categorias = $_POST['categorias'];
                }else{
                    $categorias = array();
                }

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
                                "js" => array("deleteImg.js", "setPosImg.js")
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

            if (is_int(intval(Helpers::cleanInput($_POST['id']))))
            {
               $deleted = Models\BlogPostModel::deleteImg(intval(Helpers::cleanInput($_POST['id'])));

                $response['status'] = $deleted;
            }
            else {
                $response = array("status" => "error");
            }
            
            echo json_encode($response);
        }
    }

    protected function setImgPosInPost()
    {
        if ($_POST && !empty($_POST['id'] && !empty($_POST['pos'])))
        {
            $response = array("status" => "");
            $id = Helpers::cleanInput($_POST['id']);
            $pos = Helpers::cleanInput($_POST['pos']);

            if (in_array($pos, ["starting", "inline", "ending", "side", "portada"]) && is_int(intval($id)))
            {
                $updated = Models\BlogPostModel::setImgPosInPost(intval($id), $pos);

                $response['status'] = $updated;
                $response['pos'] = $pos;
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
            if ($_POST && !empty($_POST['id']) && !empty($_POST['text']))
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
                            $insertId = Models\BlogPostModel::addComment(intval($id), intval($user->id), $comment);

                            $addedComment = Models\BlogPostModel::getCommentbyId(intval($insertId));

                            if (!empty($addedComment)) 
                            {
                                $user_notificado = Models\BlogPostModel::getUserByPostID(intval($id));

                                //? Si el usuario que crea la notificacion no es el mismo que las recibe
                                if(!empty($user_notificado ) && intval($user_notificado->id)!=intval($user->id))
                                {
                                    //? El 1 representa el tipo de notificacion, 3=COMENTARIO en la base de datos
                                    $notificacion = Models\BlogPostModel::crearNotificacion(intval($user_notificado->id),$user->id, intval($id), 3); 
                                }

                                $avatar = (!empty($addedComment->avatar)) ? base64_encode($addedComment->avatar) : "iVBORw0KGgoAAAANSUhEUgAAA4QAAAKBCAYAAAAP9GU8AAAhxUlEQVR42u3d22HjOLYF0MrAITAD
                                haAMGAIzYAYKgRkwBGagEJiBQlAG/VnTu2Y87a7yQ7b1IID1sX7vrfbYwNkEcM6Pv/766ycAAADt
                                +eGHAAAAIBACAAAgEAIAACAQAgAAIBACAAAgEAIAACAQAgAAIBACAAAgEAIAACAQAgAAIBACAAAg
                                EAIAACAQAgAAIBACAAAgEAIAACAQAgAAIBACAAAgEAIAACAQAgAAIBACAAAgEAIAACAQAgAAIBAC
                                AAAgEAIAAAiEAAAACIQAAAAIhAAAAAiEAAAACIQAAAAIhAAAAAiEAAAACIQAAAAIhAAAAAiEAAAA
                                CIQAAAAIhAAAAAiEAAAACIQAAAAIhAAAAAiEAAAACIQAtOx4PP4yz/PPw+HwyzAMP/f7/f89PT39
                                /PHjx1W8/L8bz/8/p2n6/7/lfD773wYAgRAAvut0Ov0KWc/BKyFst9tdLeDdUtd1v/69Caj5ty/L
                                8nNdV/+7AiAQAsBLOVV7Dn4JUKWEvu+GRUERAIEQgGbD37WvdJYuP49xHH+FxJyO+n0BQCAEoPhr
                                n3nj18LJ37UlLPd9/+uNolNEAARCAIo4AXwOgLkaKdjdJiA6QQRAIARgE3J6lSugTgDv/xYxwTtX
                                TP0eAiAQAnA3CSEJI94AbkdOD3M6a/QFAAIhAEJg4w1qcrVUOARAIARACHRyKBwCIBACcNmbwIw/
                                EALr480hAAIhAH947g6qMUw7DWnSCEi3UgAEQoDGTwNdCXWl1KkhgEDoBwHQkJwGpvGIQMTvp4be
                                GgIIhABUei00Bb+B8bwnp8U5NXadFEAgBKACKew1ieGr10mPx6O/IwCBEIASg2BOegQbrjHXUDAE
                                EAgBEAQRDP2dAQiEAAiCCIb+7gAEQgAEQQRDAARCAO7nuWuoZjFsofmMrqQAAiEAdzJNkyDI5qSb
                                rTmGAAIhADeS63nmCLL1OYb5YOHvFUAgBOCK7wTzXkvgoBT5cOF9IYBACMA35Z2ggIH3hQAIhACu
                                h4JrpAAIhAC1SlMOYySo0W63+7muq79zAIEQgNcsy6J7KNXLNWh/7wACIQAvTgXz1kpYwGkhAAIh
                                gFNBcFoIgEAI4K0gOC0EQCAEqIYOoqATKYBACNAgcwXh/bmFOT23VgAIhADVXRHd7/eKfvhATs9d
                                IQUQCAGquiKqcQx8jiukAAIhgCui4AqptQRAIAQo74qo2YKgCymAQAjQmBSvKWIV83C9LqSZ2Wl9
                                ARAIATbNoHkwyB5AIARoUJpgKNrhtoZh8K4QQCAE2JYUqYp1uN+7QqEQQCAE2ETzGPMFwbxCAIEQ
                                oMEwqHkMPLbZjFAIIBACPKSTaE4oFOXwePM8W5cABEKA+4VBnURBKAQQCAGEQWAj0unXOgUgEALc
                                RE4gFN2w/bEU1isAgRBAGAShEACBEEAYBKEQAIEQQBgEoRAAgRBAGAShEEAg9EMAEAZBKAQQCAEQ
                                BkEoBBAIAZp0PB4Vz1CpcRytcwACIcDrDJ2H+uUGgPUOQCAEEAZBKAQQCP0QgNadz2dhEBqzLIv1
                                D0AgBITB88/dbqdAhsbkI1BuBlgHAYHQDwFomDAIbYfC0+lkLQQEQoAWpQ29ohjalo9CuSlgTQQE
                                QoCGTNOkGAZ+2e/31kVAIARohcHzgMH1AAIh0CDjJQDjKAAEQqDRjqJd1yl8gTfpPAoIhACVyjsh
                                BS/wUedRTWYAgRCgMuM4KnaBizuPWjcBgRCgEsuyKHKBT8lHJOsnIBACFC5DpzWRAb4iH5Oso4BA
                                CFCwXP1S2AJffU+Yj0rWUkAgBPBuEPCeEEAgBCjB8XhUzALeEwIIhEBr0jLeu0HgmvKRyfoKCIQA
                                Bej7XgELXFXXdeYTAgIhwNZN06R4BW4iH5uss4BACLBRRkwARlEACIRAo/b7vYIVuPkoCldHAYEQ
                                wFVRwNVRAIEQwFVRwNVRAIEQwFVRwNVRAIEQ4D7ylV5xChhYDyAQAo0xgB4wsB5AIAQala/zClLg
                                kXa7nfUYEAgB7i1f5RWjwBYcDgfrMiAQAmgkA7TaYCbdjq3NgEAIcAfzPCtCgU0ZhsH6DAiEABrJ
                                ABrMAAiEADeRtzoKT2CLcpXdOg0IhAA3kjc6ik5gy3Kl3XoNCIQAN5A3OgpOYMu6rrNeAwIhwLWt
                                66rYBIowTZN1GxAIAa7JmAmgpDEUaYBl7QYEQoArMIQeMKweQCAEnA4COCUEEAgBp4MATgkBBELA
                                6SCAU0IAgRBwOgjglBBAIAScDgI4JQQQCAGngwBOCQEEQqAYfd8rJIFqTgmt64BACHCh0+mkiASq
                                Ms+z9R0QCAEuMQyDAhKoStd11ndAIAT4SJovKB6BGi3LYp0HBEKA96T5gsIRqFE6J1vnAYEQ4B25
                                VqVwBGqVN9LWekAgBHhFmi4oGIGa5Y209R4QCAFeYRA9YFA9gEAINMioCcAICgCBEGjUOI4KRaAJ
                                u93Oug8IhAAv5RqVQhFoxbqu1n5AIASIzOZSIAKaywAIhECD+r5XIALNNZex/gMCIdC8dNtTHAIt
                                yu0I+wAgEAJNm6ZJYQg0Kbcj7AOAQAg0Ld32FIZAq8wkBARCoFlmDwJmEppJCAiEgOuiAK6NAgiE
                                gOuiAK6NAgiEgOuiAK6NAgiEgOuiAK6NAgiEgOuiAK6NAgiEQKkMowcwpB4QCIFG5b2MAhDgH8Mw
                                2B8AgRBoQ97LKAAB/vH09GR/AARCoA0pfBSAAP+2rqs9AhAIgbodj0eFH8ArDoeDfQIQCIG6peBR
                                +AH8ab/f2ycAgRAwbgLA+AkAgRAwbgLA+AkAgRCoQQodBR/A28ZxtF8AAiFQpxQ6Cj6At+Vavf0C
                                EAiBKqVhgoIP4H32C0AgBOpccBR6AB/KeB57BiAQAlUxfxDAPEJAIAQaZf4ggHmEgEAINKrve4Ue
                                wAWenp7sG4BACNSl6zqFHsCFTqeTvQMQCIE6GEgPYEA9IBACGsoAoLEMIBACGsoAoLEMIBACGsoA
                                oLEMIBACddrtdgo8gE/K+2t7CCAQAuUvNAo7gE/L+2t7CCAQAkVb11VhB/AF0zTZRwCBEChbWqcr
                                7AA+bxxH+wggEAI6jALoNAogEAIFGoZBYQfwBV3X2UcAgRAoW75wK+wAvsY+AgiEQNEyS0tRB/A1
                                acxlLwEEQsDICQCjJwAEQsDICQCjJwAEQmDj8mVbQQfwdenUbD8BBEKgSPM8K+gAviGdmu0ngEAI
                                FMkMQgCzCAGBEBAIAfiC3W5nPwEEQqBMZhACmEUICISAQAiAQAgIhEBLctVJMQfwPefz2Z4CCIRA
                                gQuMQg7AcHpAIAQEQgAEQkAgBARCAARCQCAEapY3Lwo5gO9blsW+AgiEQFnyRVshB/B9melqXwEE
                                QkAgBBAIAQRCQCAEEAgBBEJAIAQQCAEEQkAgBBAIAQRCQCAEEAgBBEJAIAQQCAEEQkAgBBAIAQRC
                                QCAEEAgBBEJAIAQQCAGBEEAgBBAIAYEQQCAEEAgBgRBAIAQQCAGBEEAgBBAIAYEQQCAEEAgBgRBA
                                IAQQCAGBEEAgBBAIAYEQEAgVcgACISAQAs0uMAo5gG/LBzZ7CiAQAgIhgEAIIBACAiGAQAggEAIC
                                IYBACCAQAluz3+8VcwDfZD8BBEJAIAQQCAEEQqAcfd8r5gAEQkAgBFqU2VmKOYCvy00L+wkgEAIC
                                IYBACCAQAuVYlkVBB/ANwzDYTwCBEChTWqUr6AC+Ljct7CeAQAgU6XQ6KegAvmGeZ/sJIBACBS8y
                                CjoAQ+kBgRBoU9d1ijqAL8pNC3sJIBACxTKcHsAMQkAgBBo1jqOiDuALdrudfQQQCIGymUUIYAYh
                                IBACRk8AYOQEIBACRk8AYOQEIBACRk8AYOQEIBACOo0CoMMoIBAClRmGQXEH8AmZ4Wr/AARCoArT
                                NCnwAD6h73v7ByAQAjqNAugwCiAQAhrLAGgoAyAQAiXa7XaKPIALnc9newcgEAIaywBoKAMgEAIa
                                ywBoKAMgEAIlWtdVoQdwgXxAs28AAiFQnaenJ8UewAfyAc2eAQiEQHVyDUqxB/A++wUgEAJVylwt
                                xR7A2/b7vf0CEAiBOhlQD2AgPSAQAt4RAuD9ICAQAt4RAhD5YGafAARCoGrmEQKYPwgIhECjTqeT
                                wg/A/EFAIARa1XWd4g/gN/lgZo8ABEKgeuM4Kv4AXsiHMvsDIBACTViWRQEI8EI+lNkfAIEQMH4C
                                oEGZ02pvAARCwPgJAOMmAARCoG7zPCsEAf42DIN9ARAIgbacz2eFIMDf8q7avgAIhIBrowCuiwII
                                hIBrowCuiwIIhIBrowCuiwIIhIBrowCuiwIIhEA1DKkHDKMHEAiBhhlSD7RoXVd7ACAQAqSpguIQ
                                aEnXddZ/QCAEiHwlVyACLZmmyfoPCIQAz3a7nSIRaEa6LFv7AYEQ4H/MJATMHgQQCIFG5Wu55jJA
                                C47Ho3UfEAgBNJcBNJMBEAgBNJcBNJMBEAiB1u33e0UjUKVci9dMBhAIATSXATSTARAIAV6TNzaK
                                R6A2p9PJGg8IhAAfyRsbxSNQk77vre+AQAhwCSMoAKMmAARCoGGHw0ERCVQhzbKs64BACPDJU0KF
                                JFCDZVms64BACPBZBtUDBtEDCIRAo9KRT0EJlCyjdKzngEAI4JQQcDoIIBACOCUEnA4CCIQATgkB
                                p4MAAiHA+6eE5hICOosCCIRAo8wlBMwdBBAIgUZlLqFTQqAEx+PRug0IhABOCQGngwACIcDVpFGD
                                ohPYqrx5tlYDAiHAjaRRg6IT2KJxHK3TgEAIcGu5kqX4BLYkb5zz1tkaDQiEADe2rqsCFNiUaZqs
                                z4BACHAvuZqlCAW2YLfbWZcBgRDgnoyhAIyZABAIgYbN86wYBR5qGAbrMSAQAmgwA2gkAyAQAtxV
                                Zn4pTIFHyC0F6zAgEAI82OFwUJwCd5XbCdZfQCAE2Ih0+VOkAve6KprbCdZeQCAE2AizCQEzBwEE
                                QsDVUQBXRQEEQsDVUQBXRQEEQsDVUQBXRQEEQqB+KdoUr8A19X1vfQUEQoBSGFgPGEAPIBACjUrx
                                liJOMQt81/F4tK4CAiFAaZZlUcwC3zKOo/UUEAgBSpViTlELfEW6FltHAYEQoHBGUQBGTAAIhID3
                                hADeDQIIhEBrUtwpcoFLHA4H6yYgEALUxnxCwLxBAIEQaNgwDIpe4M0mMuYNAgIhQOXvCTWZAV5r
                                IrOuq3USEAgBapfOgZrMAC9lbqn1ERAIARqRkwChEIi8L7YuAgIhQGPmeVYMQ+Pyrth6CAiEAEIh
                                oKMogEAIoPMooKMogEAIIBQCwiCAQAggFALl67pOGAQQCAH+ZEYhmDUIIBACCIWKZxAGAQRCAKEQ
                                EAYBBEIAoRAQBgEEQgChEBAGAQRCAKEQEAYBBEIAoRAQBgEEQgChEBAGAQRCAKEQEAYBBEKASgzD
                                oPiGjcnHmny0sUYBCIQAQiEIgwAIhAC3M02TYhweLB9nhEEAgRDgIeZ5VpTDA8OgdQhAIAR4qOPx
                                +KuZhQId7icn9NYfAIEQYBPS2bDrOoU63KGTaE7mrTsAAiHAphhLAcZKAAiEADqQKt5BJ1EAgRBA
                                sxlA8xgAgRCgyXeFms3A93gvCCAQAhT9rnC/3yvs4ZPSpMl7QQCBEKAK4zgq8uFC+YjivSCAQAhQ
                                lWVZXCGFDxwOB+sFgEAI4AopuCIKgEAI8MnAlROGXNHc8r8z/0YhAP6r7/tNXxFNY5t0Oj2dTtZZ
                                QCAE2HLIenklc+ut6nMaYpA9rQ+az1XqLf+d/j5CJh+bvG8EBEKAjRVsuW5W4vyyFJYaztBq45it
                                n7i9NU80QTYfoARDQCAEeKDj8XjRe7wShlrnv+WtUAu1nQpO01TEh6ZL3j2akwgIhAB3llOFvDn6
                                TBFaQih0WohTwXLC4Eu5+p2POtZnQCAEuEPDmK8WoynaSrji5W0h3go+Tj4efac5jsYzgEAIcKMv
                                9teY4VdKKHytSQ6UKAGrlL+574TB32cpel8ICIQAd3wn+NlQWMq8s69cj4UtKOkaZcLbtf/O8r6w
                                lFNRQCD0gwA2WaBd62v9W1fYShqCrekMmsbcbq255RXtUt5NAgIhQHXXQ2t61+QaKa6Hlv1e1zVS
                                QCAEuKA4u/b10EuU1jb+1qen8JVTsJJO3J/Xm3t/XHGNFBAIAW7QPfRaJxul/dy8L8Q7wa/fQnjk
                                z003UkAgBNjg27iccpR4pesWjXeg1oHsW5n1WdpbS0AgBGji2mNJHUgFQwTBx3YSbfW6LSAQAnxL
                                3tBsuTFKic1mBEMEwW00j/lO0xn7AyAQAtWfCpb05q30Ak0wpPUgWMIHqFpuKAACIUA1RdnvzR9K
                                bxWfYKgrKZ+9xlh6EIxHN6tyWggIhIBTwcJOBd86Janhq326GiYYmmPIex9ASuwa+tq6U/rpuNNC
                                QCAEquggWlP4qKUjYIrl/Ldspbsrj38zm86btYxBqG3dcVoICIRAkYFjK63dXSH9+CqvWYbtzhDM
                                tdCafp9LvSJ6yRVecwsBgRAopptf7SdPtVwh/f06aYppp4b1nwbm2nBtv781XBG95H+7Gt51AgIh
                                ULFav863dpXruQmNt4Z1nWzXGiZKbVjllgIgEAK+zldy7a7mq1wJEa6Ulvu7mbeitYaHmq+mX3JL
                                oYbmP4BACPg6X81VrloazrxXfAuH5YTA2t+bJQy53qzhDCAQAg/W6tf51hs/JBzmQ4Brpdu5QthC
                                CGz9VPC9dccVUkAgBO7egCQnEYqxNk8LXzutSZHud+J+1wUTxhPKWwoCTgXfX3dcIQUEQsAVUaeF
                                m7lamsCieL9esd/SKaBTQVdIAYEQcEW0mkK+9QItASYB0Qni508AEwBrGw/xlQ9QPiy4QgoIhMAG
                                vtAr5r/X6MN1rn9f/UtQzqlX68V+PhqkgM/PI+HH8PF/PiRoYPS936vWPyYAAiFwxeLdFdHryKmP
                                L/dv/57lRCwniQlINf7O5b8rvwMJf/nvFf5el98Da851tPaeGRAIgRsUZooqTWceHRRzcpYQlTCV
                                ULXV0+qcdubfl5Ot/HtzVVbw0zTGhyhAIASKvCKaIkIx5RppCSHiWULYs+fw+LuPAsfzFc7fPYe8
                                Z89hLxTbroeWsN74MAEIhMDFxZn3gvedIadQo9UPTwnX1gGjKQCBENiINCHwducx8nbOSROtyAmr
                                tca7QkAgBDZWoCmWtjGmQjCk5nXGO8FtvCv0+wgIhMD/eS+4vWCYwtnvJjW99cxbTH/f23pX6OMT
                                IBCCNzyaOWx8QLlgiCDILdcY8woBgRA0j0EwBEFQsxlAIAQ0j6GEYOiaF4Ig1+ajEwiEgO5+aD4D
                                msU03u3Y7zMIhEDlRZuip65gmALOHEMe9QY5IwwEQR1IAYEQKECCg2Kn7iLOOyDu9f4464mbBjqQ
                                AgIhUAhjJdoq5Lwz5FbvA3UlFgoBgRAo7EqXBg/tXifNhwDXSXEtlO+sI8ZSgEAIFFzIGSuBU0O+
                                YlkWNwsQCkEgBIRBajw19NaQ994GOg3ErEIQCIGCmTHIpTMNU/z78i8E5kqoD0iYVQgCISAMIhz6
                                O2rkBkEKe++LEQoBgRCEQRAOGzoJFAIRCgGBEIRBuDgc5s1hGoz4OytzXTgcDq6DIhQCAiHULA/9
                                hUHuITPocsrk9HDbV0ET4q0J3EN+1/ztgUAIPFCKP0UJjzw9zO+gWYePC4A5vc0VX6eACIWAQAjC
                                IDw8IDpBvO07QAEQoRAQCAFhkCLmlqWBSd6wJcQ4Rfz86V+ug+fnl6u6ZgMiFAICISAMUk1IzO+x
                                Qdf/PvkT/hAKAYEQEAZp8rrpc1DMldMExdpOFJ9P/F4GPyMgEAoBgRAQBuGCsJgi82Vg3FJofA57
                                kb/T/Dvzzi//bqEPoRAQCIFvM2cQLguOz54D5FteBsuXJ3dveRnwQlMXeF/+ZuzfIBACwiAAhtcD
                                AiEgDAIgFAICISAMAiAUAgIhIAwCIBQCAiHwomOhhhUA1CYfO+3zIBACwiAADcrNF6EQBEJAGARA
                                KAQEQuClzE1TLADQwtzQfAS194NACAiDADQoN2KEQhAIgb9N06Q4AKA5+/1eHQACIbQtbbgVBQC0
                                Kjdk1AMgEEKzswYVAwC07nA4qAtAIASD5wHA4HpAIATjJQDA4HpAIIQaCYMAYEYhCIRgvAQAYBwF
                                CIRgvAQAYBwFCIRQpWVZbPIAcKFxHNUPIBCCjqIAoPMoIBCCjqIAoPMoIBBCSfq+t6EDwDc6j2oy
                                AwIhFOlwONjMAeAKnUfVFSAQgiYyANCojG1SX4BACJrIAIAmM4BACJrIAEBr7wk1mQGBEDYtV1ps
                                2gBwG13XaTIDAiFs0zRNNmsAuLF08FZ3gEAIm3s3aJMGgPvIR1j1BwiEsJl3g7nCYoMGgPvxnhAE
                                QjB8HgC8JwQEQvBuEAC8JwQEQjBvEAC8JwQEQjBvEADMJwQEQriqcRxtwgCwEflIqz4BgRDuYlkW
                                my8AbEw+1qpTQCCEm18V9W4QALbpeDyqV0AgBCMmAKDV94RGUYBACEZMAIBRFIBACNdxOp1cFQWA
                                QuS9v/oFBEK4mv1+b4MFgIKujuZjrhoGBEJwVRQAGpSPueoYEAjBVVEAaFQ+6qpnQCAEV0UBwNVR
                                QCAEV0UBwNVRQCAEV0UBQNdREAj9EMBVUQCo++qogfUgEMJF5nm2eQJAZQysB4EQPpSvh66KAkCd
                                jsejegcEQnhbvh7aMAGgTl3XuToKAiG8Ll8NbZYAULdxHNU9IBDCn1dF89XQRgkA9VvXVf0DAiH8
                                43A42CABwGxCEAihxZmDNkcAaMs0TeogEAjBzEEAMJsQBEJo0rIsNkUAaNQwDOohBEI/BDSSsSEC
                                gAYzIBCCRjIAgAYzIBBC/Y1k8nbARggAzPOsPkIghJb0fW8DBAB+yRMSDWYQCKERx+PR5gcA/Eue
                                kqiTEAihAbvdzsYHAPwxhiJPStRKCIRQsbwRsOkBAMZQgECIMRMAAP+SpyXqJgRCMGYCADCGAgRC
                                qOV00JgJAOASy7KonxAIoSbjONrgAICLx1ConxAIoaIh9DY3AOAzDKtHIIRKpGOYjQ0AcEoIAiFO
                                BwEALmJYPQIhFC6dwmxoAMBXpCFdGtOpqRAIoUCZI2QzAwCcEoJAiNNBAACnhCAQ4nQQAMApIQiE
                                OB0EAHBKiEAITgcBAJwSIhCC00EAAKeECITgdBAAwCkhAiE4HQQAnBI6JUQghG1a19VmBQA4JQSB
                                kBYNw2CjAgBufkqo7kIghI05nU42KQDgLuZ5Vn8hEILTQQCgRV3Xqb8QCMHpIADglBAEQnioPO62
                                MQEA97Tb7dRhCITwaGn9nMfdNiYA4N4y/1g9hkAID5TrGjYkAOARMv9YPYZACA+UR902JADgUdLL
                                QE2GQAgPsCyLjQgAeKh0OleXIRDCA+Saho0IAHik9DJITwO1GQIh3NG6rjYhAGATpmlSnyEQgkH0
                                AECLDKpHIIQ7j5qw+QAAW5LeBuo0BEK4g1zLsPEAAEZQgECIURMAAEZQgEBIC47How0HANikcRzV
                                awiEcEt939twAIDNjqBQryEQwo3kGobNBgDYsnme1W0IhHALh8PBRgMAaC4DAiGayQAAaC4DAiGa
                                yQAAaC4DAiH1GobBBgMAaC4DAiGtOZ/PNhcAoCjLsqjjEAjhGtKty8YCAJQko7LUcQiEcAW73c7G
                                AgAUJ7ec1HIIhGD2IADQoGma1HMIhPAd6dJlQwEASpRbTuo5BEIwexAAaJSZhAiE8EXrutpIAICi
                                mUmIQAhmDwIAjcptJ3UdAiF8QYa62kgAgNLl1pPaDoEQPiHDXG0gAIBroyAQ4rooAIBroyAQ4roo
                                AIBroyAQ4rooAIBroyAQ4rooAIBroyAQ4rooAIBroyAQ4rooAIBroyAQUowslDYMAMC1URAIaVAW
                                ShsGAODaKAiENCYLpI0CAKjZNE3qPgRCeM3hcLBRAABV2+126j4EQnhNFkgbBQBQu9PppPZDIISX
                                sjDaIACAFszzrP5DIISXsjDaIACAFvR9r/5DIISXsjDaIACAFjw9Pan/EAjhpSyMNggAoBXH41EN
                                iEAIkQXRxgAAtGQcR3UgAiFEFkQbAwBg/AQIhBg3AQBg/AQIhLTgfD7bEAAA4ydAIMS4CQCAdgzD
                                oB5EIKRtWQhtCABAi7quUw8iENK2LIQ2BACgVeu6qgkRCGlTHlLbCACAlk3TpC5EIMT7QQCAFvV9
                                ry5EIMT7QQCAFj09PakLEQjxfhAAwDtCEAjxfhAAwDtCEAjxfhAAoH7mESIQ0pxxHG0AAAA/zCNE
                                IKRBu93OBgAA8D95TqNGRCCkCefz2cIPAPDCsizqRARC2nA8Hi38AAAv5DmNOhGBkCYcDgcLPwDA
                                C/v9Xp2IQEgbsuBZ+AEA/k2diEBIE56eniz6AAC/ybMatSICIQbSAwA0yIB6BEKqlw5aFnwAgD8Z
                                UI9AiIYyAACNypxm9SICIRrKAABoLAMCIRrKAABoLAMCIRrKAABoLAMCIRrKAABoLAMCIRrKAABU
                                JP0W1I0IhFSp73sLPQCAxjIIhLQorZQt8gAA71vXVe2IQEiFv2wWeACAD6XvgtoRgZCqpIWyBR4A
                                4GPpu6B+RCCkKvM8W+ABAC6QvgvqRwRCdBgFAGhQ+i6oHxEI0WEUAECnURAI0WEUAKAlp9NJDYlA
                                iA6jAAAtSkM+NSQCIVXIFy4LOwCATqMIhBg5AQDAB8ZxVEciEKLDKABAi/b7vToSgRCBEACgRV3X
                                qSMRCKlDvnBZ2AEAPkcdiUCIkRMAAI1a11UtiUCIkRMAAC0yegKBEIEQAKBRy7KoJREIEQgBAFpk
                                FiECIQIhAIBACAIhAiEAgDeEIBAiEAIACIQgEGLsBABAnU6nk1oSgZDyDcNgUQcA+ISnpyd1JAIh
                                dZjn2cIOAPAJ+aCujkQgpArn89nCDgDwCfmgro5EIKQafd9b3AEALrwumg/qakgEQqqRLlkWeACA
                                j43jqH5EIKQ++/3eIg8A8MHpoO6iCIQ4JQQAaNDhcFA3IhBSr1yBsNgDAPwps5vViwiEVN9x1KB6
                                AIA/r4qu66peRCCkfrkXn0XP4g8A8F/LsqgTEQhpR76ACYUAAGYOIhAiFAIACIMgEOJNIQCAN4Mg
                                ENKUtFi2OQAALej7/tdHcTUgAiH81mzG8HoAoFZd1/2ay6zuQyCEDwbY58uZjQMAqEGex3griEAI
                                XzgxnKbJqSEAUGQIHMfRO0EEQrjmyWECYt4bJiQCAGxFwl9qlNQr3gciEAIAACAQAgAAIBACAAAg
                                EAIAACAQAgAAIBACAAAgEAIAACAQAgAAIBACAAAgEAIAACAQAgAAIBACAAAgEAIAACAQAgAAIBAC
                                AAAgEAIAAAiEfhAAAAACIQAAAAIhAAAAAiEAAAACIQAAAAIhAAAAAiEAAAACIQAAAAIhAAAAAiEA
                                AAACIQAAAAIhAAAAAiEAAAACIQAAAAIhAAAAAiEAAAACIQAAAAIhAAAAAiEAAAACIQAAAAIhAAAA
                                AiEAAAACIQAAAAIhAABAq/4Drz7zxD9utVEAAAAASUVORK5CYII=";
                                
                                $response = [
                                    "status" => true,
                                    "comment" => "<div class='card my-4'>
                                    <div class='card-header'>
                                    <img class='rounded-circle' style='width:70px;heigth:70px;' src='data:image/jpg;charset=utf8;base64,$avatar' /> 
                                    <div style='margin-top:-7%; margin-left:12%'>
                                    <h4 style='text-align:top'>".  User::getUsernameById(intval($addedComment->user_id))."
                                    </h4>
                                        <!-- ELIMINAR -->
                                            <button
                                            id=$insertId
                                            name='eliminar'
                                            class='btn btn-outline-danger btn-sm float-right'
                                            data-toggle='tooltip' 
                                            data-placement='top'
                                            title='Eliminar'>
                                            <i class='fas fa-trash-alt'></i>
                                            </button>
                                        </div>
                                    </div>
                                    <div class='card-body'>
                                        <p>". $addedComment->text ."</p>
                                    </div>
                                    <blockquote class='blockquote text-right'>
                                        <footer class='blockquote-footer mr-3'>". $addedComment->date ."</footer>
                                    </blockquote>
                                    <hr>
                                    <div id='$insertId-response'>
                                    <!-- Responder -->
                                        <div class='col-12'>
                                            <div class='input-group mb-2'>
                                            <div class='input-group-prepend'>
                                                <div class='input-group-text'>Responde a ". User::getUsernameById(intval($addedComment->user_id)) ."</div>
                                            </div>
                                                <input type='hidden' name='id' value='$id'>
                                                <input type='hidden' name='id_padre' value='". $insertId ."'>
                                                <textarea name='text' id='' cols='40' rows='1'></textarea>
                                                <button type='submit' name='comment' class='btn btn-primary mb-2 ml-2'>Go!</button>
                                            </div>
                                        </div>
                                    </div>"
                                ];
    
                                echo json_encode($response);
                            }
                            else {
                                echo json_encode(["error" => "El comentario no se pudo añadir :("]);
                            }
                        }
                        //* RESPUESTA
                        else
                        {
                            $id_padre = Helpers::cleanInput($_POST['id_padre']);

                            //? Comprobar que el comentario padre existe
                            if (Models\BlogPostModel::getCommentParent(intval($id_padre)))
                            {
                                $insertId = Models\BlogPostModel::addAnswer(intval($id), intval($id_padre) ,intval($user->id), $comment);

                                $addedComment = Models\BlogPostModel::getCommentbyId(intval($insertId));
                                
                                //? Try insert
                                if (!empty($addedComment))
                                {
                                    $user_notificado = Models\BlogPostModel::getUserByCommentID(intval($id_padre)); 

                                    //? Si el usuario que crea la notificacion no es el mismo que las recibe
                                    if(!empty($user_notificado ) && intval($user_notificado->user_id) != intval($user->id))
                                    {
                                        //? El 1 representa el tipo de notificacion, 4=RESPUESTA en la base de datos
                                        $notificacion = Models\BlogPostModel::crearNotificacion(intval($user_notificado->user_id),$user->id, intval($id), 4);
                                    }

                                    $avatar = (!empty($addedComment->avatar)) ? base64_encode($addedComment->avatar) : "iVBORw0KGgoAAAANSUhEUgAAA4QAAAKBCAYAAAAP9GU8AAAhxUlEQVR42u3d22HjOLYF0MrAITAD
                                    haAMGAIzYAYKgRkwBGagEJiBQlAG/VnTu2Y87a7yQ7b1IID1sX7vrfbYwNkEcM6Pv/766ycAAADt
                                    +eGHAAAAIBACAAAgEAIAACAQAgAAIBACAAAgEAIAACAQAgAAIBACAAAgEAIAACAQAgAAIBACAAAg
                                    EAIAACAQAgAAIBACAAAgEAIAACAQAgAAIBACAAAgEAIAACAQAgAAIBACAAAgEAIAACAQAgAAIBAC
                                    AAAgEAIAAAiEAAAACIQAAAAIhAAAAAiEAAAACIQAAAAIhAAAAAiEAAAACIQAAAAIhAAAAAiEAAAA
                                    CIQAAAAIhAAAAAiEAAAACIQAAAAIhAAAAAiEAAAACIQAtOx4PP4yz/PPw+HwyzAMP/f7/f89PT39
                                    /PHjx1W8/L8bz/8/p2n6/7/lfD773wYAgRAAvut0Ov0KWc/BKyFst9tdLeDdUtd1v/69Caj5ty/L
                                    8nNdV/+7AiAQAsBLOVV7Dn4JUKWEvu+GRUERAIEQgGbD37WvdJYuP49xHH+FxJyO+n0BQCAEoPhr
                                    n3nj18LJ37UlLPd9/+uNolNEAARCAIo4AXwOgLkaKdjdJiA6QQRAIARgE3J6lSugTgDv/xYxwTtX
                                    TP0eAiAQAnA3CSEJI94AbkdOD3M6a/QFAAIhAEJg4w1qcrVUOARAIARACHRyKBwCIBACcNmbwIw/
                                    EALr480hAAIhAH947g6qMUw7DWnSCEi3UgAEQoDGTwNdCXWl1KkhgEDoBwHQkJwGpvGIQMTvp4be
                                    GgIIhABUei00Bb+B8bwnp8U5NXadFEAgBKACKew1ieGr10mPx6O/IwCBEIASg2BOegQbrjHXUDAE
                                    EAgBEAQRDP2dAQiEAAiCCIb+7gAEQgAEQQRDAARCAO7nuWuoZjFsofmMrqQAAiEAdzJNkyDI5qSb
                                    rTmGAAIhADeS63nmCLL1OYb5YOHvFUAgBOCK7wTzXkvgoBT5cOF9IYBACMA35Z2ggIH3hQAIhACu
                                    h4JrpAAIhAC1SlMOYySo0W63+7muq79zAIEQgNcsy6J7KNXLNWh/7wACIQAvTgXz1kpYwGkhAAIh
                                    gFNBcFoIgEAI4K0gOC0EQCAEqIYOoqATKYBACNAgcwXh/bmFOT23VgAIhADVXRHd7/eKfvhATs9d
                                    IQUQCAGquiKqcQx8jiukAAIhgCui4AqptQRAIAQo74qo2YKgCymAQAjQmBSvKWIV83C9LqSZ2Wl9
                                    ARAIATbNoHkwyB5AIARoUJpgKNrhtoZh8K4QQCAE2JYUqYp1uN+7QqEQQCAE2ETzGPMFwbxCAIEQ
                                    oMEwqHkMPLbZjFAIIBACPKSTaE4oFOXwePM8W5cABEKA+4VBnURBKAQQCAGEQWAj0unXOgUgEALc
                                    RE4gFN2w/bEU1isAgRBAGAShEACBEEAYBKEQAIEQQBgEoRAAgRBAGAShEEAg9EMAEAZBKAQQCAEQ
                                    BkEoBBAIAZp0PB4Vz1CpcRytcwACIcDrDJ2H+uUGgPUOQCAEEAZBKAQQCP0QgNadz2dhEBqzLIv1
                                    D0AgBITB88/dbqdAhsbkI1BuBlgHAYHQDwFomDAIbYfC0+lkLQQEQoAWpQ29ohjalo9CuSlgTQQE
                                    QoCGTNOkGAZ+2e/31kVAIARohcHzgMH1AAIh0CDjJQDjKAAEQqDRjqJd1yl8gTfpPAoIhACVyjsh
                                    BS/wUedRTWYAgRCgMuM4KnaBizuPWjcBgRCgEsuyKHKBT8lHJOsnIBACFC5DpzWRAb4iH5Oso4BA
                                    CFCwXP1S2AJffU+Yj0rWUkAgBPBuEPCeEEAgBCjB8XhUzALeEwIIhEBr0jLeu0HgmvKRyfoKCIQA
                                    Bej7XgELXFXXdeYTAgIhwNZN06R4BW4iH5uss4BACLBRRkwARlEACIRAo/b7vYIVuPkoCldHAYEQ
                                    wFVRwNVRAIEQwFVRwNVRAIEQwFVRwNVRAIEQ4D7ylV5xChhYDyAQAo0xgB4wsB5AIAQala/zClLg
                                    kXa7nfUYEAgB7i1f5RWjwBYcDgfrMiAQAmgkA7TaYCbdjq3NgEAIcAfzPCtCgU0ZhsH6DAiEABrJ
                                    ABrMAAiEADeRtzoKT2CLcpXdOg0IhAA3kjc6ik5gy3Kl3XoNCIQAN5A3OgpOYMu6rrNeAwIhwLWt
                                    66rYBIowTZN1GxAIAa7JmAmgpDEUaYBl7QYEQoArMIQeMKweQCAEnA4COCUEEAgBp4MATgkBBELA
                                    6SCAU0IAgRBwOgjglBBAIAScDgI4JQQQCAGngwBOCQEEQqAYfd8rJIFqTgmt64BACHCh0+mkiASq
                                    Ms+z9R0QCAEuMQyDAhKoStd11ndAIAT4SJovKB6BGi3LYp0HBEKA96T5gsIRqFE6J1vnAYEQ4B25
                                    VqVwBGqVN9LWekAgBHhFmi4oGIGa5Y209R4QCAFeYRA9YFA9gEAINMioCcAICgCBEGjUOI4KRaAJ
                                    u93Oug8IhAAv5RqVQhFoxbqu1n5AIASIzOZSIAKaywAIhECD+r5XIALNNZex/gMCIdC8dNtTHAIt
                                    yu0I+wAgEAJNm6ZJYQg0Kbcj7AOAQAg0Ld32FIZAq8wkBARCoFlmDwJmEppJCAiEgOuiAK6NAgiE
                                    gOuiAK6NAgiEgOuiAK6NAgiEgOuiAK6NAgiEgOuiAK6NAgiEQKkMowcwpB4QCIFG5b2MAhDgH8Mw
                                    2B8AgRBoQ97LKAAB/vH09GR/AARCoA0pfBSAAP+2rqs9AhAIgbodj0eFH8ArDoeDfQIQCIG6peBR
                                    +AH8ab/f2ycAgRAwbgLA+AkAgRAwbgLA+AkAgRCoQQodBR/A28ZxtF8AAiFQpxQ6Cj6At+Vavf0C
                                    EAiBKqVhgoIP4H32C0AgBOpccBR6AB/KeB57BiAQAlUxfxDAPEJAIAQaZf4ggHmEgEAINKrve4Ue
                                    wAWenp7sG4BACNSl6zqFHsCFTqeTvQMQCIE6GEgPYEA9IBACGsoAoLEMIBACGsoAoLEMIBACGsoA
                                    oLEMIBACddrtdgo8gE/K+2t7CCAQAuUvNAo7gE/L+2t7CCAQAkVb11VhB/AF0zTZRwCBEChbWqcr
                                    7AA+bxxH+wggEAI6jALoNAogEAIFGoZBYQfwBV3X2UcAgRAoW75wK+wAvsY+AgiEQNEyS0tRB/A1
                                    acxlLwEEQsDICQCjJwAEQsDICQCjJwAEQmDj8mVbQQfwdenUbD8BBEKgSPM8K+gAviGdmu0ngEAI
                                    FMkMQgCzCAGBEBAIAfiC3W5nPwEEQqBMZhACmEUICISAQAiAQAgIhEBLctVJMQfwPefz2Z4CCIRA
                                    gQuMQg7AcHpAIAQEQgAEQkAgBARCAARCQCAEapY3Lwo5gO9blsW+AgiEQFnyRVshB/B9melqXwEE
                                    QkAgBBAIAQRCQCAEEAgBBEJAIAQQCAEEQkAgBBAIAQRCQCAEEAgBBEJAIAQQCAEEQkAgBBAIAQRC
                                    QCAEEAgBBEJAIAQQCAGBEEAgBBAIAYEQQCAEEAgBgRBAIAQQCAGBEEAgBBAIAYEQQCAEEAgBgRBA
                                    IAQQCAGBEEAgBBAIAYEQEAgVcgACISAQAs0uMAo5gG/LBzZ7CiAQAgIhgEAIIBACAiGAQAggEAIC
                                    IYBACCAQAluz3+8VcwDfZD8BBEJAIAQQCAEEQqAcfd8r5gAEQkAgBFqU2VmKOYCvy00L+wkgEAIC
                                    IYBACCAQAuVYlkVBB/ANwzDYTwCBEChTWqUr6AC+Ljct7CeAQAgU6XQ6KegAvmGeZ/sJIBACBS8y
                                    CjoAQ+kBgRBoU9d1ijqAL8pNC3sJIBACxTKcHsAMQkAgBBo1jqOiDuALdrudfQQQCIGymUUIYAYh
                                    IBACRk8AYOQEIBACRk8AYOQEIBACRk8AYOQEIBACOo0CoMMoIBAClRmGQXEH8AmZ4Wr/AARCoArT
                                    NCnwAD6h73v7ByAQAjqNAugwCiAQAhrLAGgoAyAQAiXa7XaKPIALnc9newcgEAIaywBoKAMgEAIa
                                    ywBoKAMgEAIlWtdVoQdwgXxAs28AAiFQnaenJ8UewAfyAc2eAQiEQHVyDUqxB/A++wUgEAJVylwt
                                    xR7A2/b7vf0CEAiBOhlQD2AgPSAQAt4RAuD9ICAQAt4RAhD5YGafAARCoGrmEQKYPwgIhECjTqeT
                                    wg/A/EFAIARa1XWd4g/gN/lgZo8ABEKgeuM4Kv4AXsiHMvsDIBACTViWRQEI8EI+lNkfAIEQMH4C
                                    oEGZ02pvAARCwPgJAOMmAARCoG7zPCsEAf42DIN9ARAIgbacz2eFIMDf8q7avgAIhIBrowCuiwII
                                    hIBrowCuiwIIhIBrowCuiwIIhIBrowCuiwIIhEA1DKkHDKMHEAiBhhlSD7RoXVd7ACAQAqSpguIQ
                                    aEnXddZ/QCAEiHwlVyACLZmmyfoPCIQAz3a7nSIRaEa6LFv7AYEQ4H/MJATMHgQQCIFG5Wu55jJA
                                    C47Ho3UfEAgBNJcBNJMBEAgBNJcBNJMBEAiB1u33e0UjUKVci9dMBhAIATSXATSTARAIAV6TNzaK
                                    R6A2p9PJGg8IhAAfyRsbxSNQk77vre+AQAhwCSMoAKMmAARCoGGHw0ERCVQhzbKs64BACPDJU0KF
                                    JFCDZVms64BACPBZBtUDBtEDCIRAo9KRT0EJlCyjdKzngEAI4JQQcDoIIBACOCUEnA4CCIQATgkB
                                    p4MAAiHA+6eE5hICOosCCIRAo8wlBMwdBBAIgUZlLqFTQqAEx+PRug0IhABOCQGngwACIcDVpFGD
                                    ohPYqrx5tlYDAiHAjaRRg6IT2KJxHK3TgEAIcGu5kqX4BLYkb5zz1tkaDQiEADe2rqsCFNiUaZqs
                                    z4BACHAvuZqlCAW2YLfbWZcBgRDgnoyhAIyZABAIgYbN86wYBR5qGAbrMSAQAmgwA2gkAyAQAtxV
                                    Zn4pTIFHyC0F6zAgEAI82OFwUJwCd5XbCdZfQCAE2Ih0+VOkAve6KprbCdZeQCAE2AizCQEzBwEE
                                    QsDVUQBXRQEEQsDVUQBXRQEEQsDVUQBXRQEEQqB+KdoUr8A19X1vfQUEQoBSGFgPGEAPIBACjUrx
                                    liJOMQt81/F4tK4CAiFAaZZlUcwC3zKOo/UUEAgBSpViTlELfEW6FltHAYEQoHBGUQBGTAAIhID3
                                    hADeDQIIhEBrUtwpcoFLHA4H6yYgEALUxnxCwLxBAIEQaNgwDIpe4M0mMuYNAgIhQOXvCTWZAV5r
                                    IrOuq3USEAgBapfOgZrMAC9lbqn1ERAIARqRkwChEIi8L7YuAgIhQGPmeVYMQ+Pyrth6CAiEAEIh
                                    oKMogEAIoPMooKMogEAIIBQCwiCAQAggFALl67pOGAQQCAH+ZEYhmDUIIBACCIWKZxAGAQRCAKEQ
                                    EAYBBEIAoRAQBgEEQgChEBAGAQRCAKEQEAYBBEIAoRAQBgEEQgChEBAGAQRCAKEQEAYBBEKASgzD
                                    oPiGjcnHmny0sUYBCIQAQiEIgwAIhAC3M02TYhweLB9nhEEAgRDgIeZ5VpTDA8OgdQhAIAR4qOPx
                                    +KuZhQId7icn9NYfAIEQYBPS2bDrOoU63KGTaE7mrTsAAiHAphhLAcZKAAiEADqQKt5BJ1EAgRBA
                                    sxlA8xgAgRCgyXeFms3A93gvCCAQAhT9rnC/3yvs4ZPSpMl7QQCBEKAK4zgq8uFC+YjivSCAQAhQ
                                    lWVZXCGFDxwOB+sFgEAI4AopuCIKgEAI8MnAlROGXNHc8r8z/0YhAP6r7/tNXxFNY5t0Oj2dTtZZ
                                    QCAE2HLIenklc+ut6nMaYpA9rQ+az1XqLf+d/j5CJh+bvG8EBEKAjRVsuW5W4vyyFJYaztBq45it
                                    n7i9NU80QTYfoARDQCAEeKDj8XjRe7wShlrnv+WtUAu1nQpO01TEh6ZL3j2akwgIhAB3llOFvDn6
                                    TBFaQih0WohTwXLC4Eu5+p2POtZnQCAEuEPDmK8WoynaSrji5W0h3go+Tj4efac5jsYzgEAIcKMv
                                    9teY4VdKKHytSQ6UKAGrlL+574TB32cpel8ICIQAd3wn+NlQWMq8s69cj4UtKOkaZcLbtf/O8r6w
                                    lFNRQCD0gwA2WaBd62v9W1fYShqCrekMmsbcbq255RXtUt5NAgIhQHXXQ2t61+QaKa6Hlv1e1zVS
                                    QCAEuKA4u/b10EuU1jb+1qen8JVTsJJO3J/Xm3t/XHGNFBAIAW7QPfRaJxul/dy8L8Q7wa/fQnjk
                                    z003UkAgBNjg27iccpR4pesWjXeg1oHsW5n1WdpbS0AgBGji2mNJHUgFQwTBx3YSbfW6LSAQAnxL
                                    3tBsuTFKic1mBEMEwW00j/lO0xn7AyAQAtWfCpb05q30Ak0wpPUgWMIHqFpuKAACIUA1RdnvzR9K
                                    bxWfYKgrKZ+9xlh6EIxHN6tyWggIhIBTwcJOBd86Janhq326GiYYmmPIex9ASuwa+tq6U/rpuNNC
                                    QCAEquggWlP4qKUjYIrl/Ldspbsrj38zm86btYxBqG3dcVoICIRAkYFjK63dXSH9+CqvWYbtzhDM
                                    tdCafp9LvSJ6yRVecwsBgRAopptf7SdPtVwh/f06aYppp4b1nwbm2nBtv781XBG95H+7Gt51AgIh
                                    ULFav863dpXruQmNt4Z1nWzXGiZKbVjllgIgEAK+zldy7a7mq1wJEa6Ulvu7mbeitYaHmq+mX3JL
                                    oYbmP4BACPg6X81VrloazrxXfAuH5YTA2t+bJQy53qzhDCAQAg/W6tf51hs/JBzmQ4Brpdu5QthC
                                    CGz9VPC9dccVUkAgBO7egCQnEYqxNk8LXzutSZHud+J+1wUTxhPKWwoCTgXfX3dcIQUEQsAVUaeF
                                    m7lamsCieL9esd/SKaBTQVdIAYEQcEW0mkK+9QItASYB0Qni508AEwBrGw/xlQ9QPiy4QgoIhMAG
                                    vtAr5r/X6MN1rn9f/UtQzqlX68V+PhqkgM/PI+HH8PF/PiRoYPS936vWPyYAAiFwxeLdFdHryKmP
                                    L/dv/57lRCwniQlINf7O5b8rvwMJf/nvFf5el98Da851tPaeGRAIgRsUZooqTWceHRRzcpYQlTCV
                                    ULXV0+qcdubfl5Ot/HtzVVbw0zTGhyhAIASKvCKaIkIx5RppCSHiWULYs+fw+LuPAsfzFc7fPYe8
                                    Z89hLxTbroeWsN74MAEIhMDFxZn3gvedIadQo9UPTwnX1gGjKQCBENiINCHwducx8nbOSROtyAmr
                                    tca7QkAgBDZWoCmWtjGmQjCk5nXGO8FtvCv0+wgIhMD/eS+4vWCYwtnvJjW99cxbTH/f23pX6OMT
                                    IBCCNzyaOWx8QLlgiCDILdcY8woBgRA0j0EwBEFQsxlAIAQ0j6GEYOiaF4Ig1+ajEwiEgO5+aD4D
                                    msU03u3Y7zMIhEDlRZuip65gmALOHEMe9QY5IwwEQR1IAYEQKECCg2Kn7iLOOyDu9f4464mbBjqQ
                                    AgIhUAhjJdoq5Lwz5FbvA3UlFgoBgRAo7EqXBg/tXifNhwDXSXEtlO+sI8ZSgEAIFFzIGSuBU0O+
                                    YlkWNwsQCkEgBIRBajw19NaQ994GOg3ErEIQCIGCmTHIpTMNU/z78i8E5kqoD0iYVQgCISAMIhz6
                                    O2rkBkEKe++LEQoBgRCEQRAOGzoJFAIRCgGBEIRBuDgc5s1hGoz4OytzXTgcDq6DIhQCAiHULA/9
                                    hUHuITPocsrk9HDbV0ET4q0J3EN+1/ztgUAIPFCKP0UJjzw9zO+gWYePC4A5vc0VX6eACIWAQAjC
                                    IDw8IDpBvO07QAEQoRAQCAFhkCLmlqWBSd6wJcQ4Rfz86V+ug+fnl6u6ZgMiFAICISAMUk1IzO+x
                                    Qdf/PvkT/hAKAYEQEAZp8rrpc1DMldMExdpOFJ9P/F4GPyMgEAoBgRAQBuGCsJgi82Vg3FJofA57
                                    kb/T/Dvzzi//bqEPoRAQCIFvM2cQLguOz54D5FteBsuXJ3dveRnwQlMXeF/+ZuzfIBACwiAAhtcD
                                    AiEgDAIgFAICISAMAiAUAgIhIAwCIBQCAiHwomOhhhUA1CYfO+3zIBACwiAADcrNF6EQBEJAGARA
                                    KAQEQuClzE1TLADQwtzQfAS194NACAiDADQoN2KEQhAIgb9N06Q4AKA5+/1eHQACIbQtbbgVBQC0
                                    Kjdk1AMgEEKzswYVAwC07nA4qAtAIASD5wHA4HpAIATjJQDA4HpAIIQaCYMAYEYhCIRgvAQAYBwF
                                    CIRgvAQAYBwFCIRQpWVZbPIAcKFxHNUPIBCCjqIAoPMoIBCCjqIAoPMoIBBCSfq+t6EDwDc6j2oy
                                    AwIhFOlwONjMAeAKnUfVFSAQgiYyANCojG1SX4BACJrIAIAmM4BACJrIAEBr7wk1mQGBEDYtV1ps
                                    2gBwG13XaTIDAiFs0zRNNmsAuLF08FZ3gEAIm3s3aJMGgPvIR1j1BwiEsJl3g7nCYoMGgPvxnhAE
                                    QjB8HgC8JwQEQvBuEAC8JwQEQjBvEAC8JwQEQjBvEADMJwQEQriqcRxtwgCwEflIqz4BgRDuYlkW
                                    my8AbEw+1qpTQCCEm18V9W4QALbpeDyqV0AgBCMmAKDV94RGUYBACEZMAIBRFIBACNdxOp1cFQWA
                                    QuS9v/oFBEK4mv1+b4MFgIKujuZjrhoGBEJwVRQAGpSPueoYEAjBVVEAaFQ+6qpnQCAEV0UBwNVR
                                    QCAEV0UBwNVRQCAEV0UBQNdREAj9EMBVUQCo++qogfUgEMJF5nm2eQJAZQysB4EQPpSvh66KAkCd
                                    jsejegcEQnhbvh7aMAGgTl3XuToKAiG8Ll8NbZYAULdxHNU9IBDCn1dF89XQRgkA9VvXVf0DAiH8
                                    43A42CABwGxCEAihxZmDNkcAaMs0TeogEAjBzEEAMJsQBEJo0rIsNkUAaNQwDOohBEI/BDSSsSEC
                                    gAYzIBCCRjIAgAYzIBBC/Y1k8nbARggAzPOsPkIghJb0fW8DBAB+yRMSDWYQCKERx+PR5gcA/Eue
                                    kqiTEAihAbvdzsYHAPwxhiJPStRKCIRQsbwRsOkBAMZQgECIMRMAAP+SpyXqJgRCMGYCADCGAgRC
                                    qOV00JgJAOASy7KonxAIoSbjONrgAICLx1ConxAIoaIh9DY3AOAzDKtHIIRKpGOYjQ0AcEoIAiFO
                                    BwEALmJYPQIhFC6dwmxoAMBXpCFdGtOpqRAIoUCZI2QzAwCcEoJAiNNBAACnhCAQ4nQQAMApIQiE
                                    OB0EAHBKiEAITgcBAJwSIhCC00EAAKeECITgdBAAwCkhAiE4HQQAnBI6JUQghG1a19VmBQA4JQSB
                                    kBYNw2CjAgBufkqo7kIghI05nU42KQDgLuZ5Vn8hEILTQQCgRV3Xqb8QCMHpIADglBAEQnioPO62
                                    MQEA97Tb7dRhCITwaGn9nMfdNiYA4N4y/1g9hkAID5TrGjYkAOARMv9YPYZACA+UR902JADgUdLL
                                    QE2GQAgPsCyLjQgAeKh0OleXIRDCA+Saho0IAHik9DJITwO1GQIh3NG6rjYhAGATpmlSnyEQgkH0
                                    AECLDKpHIIQ7j5qw+QAAW5LeBuo0BEK4g1zLsPEAAEZQgECIURMAAEZQgEBIC47How0HANikcRzV
                                    awiEcEt939twAIDNjqBQryEQwo3kGobNBgDYsnme1W0IhHALh8PBRgMAaC4DAiGayQAAaC4DAiGa
                                    yQAAaC4DAiH1GobBBgMAaC4DAiGtOZ/PNhcAoCjLsqjjEAjhGtKty8YCAJQko7LUcQiEcAW73c7G
                                    AgAUJ7ec1HIIhGD2IADQoGma1HMIhPAd6dJlQwEASpRbTuo5BEIwexAAaJSZhAiE8EXrutpIAICi
                                    mUmIQAhmDwIAjcptJ3UdAiF8QYa62kgAgNLl1pPaDoEQPiHDXG0gAIBroyAQ4rooAIBroyAQ4roo
                                    AIBroyAQ4rooAIBroyAQ4rooAIBroyAQ4rooAIBroyAQ4rooAIBroyAQUowslDYMAMC1URAIaVAW
                                    ShsGAODaKAiENCYLpI0CAKjZNE3qPgRCeM3hcLBRAABV2+126j4EQnhNFkgbBQBQu9PppPZDIISX
                                    sjDaIACAFszzrP5DIISXsjDaIACAFvR9r/5DIISXsjDaIACAFjw9Pan/EAjhpSyMNggAoBXH41EN
                                    iEAIkQXRxgAAtGQcR3UgAiFEFkQbAwBg/AQIhBg3AQBg/AQIhLTgfD7bEAAA4ydAIMS4CQCAdgzD
                                    oB5EIKRtWQhtCABAi7quUw8iENK2LIQ2BACgVeu6qgkRCGlTHlLbCACAlk3TpC5EIMT7QQCAFvV9
                                    ry5EIMT7QQCAFj09PakLEQjxfhAAwDtCEAjxfhAAwDtCEAjxfhAAoH7mESIQ0pxxHG0AAAA/zCNE
                                    IKRBu93OBgAA8D95TqNGRCCkCefz2cIPAPDCsizqRARC2nA8Hi38AAAv5DmNOhGBkCYcDgcLPwDA
                                    C/v9Xp2IQEgbsuBZ+AEA/k2diEBIE56eniz6AAC/ybMatSICIQbSAwA0yIB6BEKqlw5aFnwAgD8Z
                                    UI9AiIYyAACNypxm9SICIRrKAABoLAMCIRrKAABoLAMCIRrKAABoLAMCIRrKAABoLAMCIRrKAABU
                                    JP0W1I0IhFSp73sLPQCAxjIIhLQorZQt8gAA71vXVe2IQEiFv2wWeACAD6XvgtoRgZCqpIWyBR4A
                                    4GPpu6B+RCCkKvM8W+ABAC6QvgvqRwRCdBgFAGhQ+i6oHxEI0WEUAECnURAI0WEUAKAlp9NJDYlA
                                    iA6jAAAtSkM+NSQCIVXIFy4LOwCATqMIhBg5AQDAB8ZxVEciEKLDKABAi/b7vToSgRCBEACgRV3X
                                    qSMRCKlDvnBZ2AEAPkcdiUCIkRMAAI1a11UtiUCIkRMAAC0yegKBEIEQAKBRy7KoJREIEQgBAFpk
                                    FiECIQIhAIBACAIhAiEAgDeEIBAiEAIACIQgEGLsBABAnU6nk1oSgZDyDcNgUQcA+ISnpyd1JAIh
                                    dZjn2cIOAPAJ+aCujkQgpArn89nCDgDwCfmgro5EIKQafd9b3AEALrwumg/qakgEQqqRLlkWeACA
                                    j43jqH5EIKQ++/3eIg8A8MHpoO6iCIQ4JQQAaNDhcFA3IhBSr1yBsNgDAPwps5vViwiEVN9x1KB6
                                    AIA/r4qu66peRCCkfrkXn0XP4g8A8F/LsqgTEQhpR76ACYUAAGYOIhAiFAIACIMgEOJNIQCAN4Mg
                                    ENKUtFi2OQAALej7/tdHcTUgAiH81mzG8HoAoFZd1/2ay6zuQyCEDwbY58uZjQMAqEGex3griEAI
                                    XzgxnKbJqSEAUGQIHMfRO0EEQrjmyWECYt4bJiQCAGxFwl9qlNQr3gciEAIAACAQAgAAIBACAAAg
                                    EAIAACAQAgAAIBACAAAgEAIAACAQAgAAIBACAAAgEAIAACAQAgAAIBACAAAgEAIAACAQAgAAIBAC
                                    AAAgEAIAAAiEfhAAAAACIQAAAAIhAAAAAiEAAAACIQAAAAIhAAAAAiEAAAACIQAAAAIhAAAAAiEA
                                    AAACIQAAAAIhAAAAAiEAAAACIQAAAAIhAAAAAiEAAAACIQAAAAIhAAAAAiEAAAACIQAAAAIhAAAA
                                    AiEAAAACIQAAAAIhAABAq/4Drz7zxD9utVEAAAAASUVORK5CYII=";

                                    $response = [
                                        "status" => true,
                                        "answer" => "
                                            <div class='card ml-5 my-4'>
                                            <div class='card-header'>
                                            <img class='rounded-circle' style='width:70px;heigth:70px;' src='data:image/jpg;charset=utf8;base64,$avatar'/> 
                                            <div style='margin-top:-7%; margin-left:12%'>
                                            <h4 style='text-align:top;'>". User::getUsernameById(intval($addedComment->user_id)) ."</h4>
                                                <!-- ELIMINAR -->
                                                <button
                                                    id=$insertId
                                                    name='eliminar'
                                                    class='btn btn-outline-danger btn-sm float-right'
                                                    data-toggle='tooltip' 
                                                    data-placement='top'
                                                    title='Eliminar'>
                                                    <i class='fas fa-trash-alt'></i>
                                                </button>
                                            </div>
                                            </div>
                                            <div class='card-body'>
                                            <p>$addedComment->text</p>
                                            </div>
                                            <blockquote class='blockquote text-right'>
                                            <footer class='blockquote-footer mr-3'>$addedComment->date</footer>
                                            </blockquote>
                                        </div>"
                                    ];
                                    echo json_encode($response);
                                }
                                //? No se pudo añadir
                                else {
                                    echo json_encode(["error" => "No se pudo responder al comentario. Intenalo de nuevo."]);
                                }
                            }
                            //? El comentario padre no existe
                            else {
                                echo json_encode(["error" => "El comentario al que intentas responder no existe :("]);
                            }
                        }
                    }
                    //? el post a comentar no existe
                    else {
                        echo json_encode(["error" => "El post no se pudo encontrar"]);
                    }
                }
                //? ID no enviado en formulario
                else {
                    echo json_encode(["error" => "El post no se pudo encontrar"]);
                }
            }
            else {
                echo json_encode(["error" => "Uno o mas datos estan vacios"]);
            }
        }
        //? Usuario no logueado
        else {
            echo json_encode(["error" => "Tienes que estar logueado para comentar un post"]);
        }
    }

    protected function deleteComment()
    {
        if ($_POST && !empty($_POST['id']))
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
                                echo json_encode(["status" => true]);
                            }
                            //? Error eliminar
                            else {
                                echo json_encode([ "error" => "No se pudo eliminar el comentario :("]);
                            }
                        }
                        else {
                            echo json_encode([ "error" => "No estas autorizado para borrar este post :("]);
                        }
                    }
                    //? El post no existe o no esta visible
                    else {
                        echo json_encode([ "error" => "El post no esta disponible."]);
                    }
                }
                //? el comentario no existe
                else {
                    echo json_encode([ "error" => "El comentario no se pudo borrar"]);
                }
            }
            //? Usuario no logueado
            else {
                echo json_encode(["error" => "Tienes que estar logueado para eliminar un comentario"]);
            }
        }
        //? No vienes por POST
        else {
            echo json_encode(["error" => "Uno o mas datos estan vacios"]);
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
                        ,"js" => array("addFavFeed.js")
                        ,"list" => $feed
                        ,"categorias" => $categorias
                        ,"page" => __DIR__ . '/../Views/BlogPost/List.php'
                    ]); 
                }
                else
                {
                    parent::sendToView([
                        "titulo" => "LIST"
                        ,"list" => $feed
                        ,"js" => array("addFavFeed.js")
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
                    ,"js" => array("addFavFeed.js")
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
                    ,"js" => array("addFavFeed.js")
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
                    ,"js" => array("addFavFeed.js")
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
        if ($_POST && !empty($_POST['type']) && !empty($_POST['id']))
        {
            $user = User::getUser();

            if(!empty($user))
            {
                $response = array();

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

                            echo json_encode(["status" => $deleted]);
                        }
                        //? AÑADIR A FAVORITOS
                        else
                        {
                            $added = Models\BlogPostModel::addToFavorites(intval($post_id), $user->id);

                            if ($added)
                            {
                                $user_notificado = Models\BlogPostModel::getUserByPostID(intval($post_id));

                                //? Si el usuario que crea la notificacion no es el mismo que las recibe
                                if(!empty($user_notificado ) && intval($user_notificado->id)!=intval($user->id))
                                {
                                    //? El 1 representa el tipo de notificacion, 1=LIKE en la base de datos
                                    $notificacion = Models\BlogPostModel::crearNotificacion(intval($user_notificado->id),$user->id, intval($post_id), 1);
                                }

                                echo json_encode(["status" => true]);
                            }
                            else {
                                echo json_encode(["error" => "No se pudo añadir a favoritos"]);
                            }
                        }
                    }
                    else if($type == "feed")
                    {
                        //? borrar del feed
                        if (Models\BlogPostModel::isInFeed(intval($post_id), intval($user->id)))
                        {
                            $deleted = Models\BlogPostModel::deleteFromFeed(intval($post_id), intval($user->id));

                            echo json_encode(["status" => $deleted]);
                        }
                        //? AÑADIR Al FEED
                        else
                        {
                            $added = Models\BlogPostModel::addToFeed(intval($post_id), $user->id);
                        
                            $user_notificado = Models\BlogPostModel::getUserByPostID(intval($post_id));

                            //? Si el usuario que crea la notificacion no es el mismo que las recibe
                            if(!empty($user_notificado ) && intval($user_notificado->id)!=intval($user->id))
                            {
                                //? El 1 representa el tipo de notificacion, 2=RETWEET en la base de datos
                                $notificacion = Models\BlogPostModel::crearNotificacion(intval($user_notificado->id),$user->id, intval($post_id), 2);
                            }

                            echo json_encode(["status" => $added]);
                        }
                    }
                }
                //? No hay post
                else {
                    echo json_encode(["error" => "El post que intentas añadir no existe."]);
                }
            }
            //? No estas logueado
            else {
                echo json_encode(["error" => "Debes estar logueado para añadir a favoritos o a tu feed."]);
            }  
        }
    }

}