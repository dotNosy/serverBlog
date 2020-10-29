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
            $errors .= "La contraseña es demasiado corta! (Al menos 8 caracteres)<br>";
        }
    
        if (!preg_match("#[0-9]+#", $pwd)) {
            $errors .= "La contraseña tiene que tener por lo menos un numero!<br>";
        }
    
        if (!preg_match("#[a-zA-Z]+#", $pwd)) {
            $errors .= "La contraseña debe tener por lo menos una letra!<br>";
        }     
        return $errors;//($errors == $errors_init);
    }

}