<?php
/**
 * Created by PhpStorm.
 * User: User
 * Date: 23.07.2017
 * Time: 16:59
 */

namespace Library;


class Session
{
    public function start(){
        if(session_id()==false){
            session_start();
        }
        return $this;
    }

    public function set($key,$value)
    {
        return $_SESSION[$key] = $value;
    }

    public function get($key)
    {
        if(isset($_SESSION[$key])){
            return $_SESSION[$key];
        }
        return null;
    }

    public function hash($key)
    {
        return (isset($_SESSION[$key])) ? true : false;
    }

    public function remove($key)
    {
        if(isset($_SESSION[$key])){
            unset($_SESSION[$key]);
            return true;
        }
        return false;
    }

    public function message($message=false,$key=null)
    {
        if(!$key){$key='flash_message';}
        if($message){
            return $this->set($key,$message);
        }
        $message = $this->get($key);
        $this->remove($key);
        return $message;
    }
}