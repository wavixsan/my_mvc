<?php
/**
 * Created by PhpStorm.
 * User: User
 * Date: 10.08.2017
 * Time: 22:34
 */

namespace Library;


class Cart
{
    private $cookie;
    private $cart;

    public function __construct($cookie)
    {
        $this->cookie = $cookie;
        $this->cart = unserialize($cookie->get('cart200'));
        if(!$this->cart){$this->cart = [];}
    }

    public function all()
    {
        return $this->cart;
    }

    public function save()
    {
        $this->cookie->set('cart200',serialize($this->cart));
    }

    public function add($id,$p=false)
    {
        if(isset($this->cart[$id])){
            $this->cart[$id]++;
        }else{
            $this->cart[$id] = 1;
        }
        if($p) $this->save();
        return $this;
    }

    public function test($id)
    {
        return isset($this->cart[$id])?true:false;
    }

    public function remove($id,$p=false)
    {
        if(isset($this->cart[$id])) unset($this->cart[$id]);
        if($p) $this->save();
        return $this;
    }

    public function minus($id,$p=false)
    {
        if(isset($this->cart[$id]) and $this->cart[$id]>0) $this->cart[$id]--;
        if($p) $this->save();
        return $this;
    }

    public function plus($id,$p=false)
    {
        if(isset($this->cart[$id])) $this->cart[$id]++;
        if($p) $this->save();
        return $this;
    }

    public function amount()
    {
        $count = 0;
        foreach($this->cart as $v){
            $count += $v;
        }
        return $count;
    }
}