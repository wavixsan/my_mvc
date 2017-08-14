<?php
/**
 * Created by PhpStorm.
 * User: User
 * Date: 24.07.2017
 * Time: 0:30
 */

namespace Controller;

use Library\Controller;
use Library\Validation;
use Library\Vars;

class AnalyzerController extends Controller
{
    public function actionIndex($request)
    {
        $result = []; $new = false; $newAdd = $this->newProduct(false);
        $weight = $proteins = $fats = $carbohydrates = $calories = 0;
        if($request->isPost() and $request->post('key')){
            switch($request->post('key')){
                case "add":
                    $add = $this->add($request);
                    if($add) $newAdd = $add;
                    break;
                case "new": $new = true;
                case "analyze":
                    $i=1;
                    while($request->post("name_$i")!==null) {
                        $valid = new Validation();
                        $name_= $valid->processing($request->post("name_$i"));
                        $weight_ = $valid->processing($request->post("weight_$i"));
                        if($name_ ) {
                            $number = $weight_ ? $weight_ : 100;
                            $res = $this->get('model')->get('analyzer')->get($name_);
                            if($res){
                                $weight += $res->weight = $number;
                                $proteins += $res->proteins = $res->proteins / 100 * $number;
                                $fats += $res->fats = $res->fats / 100 * $number;
                                $carbohydrates += $res->carbohydrates = $res->carbohydrates / 100 * $number;
                                $calories += $res->calories = $res->calories / 100 * $number;
                                $result[$i] = $res;
                            }else{
                                $this->get('session')->message('нет совпадений = '.$name_);
                                break;
                            }
                        }else{
                            $this->get('session')->message('не валидная форма');
                            break;
                        }
                        $i++;
                    }
                    break;
            }
        }

        if($new){
            $count = count($result);
            $result[$count+1] = $this->newProduct();
        }
        if(!$result){
            $result[1] = $this->newProduct('','',$proteins,$fats,$carbohydrates,$calories);
        }
        $sum = $this->newProduct('',$weight,$proteins,$fats,$carbohydrates,$calories);
        return $this->view('index.phtml',['add'=>$newAdd]+compact('result','sum'));
    }

    public function newProduct($name='',$weight='',$proteins=0,$fats=0,$carbohydrates=0,$calories=0)
    {
        if($name===false) $name = $proteins = $fats = $carbohydrates = $calories = '';
        return new Vars(compact('name','weight','proteins','fats','carbohydrates','calories'));
    }

    private function add($request)
    {
        $valid = new Validation();

        $name = $valid->processing($request->post('name'));
        $proteins = $valid->processing($request->post('proteins'));
        $fats = $valid->processing($request->post('fats'));
        $carbohydrates = $valid->processing($request->post('carbohydrates'));
        $calories = $valid->processing($request->post('calories'));

        if($name and $proteins and $fats and $carbohydrates and $calories){
            if(!$this->model->get('analyzer')->test($name)){
                $this->get('model')->get('analyzer')->save($name,$proteins,$fats,$carbohydrates,$calories);
                $this->get('session')->message('ok');
                return false;
            }
            $this->get('session')->message('Имя существует');
        }else{
            $this->get('session')->message('error form');
        }
        return $this->newProduct($name,100,$proteins,$fats,$carbohydrates,$calories);
    }
}