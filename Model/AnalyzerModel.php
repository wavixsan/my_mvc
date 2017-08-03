<?php
/**
 * Created by PhpStorm.
 * User: User
 * Date: 27.07.2017
 * Time: 15:59
 */

namespace Model;

use Library\TraitPDO;
use Library\Vars;

class AnalyzerModel
{
    use TraitPDO;

    public function save($name,$proteins,$fats,$carbohydrates,$calories)
    {
        $sth = $this->pdo->prepare('INSERT INTO analyzer VALUES (null, :name, :proteins, :fats, :carbohydrates, :calories)');
        $sth->execute(compact('name','proteins','fats','carbohydrates','calories'));
    }

    public function get($name)
    {
        $sth = $this->pdo->prepare("SELECT * FROM analyzer WHERE name = :name");
        $sth->execute(compact('name'));//
        while ($res = $sth->fetch(\PDO::FETCH_ASSOC)){
            return new Vars($res);
        }
        return null;
    }
}