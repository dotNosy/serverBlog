<?php

declare(strict_types=1);

namespace ServerBlog\Controllers;

use ServerBlog\Services as Services;

class Controller
{
    public function __construct(array $params = null) 
    {
        //* Comprobar si se ha especificado metodo o tenemos que ir a un metodo por defecto (Index)

        //? Si no hay metodo
        if (count($params) == 1) {
            $this->index($params);
        }
        //? si hay metodo
        else if($params[1] != "")
        {
            if (method_exists($this, $params[1])) 
            {
                $method = $params[1];
                $this->$method($params);
            } else {
                Services\Helpers::sendTo404();   
            }
        }
        //? si pasa algo que no se esperaba
        else {
            $this->index($params);
        }
    }

    protected function sendToView(array $properties) :void
    {
        unset($_SESSION["css"]); //Reset css of the page
        unset($_SESSION["error"]); //Reset erorrs of the page

        foreach ($properties as $name => $value) 
        {
            //! Añadir la informacion para recogerla en las plantillas
            $_SESSION[$name] = $value;
        }
        
        require_once __PARENT_TEMPLATE__;
        die();
    }
}