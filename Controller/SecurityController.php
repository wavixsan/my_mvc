<?php
/**
 * Created by PhpStorm.
 * User: User
 * Date: 23.07.2017
 * Time: 16:45
 */

namespace Controller;

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
                $res = $this->get('model')->get('user')->find($email,$password);
                if($res!=null) {
                    $session->set('user_session', $res->email);
                    $this->get('router')->redirect("/");
                }
                $session->message('Пользователь не найден!');
            }else{
                $session->message('Форма не валидна!');
            }
        }
        return $this->view('login.phtml');
    }

    public function actionLogout()
    {
        $this->get('session')->remove('user_session');
        $this->get('router')->redirect("/");
    }

    public function actionRegister($request)
    {
        if($request->isPost()){
            $session = $this->get('session');
            if($request->post('active') == 'yes'){
                $valid = new Validation();
                $username = $valid->processing($request->post('username'));
                $email = $valid->processing($request->post('email'));
                $password = $valid->processing($request->post('password'));
                $passwordConfirm = $valid->processing($request->post('passwordConfirm'));
                if($password===$passwordConfirm){
                    if($valid->valid($password) and $valid->valid($email) and $valid->valid($username)){
                        $model = $this->get('model')->get('user');
                        if($model->test($email)){
                            $password = new Password($password);
                            $active = new Password($username,$password);
                            $model->save($username,$email,$password,$active,false);
//                            $session->set('user_session', $email);
                            $this->get('router')->redirect("/email-$email-$active.html");//todo - письмо на почту
                        }else{
                            $session->message('Пользователи существует!');
                        }
                    }else{
                        $session->message('Форма не валидна!');
                    }
                }else{
                    $session->message('Пароли не совпадают!');
                }
            }else{
                $session->message('Соглашение не подтверждено!');
            }
        }
        return $this->view("register.phtml");
    }//actionRegister() -


    public function actionEmail($request)
    {
        $url = "/active-{$request->get('email')}-{$request->get('code')}.html";
        return $this->view('email.phtml',compact('url'));
    }

    public function actionActive($request)
    {
        $session = $this->get('session');
        if($request->get('code') and $request->get('email')){
            $this->get('model')->get('user')->active($request->get('email'),$request->get('code'));
            $session->message('Активен');
        }else{
            $session->message('Нет кода для активации');
        }
        return $this->view('active.phtml');
    }
}