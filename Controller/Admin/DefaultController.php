<?php
/**
 * Created by PhpStorm.
 * User: User
 * Date: 22.07.2017
 * Time: 23:51
 */

namespace Controller\Admin;

use Library\Controller;

class DefaultController extends Controller
{
    public function actionIndex()
    {
        return $this->view('index.phtml');
    }
}