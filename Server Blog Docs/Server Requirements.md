# Server Requirements

Requerimientos para desplegar serverBlog en un servidor

- Windows:
    - Xampp
- Linux:
    - PHP 7.4.2
    - Apache 2
    - Mysql
    - Composer
    - Git

## PHP y modulos

```bash
	sudo apt install php php-common php-opcache php-mcrypt php-cli php-gd php-curl php-mysql
```

Instalar composer

[Cómo instalar y utilizar Composer en Ubuntu 20.04 | DigitalOcean](https://www.digitalocean.com/community/tutorials/how-to-install-and-use-composer-on-ubuntu-20-04-es)

## Apache configuration

```bash
sudo apt install libapache2-mod
```

Configurar un host virtual

[How To Set Up Apache Virtual Hosts on Ubuntu 20.04](https://linuxize.com/post/how-to-set-up-apache-virtual-hosts-on-ubuntu-20-04/)

Configuracion adicional de apache

En el fichero php.ini tendremos que

- Activar la extension pdo y mysql-pdo
- Mostrar errores = true
- Aumentar el post size para poder subir imagenes HD
- Establecer /tmp como carpeta temporal (Cambiar propietario al del server y chmod 777)

Habilitar fichero .htaccess

[How to Fix HTTP error code "500 internal server error" - 1&1 Hosting (US)](https://www.ionos.com/community/server-cloud-infrastructure/apache/how-to-fix-http-error-code-500-internal-server-error/)

Habilitar reescritura .htaccess

[.htaccess: Invalid command 'RewriteEngine', perhaps misspelled or defined by a module not included in the server configuration](https://stackoverflow.com/questions/10144634/htaccess-invalid-command-rewriteengine-perhaps-misspelled-or-defined-by-a-m)

## Mysql

Installation

[Cómo instalar MySQL en Ubuntu 20.04 | DigitalOcean](https://www.digitalocean.com/community/tutorials/how-to-install-mysql-on-ubuntu-20-04-es)

Habilitar conexiones remotas

[How to Allow Remote Connections to MySQL Database Server](https://linuxize.com/post/mysql-remote-access/)