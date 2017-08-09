<?php
/**
 * Created by PhpStorm.
 * User: User
 * Date: 09.08.2017
 * Time: 17:38
 */

namespace Controller;
use Library\Controller;

class CartController extends Controller
{
    public function actionShow()
    {
        $cart = unserialize($this->get('cookie')->get('cart'));

        if($cart){
            $cart = $this->get('model')->get('book')->bookId($cart);
        }else{
            $cart = [];
        }
        return $this->view('show.phtml',['items'=>$cart]);
    }
    public function actionAddBook($request)
    {
        $id = $request->get('id');

        if($id and $this->get('model')->get('book')->showBook($id)){
            $cart = unserialize($this->get('cookie')->get('cart'));
            if(!$cart) $cart=[];
            if(isset($cart[$id])){$cart[$id]++;}else{$cart[$id]=1;}
            $this->get('cookie')->set('cart',serialize($cart));
            $this->get('router')->redirect($this->get('router')->getUrl('books'));
        }

        return false;
    }
}