<?php
/**
 * Created by PhpStorm.
 * User: User
 * Date: 28.05.2017
 * Time: 13:45
 */

namespace Controller;

use Library\Controller;

class NameController extends Controller
{
    private $names = ["Александр","Иван","Леонтий","Макар","Максим","Марк","Никита","Петр","Тихон"];

    public function actionIndex()
    {
        return $this->view("index.phtml",['names'=>$this->names]);
    }

    public function actionName($params)
    {
//        var_dump($params);

        if(isset($this->names[$params->id])){
            return $this->view('name.phtml',['name'=>$this->names[$params->id]]);
        }
        return false;
    }

//    public function actionNew()
//    {
//        //
//    }
}