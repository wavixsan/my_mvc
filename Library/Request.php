<?php
/**
 * Created by PhpStorm.
 * User: User
 * Date: 23.07.2017
 * Time: 1:59
 */

namespace Library;


class Request
{
    private $get;
    private $post;
    private $server;

    public function __construct()
    {
        $this->get = $_GET;
        $this->post = $_POST;
        $this->server = $_SERVER;
    }

    public function set($key,$value=null)
    {
        if(is_array($key)){
            foreach($key as $k=>$v){
                $this->get[$k] = $this->$k = $v;
            }
        }else{
            $this->get[$key]=$this->$key = $value;
        }
        return $this;
    }

    public function get($key)
    {
        if(isset($this->get[$key])){
            return $this->get[$key];
        }
        return null;
    }

    public function post($key)
    {
        if(isset($this->post[$key])){
            return $this->post[$key];
        }
        return null;
    }

    public function server($key)
    {
        if(isset($this->server[$key])){
            return $this->server[$key];
        }
        return null;
    }

    public function getUri()
    {
        return $this->server('REQUEST_URI');
    }

    public function isPost()
    {
        return ($this->post) ? true : false;
    }
}