<?php
/**
 * Created by PhpStorm.
 * User: User
 * Date: 23.07.2017
 * Time: 18:30
 */

namespace Model;

use Library\TraitPDO;

class UserModel
{
    use TraitPDO;

    public function login($email,$password)
    {
        $sth = $this->pdo->prepare("SELECT * FROM user WHERE email = :email AND password = :password");
        $sth->execute(compact('email','password'));//
        return $sth->fetch(\PDO::FETCH_OBJ);
    }

    public function test($email)
    {
        $sth = $this->pdo->prepare("SELECT * FROM user WHERE email = :email");
        $sth->execute(compact('email'));
        $res = $sth->fetch(\PDO::FETCH_ASSOC);
        return (bool) !$res;
    }

    public function save($username,$email,$password,$active,$admin)
    {
        $sth = $this->pdo->prepare('INSERT INTO user VALUES (null, :username, :email, :password, :active, :admin)');
        $sth->execute(compact('username','email','password','active','admin'));
    }

    public function activeTest($code)
    {
        $sth = $this->pdo->prepare("SELECT * FROM user WHERE active = :code");
        $sth->execute(compact('code'));
        return (bool) $sth->fetch(\PDO::FETCH_ASSOC);
    }

    public function active($active)
    {
        $sth = $this->pdo->prepare("UPDATE `user` SET `active` = 0 WHERE `user`.`active` = :active");
        $sth->execute(compact('active'));

    }

    public function admin($email,$password)
    {
        $sth = $this->pdo->prepare("SELECT * FROM user WHERE email = :email AND password = :password AND admin=1");
        $sth->execute(compact('email','password'));//
        return $sth->fetch(\PDO::FETCH_OBJ);
    }
}

