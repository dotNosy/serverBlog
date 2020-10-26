<?php

declare(strict_types=1);

namespace ServerBlog\Services\Controllers;

use ServerBlog\Services as Services;

use ServerBlog\Models as Models;

class Login
{
    public function __construct(array $params = null) 
    {
        parent::__construct($params);
    }

    public function checkPassword($pwd, &$errors) {
        // $errors_init = $errors;
        $errors = "";
        if (strlen($pwd) < 8) {
            $errors .= "Password too short!<br>";
        }
    
        if (!preg_match("#[0-9]+#", $pwd)) {
            $errors .= "Password must include at least one number!<br>";
        }
    
        if (!preg_match("#[a-zA-Z]+#", $pwd)) {
            $errors .= "Password must include at least one letter!<br>";
        }     
    
        return $errors;//($errors == $errors_init);
    }

}