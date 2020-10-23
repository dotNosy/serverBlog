<?php 

    if (!empty($_SESSION['user'])) 
    {
        $user = unserialize($_SESSION['user']);

        echo $user->getId();
        echo $user->getUsername();
    }
