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
        if(!empty($params[2]) && is_int($params[2]))
        {

            Helpers::sendToController("/post/all",
            [
                "error" => "No existe este post."
            ]);
        }
        else
        {
            $view = Models\BlogPostModel::view(intval($params[2]));
            if(!empty($view))
            {
                parent::sendToView([
                    "titulo" => "POST"
                    ,"css" => array("post.css")
                    ,"blogPost" => json_encode($view)
                    ,"autor" => User::getUsernameById(intval($view->user_id))
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
            Helpers::sendToController("/login/index"
            ,[
                "error" => "Para ver tus posts debes estar registrado."
             ]);
        }  
    }

    //! Todos los posts por orden cronologico
    protected function all(array $params)
    {
        //* Todos los posts, los nuevos primero
        $list = Models\BlogPostModel::all();
        
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
                ,"error" => "No se han publicado posts todavia."
                ,"page" => __DIR__ . '/../Views/BlogPost/List.php'
            ]); 
        }
    }

    //! Todos los posts de X autor(user)
    protected function author(array $params)
    {
        
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
    
                //? Si el radibutton no devulve 0 o 1
                if($radioPost!=0 && $radioPost!=1){
                    //TODO: Poner datos incorrectos en rojo
                }
                //? Error titulo o mensaje vacio
                else if (empty($tituloPost) || empty($mensajePost)) 
                {   
                    // TODO: Ponerle en rojo los datos que estan vacios
                }
                //? Creacion del post
                else
                {
                    //* Va al model para intentar crear el post
                    $id_post = Models\BlogPostModel::add($user->id,$tituloPost,$mensajePost,intval($radioPost));
                    
                    //* Si ha devuelto un id es que se ha creado, y por lo tanto te devuelve el view del post creado
                    if(!empty($id_post))
                    {
                        Helpers::sendToController("/post/view/$id_post");
                    }
                    else
                    {
                        //TODO: si no se ha podido crear el post
                    }
                    
    
                    //TODO: Enviar a la funcion view que te devuelve el post que se acaba de crear
                }
            }
            //? Si no se ha enviado el formulario de creacion de post te devuelve la vista para crearlo
            else 
            {
                parent::sendToView([
                    "titulo" => "ADD POST"
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
                        //* Se recogen las variables sin caracteres especiales
                        $id = Helpers::cleanInput($_POST['id']);
                        $titulo = Helpers::cleanInput($_POST['titulo']);
                        $mensaje = Helpers::cleanInput($_POST['mensaje']);
                        $visibleRadio = Helpers::cleanInput($_POST['visibleRadio']);

                        //? Se ha updateado correctamente
                        if(Models\BlogPostModel::edit(intval($view->id),$titulo,$mensaje,intval($visibleRadio)))
                        {
                           //* La llamada a la vista
                            Helpers::sendToController("/post/view/$view->id"); 
                        }
                        //? Ha fallado el update
                        else
                        {

                            //TODO: Error "LA SENTENCIA SQL HA FALLADO"

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
                            //* Se llama a la vista del edit
                            parent::sendToView([
                                "titulo" => "EDIT POST"
                                ,"css" => array("post.css")
                                ,"blogPost" => json_encode($view)
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
            Helpers::sendToController("/login/index"
            ,[
                "error" => "Para editar un post debes estar logeado."
             ]);
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

            if(!empty($user))
            {
                //* Me devuelve de la BD todos los registros del usuario del id
                $feed = Models\BlogPostModel::feed($user->username);
                
                if(!empty($feed))
                {   
                    parent::sendToView([
                        "titulo" => "FEED"
                        ,"list" => $feed
                        ,"page" => __DIR__ . '/../Views/BlogPost/List.php'
                    ]); 
                }
                else
                {
                    parent::sendToView([
                        "titulo" => "LIST"
                        ,"error" => "Tu feed está vacio."
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
                
            if(!empty($feed))
            {   
                parent::sendToView([
                    "titulo" => "FEED"
                    ,"list" => $feed
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
            
            if(!empty($favs))
            {   
                parent::sendToView([
                    "titulo" => "FEED"
                    ,"list" => $favs
                    ,"page" => __DIR__ . '/../Views/BlogPost/List.php'
                ]); 
            }
            else
            {
                parent::sendToView([
                    "titulo" => "LIST"
                    ,"error" => "No tienes ningun post favorito."
                    ,"page" => __DIR__ . '/../Views/BlogPost/List.php'
                ]); 
            }
        }
        //? No estas logueado
        else
        {
            Helpers::sendToController("/login",
            [
                "error" => "Tienes que estar logueado para ver tus psots favoritos."
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