<?php
/**
 * Created by PhpStorm.
 * User: User
 * Date: 16.07.2017
 * Time: 11:41
 */

namespace Library;


class Vars
{
    public function __construct($key=null,$value=null)
    {
        if(is_array($key)){
            foreach($key as $k=>$v){
                $this->$k = $v;
            }
        }else if($key){
            $this->$key = $value;
        }
    }

    public function set($key,$value=null)
    {
        if(is_array($key)){
            foreach($key as $k=>$v){
                $this->$k = $v;
            }
        }else{
            $this->$key = $value;
        }
        return $this;
    }

    public function get($key)
    {
        if(isset($this->$key)){
            return $this->$key;
        }
        return null;
    }
}