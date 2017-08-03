<?php
/**
 * Created by PhpStorm.
 * User: User
 * Date: 16.07.2017
 * Time: 9:42
 */

namespace Library;


trait TraitPDO
{
    protected $pdo;

    public function setPdo($pdo)
    {
        $this->pdo = $pdo;
        return $this;
    }
}