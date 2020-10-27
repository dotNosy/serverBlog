<?php

declare(strict_types=1);

namespace ServerBlog\Controllers;

class Home extends Controller
{
    public function __construct(array $params = null) 
    {
        parent::__construct($params);
    }

    protected function index(array $params = null) 
    {
        parent::sendToView([
            "user" => serialize($user)
            ,"titulo" => "HOME"
            ,"page" => __DIR__ . '/../Views/Home.php'
         ]);
    }
}