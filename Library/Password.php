<?php
/**
 * Created by PhpStorm.
 * User: User
 * Date: 23.07.2017
 * Time: 16:28
 */

namespace Library;


class Password
{
    private $password;

    public function __construct($password,$salt=false)
    {
        $this->password = md5($password.md5((($salt)? $salt : 'Yes, Mr White! Yes, science!')));
    }

    public function __toString()
    {
        return $this->password;
    }
}