<?php

namespace Model;

use Library\TraitPDO;
use Library\Vars;

class BookModel
{
    use TraitPDO;

    public function allBooks($offset,$count)
    {
        $array = [];
        $sth = $this->pdo->query("SELECT * FROM book WHERE status = 1");
        while ($res = $sth->fetch(\PDO::FETCH_ASSOC)){
            $array[] = (new Vars())->set($res);
        }
        return $array;
    }

    public function pageBooks($offset,$count)
    {
        $array = [];
        $sth = $this->pdo->query("SELECT * FROM book WHERE status = 1 LIMIT {$offset}, {$count}");
        while ($res = $sth->fetch(\PDO::FETCH_ASSOC)){
            $array[] = (new Vars())->set($res);
        }
        return $array;
    }

    public function showBook($id)
    {
        $sth = $this->pdo->prepare("SELECT * FROM book WHERE id = :id ");
        $sth->execute(['id' => $id]);
        while ($res = $sth->fetch(\PDO::FETCH_ASSOC)){
            return (new Vars())->set($res);
        }
        return null;
    }

    public function adminAllBooks()
    {
        $arr = [];
        $sth = $this->pdo->query("SELECT * FROM book");
        while($res=$sth->fetch(\PDO::FETCH_OBJ)){
            $arr[] = $res;
        }
        return $arr;
    }

    public function countBooks()
    {
        $sth = $this->pdo->query("SELECT count(id) FROM book WHERE status = 1");
        return $sth->fetchColumn();
    }
}