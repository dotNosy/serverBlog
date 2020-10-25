# serverBlog
Blog php para el reto

# Estructura de carpetas
    * SRC
        - Public:
            Carpeta de acceso publico de la aplicacion web. Contiene los html estaticos, js, css, imgs y el index.
         
        - Models:
            Clases para las tablas de bbdd. Representan un modelo de entidad y contiene los atributos y metodos necesarios para el modelo.

        - Views
            Carpetas con las vistas (html) / templates (html + php) de nuestra web. Son la representacion visual de los modelos y otros elementos de la pagina.

        - Controllers:
            Clases php (puras / vanilla) para controlar el flujo de la aplicacion web. Estas clases se encargan de llamar a clases de servicio, metodos de modelos u otras funciones (Logica de negocio) y redirigir a la vista pertinente con los datos necesarios.
        
        - Services:
            Clases php que contienen la logica de negocio o clases de ayuda que se usaran en mas de un archivo

