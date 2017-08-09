<?php
/**
 * Created by PhpStorm.
 * User: User
 * Date: 09.08.2017
 * Time: 19:19
 */

namespace Library;


class Cookie
{
    public function set($key,$value,$time=360*24*60*60)
    {
        setcookie($key,$value,time()+$time,'/');
    }

    public function get($key)
    {
        if(isset($_COOKIE[$key])){
            return $_COOKIE[$key];
        }
        return null;
    }

    public function test($key)
    {
        if(isset($_COOKIE[$key])) return true;
        return false;
    }

    public function delete($key)
    {
        if(isset($_COOKIE[$key])){
            $this->set($key,'x',1);
            unset($_COOKIE[$key]);
        }
    }
}