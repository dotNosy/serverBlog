<?php
declare(strict_types=1);

namespace ServerBlog\Services;

class DatabaseConfig 
{
    public $servername;
    public $dbName;
    public $username;
    public $password;

    public function __construct(string $app_status) 
    {
        switch ($app_status) 
        {
            case "dev":
                $this->servername = "localhost";
                $this->dbName = "blog";
                $this->username = "root";
                $this->password = "2dw3";
            break;

            case "prod":
                $this->servername = "";
                $this->dbName = "";
                $this->username = "";
                $this->password = "";
            break;

            case "mikelhome":
                $this->servername = "localhost";
                $this->dbName = "blog";
                $this->username = "root";
                $this->password = "";
            break;
            
            case "nosy":
                $this->servername = "localhost";
                $this->dbName = "blog";
                $this->username = "root";
                $this->password = "Nosy123";
            break;
        }
    }
}