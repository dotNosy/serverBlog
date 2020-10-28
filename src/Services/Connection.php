<?php
declare(strict_types=1);

namespace ServerBlog\Services;
use \PDO as PDO;

class Connection 
{
  private DatabaseConfig $config;
  private PDO $conn;

  public function __construct($configMode) 
  {
    try 
    {
      $config = new DataBaseConfig($configMode);

      $this->conn = new PDO("mysql:host=$config->servername;dbname=$config->dbName", $config->username, $config->password);
      
      //propiedades / config de la conexion
      $this->conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    }
    catch(PDOException $e) 
    {
      //TODO: manejar excepcion o reenviar a pagina de error
      echo "Connection failed: " . $e->getMessage();
      die();
    }
    catch(Exception $e)
    {
      echo $e->getMessage();
      die();
    }
  }

  public function getConnection()
  {
    return $this->conn;
  }
}
 