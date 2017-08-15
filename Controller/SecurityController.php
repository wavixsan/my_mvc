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
use Library\Vars;

class SecurityController extends Controller
{
    public function actionLogin($request)
    {
        if($request->isPost()){
            $session = $this->get('session');
            $valid = new Validation();
            $password = $valid->processing($request->post('password'));
            $email = $valid->processing($request->post('email'));
            if($password and $email){
                $password = new Password($password);
                $res = $this->get('model')->get('user')->login($email,$password);
                if($res!=null) {
                    if($request->post('active') === 'yes'){
                        $this->cookie->set('login',$email);
                    }
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
        $this->cookie->delete('login');
        $this->get('session')->remove('user_session');
        $this->get('router')->redirect("/");
    }

    public function actionRegister($request)
    {
        $username = $email = '';
        if($request->isPost()){
            $session = $this->get('session');
            $valid = new Validation();
            $username = $valid->processing($request->post('username'));
            $email = $valid->processing($request->post('email'));
            if($request->post('active') == 'yes'){
                $password = $valid->processing($request->post('password'));
                $passwordConfirm = $valid->processing($request->post('passwordConfirm'));
                if($password===$passwordConfirm){
                    if($password and $email and $username){
                        $model = $this->get('model')->get('user');
                        if($model->test($email)){
                            $passwordSave = new Password($password);
                            $active = new Password($email.'res'.$username,$passwordSave);
                            $model->save($username,$email,$passwordSave,$active,false);
                            extract(get_object_vars($this->config->get(['siteName','siteEmail'])));
                            $url = "http://".$siteName.$this->get('router')->getUrl('active',['code'=>$active]);
                            $title = "Регистрация на сайте: ".$siteName;
                            $header = "MIME-Version: 1.0\r\nContent-Type: text/html; charset=utf-8\r\nFrom: $siteEmail";
                            $text = "<html>
<head>
  <title>$title</title>
</head>
<body>
Здравствуйте <b style=\"color:#f90;\">$username</b> вы зарегистрировались на сайте <b style=\"color:#f90;\">$siteName</b> .<br>
<b>Ваш логин:</b> <span style=\"color:#f90;\">$email</span>, <br>
<b>Ваш пароль:</b> <span style=\"color:#f90;\">$password</span>,<br>
Подтвердите пожалуйста свой электронный адрес перейдя по ссылке:<br>
<a style=\"color:#33f;\" href=\"$url\" target=\"_blank\"><b>$url</b></a>
</body>
</html>";
                            mail($email,$title,$text,$header);
                            $this->get('router')->redirect($this->get('router')->getUrl('email'));
                        }else{
                            $session->message('Email существует!');
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
        return $this->view("register.phtml",['form'=>new Vars(compact('username','email'))]);
    }//actionRegister() -


    public function actionEmail($request)
    {
        $url = "/active-{$request->get('email')}-{$request->get('code')}.html";
        return $this->view('email.phtml',compact('url'));
    }

    public function actionActive($request)
    {
        $session = $this->get('session');
        $code = (new Validation())->processing($request->get('code'));
        if($code and $this->model->get('user')->activeTest($code)){
            $this->get('model')->get('user')->active($code);
            $session->message('Активен!');
        }else{
            $session->message('Пользователь не найден или пользователь уже зарегистрирован!');
        }
        return $this->view('active.phtml');
    }
}