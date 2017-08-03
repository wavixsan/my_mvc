<?php
/**
 * Created by PhpStorm.
 * User: User
 * Date: 27.07.2017
 * Time: 0:11
 */

namespace Library;


class Validation
{
    public function valid($res,$p=false)
    {
        return (bool) ($p) ? $this->processing($res) : $res;
    }

    public function processing($res)
    {
        if(is_array($res)){
            foreach($res as $k=>$v){
                $res[$k] = trim(htmlspecialchars(stripslashes($v)));
            }
            return $res;
        }
        return trim(htmlspecialchars(stripslashes($res)));
    }
}