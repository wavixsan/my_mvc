<?php
/**
 * Created by PhpStorm.
 * User: User
 * Date: 24.07.2017
 * Time: 0:30
 */

namespace Controller;

use Library\Controller;
use Library\Vars;

class AnalyzerController extends Controller
{
    public function actionIndex($request)
    {
        $result = []; $new = false;
        $weight = $proteins = $fats = $carbohydrates = $calories = 0;
        if($request->isPost() and $request->post('key')){
            switch($request->post('key')){
                case "add":
                    $this->add($request);
                    break;
                case "new": $new = true;
                case "analyze":
                    for($i=1; $i<=10; $i++) {
                        if ($request->post("name_$i")) {
                            $number = ($request->post("weight_$i")) ? $request->post("weight_$i") : 100;
                            $res = $this->get('model')->model('analyzer')->get($request->post("name_$i"));
                            $weight += $res->weight = $number;
                            $proteins += $res->proteins = $res->proteins / 100 * $number;
                            $fats += $res->fats = $res->fats / 100 * $number;
                            $carbohydrates += $res->carbohydrates = $res->carbohydrates / 100 * $number;
                            $calories += $res->calories = $res->calories / 100 * $number;
                            $result[$i] = $res;
                        } else {
                            break;
                        }
                    }
                    break;
            }
        }
        if($new){
            $count = count($result);
            $result[$count+1] = new Vars(['name'=>'',"weight"=>''] + compact('proteins','fats','carbohydrates','calories'));
        }
        if(!$result){
            $result[1] = new Vars(['name'=>'',"weight"=>''] + compact('proteins','fats','carbohydrates','calories'));
        }
        $sum = new Vars(compact('weight','proteins','fats','carbohydrates','calories'));
        return $this->view('index.phtml',compact('result','sum'));
    }

    private function add($request)
    {
        $name = $request->post('name');
        $proteins = $request->post('proteins');
        $fats = $request->post('fats');
        $carbohydrates = $request->post('carbohydrates');
        $calories = $request->post('calories');

        $this->get('model')->get('analyzer')->save($name,$proteins,$fats,$carbohydrates,$calories);

        $this->get('session')->message('ok');
    }
}