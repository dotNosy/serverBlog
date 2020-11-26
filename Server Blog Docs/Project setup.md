# Project setup

Lo primero sera clonar el repositorio a nuestro host virtual.

Una vez clonado, creamos la carpeta assets al mismo nivel que serverBlog.

Quedando un directorio como este

/Host virtual

/serverBlog

/assets

Una vez nuestro directorio esta listo le daremos permisos 775.

Estando en la terminal en /serverBlog lanzaremos el comando composer update que nos creara la auotacarga de clases.

Lo siguiente sera actualizar las propiedades de conexion en el fichero Src/Services/DataBaseConfig.php. Modifica las propiedades existentes o crea la tuya y no olvides hacerle referencia en Src/Services/Helpers.php en la funcion getEnviroment.

Si hemos hecho todo correctamente, al entrar a nuestro host se nos abrira el login (pagina de inicio).