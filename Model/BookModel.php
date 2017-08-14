<?php

namespace Model;

use Library\TraitPDO;
use Library\Vars;

class BookModel
{
    use TraitPDO;

    public function bookId($ids)
    {
        $return = $count = $arr = [];
        foreach($ids as $k=>$v){
            $count[] = "?";
            $arr[] = $k;
        }
        $count = implode(', ',$count);
        $sth = $this->pdo->prepare("SELECT id,title,price,status FROM book WHERE id IN ($count)");
        $sth->execute($arr);
        while ($res = $sth->fetch(\PDO::FETCH_ASSOC)){
            $return[] = (new Vars($res))->set('count',$ids[$res['id']]);
        }
        return $return;
    }

    public function allBooks()
    {
        $array = [];
        $sth = $this->pdo->query("SELECT * FROM book WHERE status = 1");
        while ($res = $sth->fetch(\PDO::FETCH_ASSOC)){
            $array[] = (new Vars())->set($res);
        }
        return $array;
    }

    public function pageBooks($offset,$count,$category)
    {
        $array = []; $style='';
        if($category and $this->testStyle($category)){
            $style="AND style_id=$category";
        }
        $sth = $this->pdo->query("SELECT * FROM book WHERE status = 1 {$style} LIMIT {$offset}, {$count}");
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

    public function styleOptions($option,$param,$return=false)
    {
        switch($option){
            case 'book_style_update':
                $sql = "UPDATE book SET style_id=1 WHERE style_id=:id";
                $array = ['id'=>$param];
                break;
                break;
            case 'add':
                $sql = "INSERT INTO style VALUES (null,:title)";
                $array = ['title'=>$param];
                break;
            case 'edit':
                $sql = "UPDATE style SET title=:title WHERE id=:id";
                $array = $param;
                break;
            case 'delete':
                $sql = "DELETE FROM style WHERE id=:id";
                $array = ['id'=>$param];
                break;
            case 'show':
                $sql = "SELECT * FROM style WHERE id=:id";
                $array = ['id'=>$param]; $return=true;
                break;
            default: return false;
        }
        $sth = $this->pdo->prepare($sql);
        $sth->execute($array);
        if($return){
            while($res = $sth->fetch(\PDO::FETCH_OBJ)){
                return $res;
            }
        }
        return false;
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

    public function countBooks($category)
    {
        $style='';
        if($category and $this->testStyle($category)){
            $style="AND style_id=$category";
        }
        $sth = $this->pdo->query("SELECT count(id) FROM book WHERE status = 1 $style");
        return $sth->fetchColumn();
    }
}