<?php

namespace Controller\API;
use Library\Controller;

class CartController extends Controller
{
    public function actionAdd($request)
    {
        $id = $request->get('id');

        if($id and $this->model->get('book')->showBook($id)){
            $this->cart->add($id)->save();
        }
        return $this->cart->amount();
    }
}