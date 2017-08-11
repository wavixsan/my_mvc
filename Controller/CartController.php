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
        $cart = $this->cart->all();
        if($cart){
            $cart = $this->model->get('book')->bookId($cart);
        }
        return $this->view('show.phtml',['items'=>$cart]);
    }

    public function actionOptions($request)
    {
        $option = $request->get('option');
        if($option){
            $id = $request->get('id');
            if($this->cart->test($id)){
                switch($option){
                    case 'minus':
                        $this->cart->minus($id);
                        break;
                    case "plus":
                        $this->cart->plus($id);
                        break;
                    case "delete":
                        $this->cart->remove($id);
                        break;
                }
                $this->cart->save();
                $this->router->redirect($this->router->getUrl('cart'));
            }
        }
        return false;
    }

    public function actionAddBook($request)
    {
        $id = $request->get('id');

        if($id and $this->model->get('book')->showBook($id)){
            $this->cart->add($id)->save();
            $this->router->redirect($this->router->getUrl('books'));
        }

        return false;
    }
}