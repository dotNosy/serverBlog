# Estructura y directorios

![Estructura%20y%20directorios%20aa4025e0638b4d60b2f3d5230319ac42/IMG_20201125_171036_287.jpg](Estructura%20y%20directorios%20aa4025e0638b4d60b2f3d5230319ac42/IMG_20201125_171036_287.jpg)

Cada carpeta del proyecto tiene una unica responsabilidad

Controllers

Contiene las clases controlador de la aplicacion. Cada controlador representa una seccion de nuestra web y maneja el flujo y validaciones de esa seccion.

Models

Representan las entidades de la base de datos (cada modelo es una tabla). Cada clase contiene metodos estaticos con las diferentes funcionalidades de cada modelo. Añadir, eliminar, obtener por id, actualizar, listar....

Views

Representan el html de un modelo o una seccion. Todas las vistas son añadidas a una vista padre que contiene el diseño principal de la web.

Public

Son los ficheros publicos del servidor, como el css, js o index.

Services

Son clases que no encajan en ninguno de los demas directorios, pueden ser clases de ayuda o clases necesarias en mas de una clase.

Resumen del flujo de la aplicacion

![Estructura%20y%20directorios%20aa4025e0638b4d60b2f3d5230319ac42/IMG_20201125_172531_377.jpg](Estructura%20y%20directorios%20aa4025e0638b4d60b2f3d5230319ac42/IMG_20201125_172531_377.jpg)