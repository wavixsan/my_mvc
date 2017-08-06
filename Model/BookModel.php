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

    public function adminAllBooks($sorting,$style,$status)
    {
        $group = $where = ''; $arr = [];
        if($style) $where .=" AND book.style_id=$style";
        if($status){
            if($status == 1) $where .= " AND status=1";
            if($status == 2) $where .= " AND status=0";
        }
        if($sorting and $sorting['param']){
            $group = " GROUP BY ";
            if($sorting['param']){$group.= $sorting['sort'];}
            if($sorting['param']==2){$group.=" DESC";}
        }
        $sth = $this->pdo->query("SELECT book.id,book.title,book.price,book.status,style.title AS style FROM book,style WHERE book.style_id=style.id{$where}{$group}");
        while($res=$sth->fetch(\PDO::FETCH_OBJ)){
            $arr[] = $res;
        }
        return $arr;
    }

    public function addBook($title,$price,$style_id,$description,$status)
    {
        $sth = $this->pdo->prepare("INSERT INTO book VALUES (null,:title,:price,:style_id,:description,:status)");
        $sth->execute(compact("title","price","style_id","description","status"));
    }

    public function deleteBook($id)
    {
        $sth = $this->pdo->prepare("DELETE FROM book WHERE id=:id");
        $sth->execute(compact('id'));
    }

    public function editBook($id){
        $sth = $this->pdo->prepare("SELECT * FROM book WHERE id=:id");
        $sth->execute(["id"=>$id]);
        while($res=$sth->fetch(\PDO::FETCH_OBJ)){
            return $res;
        }
        return null;
    }

    public function update($id,$title,$price,$style_id,$description,$status)
    {
        $sth = $this->pdo->prepare("UPDATE book SET title=:title,price=:price,style_id=:style_id,description=:description,status=:status WHERE id=:id");
        $sth->execute(compact("id","title","price","style_id","description","status"));
    }

    public function allStyles()
    {
        $styles = [];
        $sth = $this->pdo->query("SELECT * FROM style");
        while($res = $sth->fetch(\PDO::FETCH_OBJ)){
            $styles[] =  $res;
        }
        return $styles;
    }

    public function testStyle($id)
    {
        $sth = $this->pdo->prepare("SELECT * FROM style WHERE id=:id");
        $sth->execute(['id'=>$id]);
        while ($res = $sth->fetch(\PDO::FETCH_ASSOC)){
            return true;
        }
        return false;
    }

    public function countBooks()
    {
        $sth = $this->pdo->query("SELECT count(id) FROM book WHERE status = 1");
        return $sth->fetchColumn();
    }
}