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
        $_SESSION["titulo"] = "TEST CONTROLLER";
        unset($_SESSION["css"]);

        $_SESSION["page"] =  __DIR__ . '/../Views/testChild.php';
        require_once __PARENT_TEMPLATE__;
        die();
    }

    protected function test()
    {
        $_SESSION["titulo"] = "TEST METHOD";

        $_SESSION["page"] =  __DIR__ . '/../Views/testChild2.php';
        require_once __PARENT_TEMPLATE__;
        die();
    }
}