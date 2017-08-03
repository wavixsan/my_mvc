<?php
/**
 * Created by PhpStorm.
 * User: User
 * Date: 01.08.2017
 * Time: 1:50
 */

namespace Model;

use \Library\TraitPDO;

class HelpModel
{
    use TraitPDO;

    public function content($id)
    {
        $sth = $this->pdo->prepare("SELECT name,content FROM help WHERE id=:id");
        $sth->execute(compact('id'));
        return $sth->fetch(\PDO::FETCH_OBJ);
    }

    public function save($id,$name,$title,$content)
    {
        $sth = $this->pdo->prepare("UPDATE help SET name=:name,title=:title,content=:content WHERE id=:id");
        $sth->execute(compact('id','name','title','content'));
    }

    public function edit($id)
    {
        $sth = $this->pdo->prepare("SELECT * FROM help WHERE id=:id");
        $sth->execute(compact('id'));
        return $sth->fetch(\PDO::FETCH_OBJ);
    }

    public function delete($id)
    {
        $sth = $this->pdo->prepare("DELETE FROM help WHERE id=:id");
        $sth->execute(compact('id'));
    }

    public function add($name,$title,$content)
    {
        $sth = $this->pdo->prepare('INSERT INTO help VALUES (null,:name,:title,:content)');
        $sth->execute(compact('name','title','content'));
    }

    public function all()
    {
        $arr = [];
        $sth = $this->pdo->query("SELECT id,name,title FROM help");
        while($res = $sth->fetch(\PDO::FETCH_OBJ)){
            $arr[] = $res;
        }
        return $arr;
    }
}