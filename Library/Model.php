<?php
/**
 * Created by PhpStorm.
 * User: User
 * Date: 15.07.2017
 * Time: 23:07
 */

namespace Library;


class Model
{
    use TraitPDO;

    private $model=[];

    public function get($model)
    {
        if(isset($this->model[$model])){
            return $this->model[$model];
        }
        $class = "Model\\".ucfirst($model)."Model";
        return $this->model[$model] = (new $class)->setPdo($this->pdo);
    }
}
