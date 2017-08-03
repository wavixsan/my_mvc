<?php
/**
 * Created by PhpStorm.
 * User: User
 * Date: 04.07.2017
 * Time: 18:50
 */

namespace Library;


class Container
{
    private $container=[];

    public function get($key){
        if(isset($this->container[$key])){
            return $this->container[$key];
        }
        return null;
    }

    public function set($key,$value){
        $this->container[$key] = $value;
    }
}