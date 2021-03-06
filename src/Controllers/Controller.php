<?php

declare(strict_types=1);

namespace ServerBlog\Controllers;

use ServerBlog\Services as Services;
use ServerBlog\Models\User;

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

    //! Esta funcion solo se puede usar para redirigir a una vista de un mismo controllador
    //* Los parametros del controlador son para vistas del mismo controlador o funcion
    //* Los parametros de url se obtienen de una llamada desde otro controlador u otra funcion del mismo usando Helpers::sendToController()
    protected function sendToView(array $controllerParams) :void
    { 
        //! VARIABLES NECESARIAS PARA UNA SESION AQUI
        $user = User::getUser();
        $URLparams = !empty($_SESSION["URL_PARAMS"]) ?  $_SESSION["URL_PARAMS"] : array();

        //! BORRAR RESTOS DE SESION
        foreach ($_SESSION as $key => $value) {
            unset($_SESSION[$key]);
        }

        //* CONTROLLER PARAMS
        foreach ($controllerParams as $name => $value) {
            //! Añadir la informacion para recogerla en las plantillas
            $_SESSION[$name] = $value;
        }

        //? URL PARAMS
        if (!empty($URLparams)) {
            foreach ($URLparams as $name => $value) {
                //! Añadir la informacion para recogerla en las plantillas
                $_SESSION[$name] = $value;
            }
        }

        //*Login user
        if (!empty($user)) {
            $_SESSION['user'] = json_encode($user);
        }

        require_once __PARENT_TEMPLATE__;
        die();
    }
}