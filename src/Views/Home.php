<?php 
    use ServerBlog\Models\User;

    $user = User::getUser();

    if ($user)
    {
        echo $user->getId();
        echo $user->getUsername();
    }
