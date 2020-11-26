# Services

## DataBaseConfig

Clase con las credenciales y propiedades de conexion a la base de datos.

## Connection

Clase que abre la conexion con la base de datos, en base a la configuracion de la clase mencionada anteriormente.

## Helpers

Esta clase contiene métodos que se usan en mas de un controlador o métodos que no deberían estar en un controlador.

SendTo404 → Llama a la vista 404

SendToController → Nos envía a otro controlador pudiendo pasarle opcionalmente parámetros a este.

FilesToArray → Agrupa los ficheros y en un array asociativo

GetFilesContent → Devuelve un array asociativo con el nombre del fichero y el contenido en binario.

CleanInput → Devuelve el contenido de un input eliminando caracteres maliciosos.

GetEnviroment → devuelve el entorno de base de datos (DataBaseConfig) que estamos usando.