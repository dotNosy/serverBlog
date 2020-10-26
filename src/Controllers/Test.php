<?php

declare(strict_types=1);

namespace ServerBlog\Controllers;

use ServerBlog\Services as Services;

class Test extends Controller
{
    public function __construct(array $params = null) 
    {
        parent::__construct($params);
    }

    protected function index(array $params = null) 
    {
        parent::sendToView([
             "titulo" => "TEST CONTROLLER"
            ,"page" =>  __DIR__ . '/../Views/testChild.php'
        ]);
    }

    protected function test()
    {
        parent::sendToView([
            "titulo" => "TEST METHOD"
           ,"page" =>  __DIR__ . '/../Views/testChild2.php'
       ]);
    }
}