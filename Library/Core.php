<?php
/**
 * Created by PhpStorm.
 * User: User
 * Date: 24.05.2017
 * Time: 13:16
 */

namespace Library;

class Core
{
    private $container;
    private $controller;
    private $action;
    private $params;

    public function core()
    {
        $this->container = new Container();
        $this->container->set('session',(new Session())->start());
        $this->container->set('cookie',new Cookie);
        $this->container->set('config',new Config);
        $this->container->set('request',new Request());
        $this->container->set('router',new Router($this->container));
        $this->container->set('model',(new Model())->setPdo((new Connect($this->container->get('config')))->getPdo()));
        $this->container->set('cart',new Cart($this->container->get('cookie'),$this->container->get('config')));

        $config = get_object_vars($this->container->get('config')->get(['errorController','errorAction','containerPublic']));
        foreach($config as $k=>$v){$this->$k=ucfirst($v);}

        $route = $this->container->get('router')->route();
        if($route){
            $this->params = $this->container->get('request')->set($route->vars);
            $this->controller = $route->controller;
            $this->action = $route->action;
        }else{
            Error::error("Core: Нет совпадений по роуту");
            $this->controller = $this->errorController;
            $this->action = $this->errorAction;
        }

        $return = $this->controller();
        if($return===false){
            Error::error("Core: Контроллер ('".$this->controller."') передал false");
            $this->controller = $this->errorController;
            $this->action = $this->errorAction;
            $return = $this->controller();
        }else if(!$return){
            Error::fatal_error("Core: Контроллер ('".$this->controller."') не передал результат вывода");
        }
        return $return;
    }//core()

    private function controller()
    {
        $class = "Controller\\".ucfirst($this->upStr($this->controller))."Controller";
        $method = "action".ucfirst($this->action);
        if(!method_exists($class,$method)){
            Error::fatal_error("Core: Нет экшена ('{$this->action}') в контроллере ('{$this->controller}')");
        }
        $controller = (new $class)->setContainer($this->container,$this->containerPublic);
        return $controller->$method($this->params);
    }

    private function upStr($str,$position=0)
    {
        $pos = strpos($str,"\\",$position);
        if($pos){
            $str = str_replace(substr($str,$pos,2),strtoupper(substr($str,$pos,2)),$str);
            return $this->upStr($str,$pos+1);
        }
        return $str;
    }

}//Core{}
