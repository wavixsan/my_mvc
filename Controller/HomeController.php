<?php

namespace Controller;

use Library\Controller;

class HomeController extends Controller
{
    public function actionIndex()
    {
        $data = array('name'=>'Андрей',"age"=>'27');
//        return array('index.phtml',$data);
        return $this->view('index.phtml',$data);
    }

//    public function action404()
//    {
//        return $this->controller('404.phtml');
//    }

    public function actionError()
    {
        return $this->view('404.phtml',['error']);
    }

}