<?php
/**
 * Created by PhpStorm.
 * User: User
 * Date: 23.07.2017
 * Time: 16:45
 */

namespace Controller\Admin;

use Library\Controller;
use Library\Password;
use Library\Validation;

class SecurityController extends Controller
{
    public function actionLogin($request)
    {
        if($request->isPost()){
            $session = $this->get('session');
            $valid = new Validation();
            $password = $valid->processing($request->post('password'));
            $email = $valid->processing($request->post('email'));
            if($valid->valid($password) and $valid->valid($email)){
                $password = new Password($password);
                $res = $this->get('model')->get('user')->admin($email,$password);
                if($res!=null) {
                    $session->set('admin_session', $res->email);
                    $session->set('user_session', $res->email);
                    $this->get('router')->redirect($request->getUri());
                }
                $session->message('Пользователь не найден!');
            }else{
                $session->message('Форма не валидна!');
            }
        }
        $this->get('config')->set('layout','login.phtml');
        return $this->view(false);
    }

    public function actionLogout()
    {
        $this->get('session')->remove('admin_session');
        $this->get('router')->redirect("/");
    }
}