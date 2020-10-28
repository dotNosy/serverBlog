<?php 
    use ServerBlog\Models\User;

    $user = User::getUser();

    if (!empty($user))
    {
        echo $user->id;
        echo $user->username;
    }
