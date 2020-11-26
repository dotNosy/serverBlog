# Controllers y Router

## Router

La clase router se encarga de hacer las llamadas al controlador en base a la URL que se busca. La URL se divide en 3 partes principales (sin contar el dominio). 

> [www.serverBlog.com/Controller/Method/Param1/Param2/etc](http://www.serverblog.com/Controller/Method/Param1/Param2/etc)...

Cuando una petición es realizada, se parsea y divide la url. El primer parametro sera siempre un controlador. Si el controlador esta en nuestra whitelist (Controladores permitidos) creara una instancia de dicho controlador. Si el controlador no existe o no se encuentra habilitado nos enviara a la pagina 404.

## Parent Controller

Todos los controladores heredan de una clase padre. Esta clase contiene la estructura que deben compartir todos los controladores. El constructor es el encargado de hacer la llamada al correspondiente método. Si no hay método especificado se llamara siempre al método por defecto que es index. En caso de que el método especificado no exista nos enviara a la pagina 404.

Otra función que heredan todos los controladores es sendToView. Esta función es la encargada de llamar a la vista correspondiente y enviarle los parámetros. SendToView recoge dos tipos de parámetros, de controlador y URL. Los parámetros de controlador son aquellos enviados por el método  que realiza la llamada a la vista. Los parámetros de URL son parámetros enviados por una llamada desde otro controlador o función. Por ejemplo si intentamos acceder a un controlador que requiere estar logueado y no lo estamos, este hará una llamada al controlador de login pasándole un mensaje de error por parámetro de url.

## Login

La responsabilidad de este controlador es el flujo de sesión. Tiene los metodos y validaciones para el login, registro y logout.

## Profile

Contiene los metodos para mostrar/editar los datos personales de un usuario y la gestion de notificaciones.

## BlogPost

Dirige el flujo de los post de un blog. Contiene los metodos y validaciones para:

- Mostar todos los post
- Mostar mis post redactados
- Mostrar categorias
- Mostrar mi feed (Redactados mas pineados) y añadir posts a este
- Mostar y añadir favoritos
- Crear y editar un post.
- Añadir comentarios y respuestas a un post y eliminarlos.