<?php

namespace Library;

class Router
{
    private $uri;
    private $routes;
    private $security;
    private $params;

    public function __construct($container)
    {
        $this->uri = $container->get('request')->getUri();
        $routes = $container->get('config')->includeConfig('routes');
        if(!$routes){Error::fatal_error('Router: Нет файла роутеров');}
        foreach($routes as $k=>$v){
            $this->routes[$k] = call_user_func_array([$this,'setRoute'],$v);
        }
        if($this->security){
            foreach($this->security as $url=>$security){
                if(strpos($this->uri,$url)===0){
                    if(isset($security->layout)){

                        $container->get('config')->set('layout',str_replace(['/','\\'],DS,$security->layout));
                    }
                    if(isset($security->session) and !$container->get('session')->hash($security->session)){
                        $this->params = $security;
                    }
                    break;
                }
            }
        }
    }//__construct()

    public function route()
    {
        foreach($this->routes as $route){
            $match=$route->url;
            foreach($route->params as $k=>$v){
                $match = str_replace("{".$k."}","($v)",$match);
            }
            if(preg_match("@^".$match."$@",$this->uri,$matcher)){
                array_shift($matcher);
                $route->active = true;
                $route->vars=array_combine(array_keys($route->params),$matcher);
                if($this->params){
                    $route->controller = $this->params->controller;
                    $route->action = $this->params->action;
                }
                return $route;
            }
        }
        return null;
    }//route()

    public function getUrl($key,array $params=[])
    {
        if(isset($this->routes[$key])){
            $match = $this->routes[$key]->url;
            foreach($params as $k=>$v){
                $match = str_replace("{".$k."}","$v",$match);
            }
            return $match;
        }
        return null;
    }//getUrl()

    public function navBar(array $keys=[],$p=false)
    {
        $array=[];
        foreach($keys as $name=>$params){
            $active = false;
            $key = is_array($params) ? array_shift($params) : $params;
            if(isset($this->routes[$key])) {
                if($this->routes[$key]->active){
                    $active=true;
                }else{
                    if(is_array($params) and $params){
                        $p = false;
                        foreach($params as $k){
                            if(isset($this->routes[$k]) and $this->routes[$k]->active){
                                $active = true; break;
                            }
                            if($k===true) $p = true;
                        }
                    }
                    if($p===true){
                        foreach($this->routes as $route){
                            if($route->active and $route->controller==$this->routes[$key]->controller){
                                $active = true; break;
                            }
                        }
                    }
                }
                $array[] = (new Vars())->set(["name"=>$name,"url"=>$this->routes[$key]->url,"active"=>$active]);
                continue;
            }
            $array[] = (new Vars())->set(["name"=>'null',"url"=>'',"active"=>$active]);
        }
        return $array;
    }//navBar()

    public function redirect($to)
    {
        header("location: {$to}"); die;
    }

    private function setSecurity($controller,$action,$session)
    {
        return new Vars(compact('controller','action','session'));
    }

    private function setRoute($controller,$action,$url,$params=[],$layout=false,$security=false)
    {
        if(substr($url,0,1)!='/') $url = '/'.$url;
        if($security and is_array($security)){
            $this->security[$url] = call_user_func_array([$this,'setSecurity'],$security);
            if($layout){
                $this->security[$url]->set('layout',$layout);
            }
        }
        if(!$security and is_string($layout)){
        $this->security[$url] = new Vars('layout',$security);
    }
        $active = false;
        if(!is_array($params)){$params = [];}
        return new Vars(compact('controller','action','url','active','params'));
    }
}//Router{}
