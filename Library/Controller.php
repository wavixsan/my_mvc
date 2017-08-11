<?php

namespace Library;

class Controller
{
    private $container;

    public function setContainer($container,$p=false)
    {
        $this->container = $container;
        if($p){
            foreach($container->all() as $k=>$v){
                $this->$k = $v;
            }
        }
        return $this;
    }

    public function get($key)
    {
        return $this->container->get($key);
    }

    public function view($view=null,array $data=[])
    {
        $controller = str_replace(['\\','Controller'],[DS,''],get_class($this));
        $config = $this->get('config')->get(['layout']);
        $_content = null;

        if($view===null){
            Error::fatal_error("Controller: Контроллер '".get_class($this)."' не передал имя для View");
        }

        $viewFileLayout=ROOT."View".DS."{$config->layout}";
        if(!file_exists($viewFileLayout)){
            Error::fatal_error("Controller: View файл: '$viewFileLayout' не найден");
        }

        extract($data);

        if($view!==false){
            $viewFileController = ROOT.'View'.$controller.DS.$view;

            if(!file_exists($viewFileController)){
                Error::fatal_error("Controller: View файл: '$viewFileController' не найден");
            }

            Error::includeFile($viewFileController);
            ob_start();
            require_once($viewFileController);
            $_content  = ob_get_clean();
        }

        Error::includeFile($viewFileLayout);
        ob_start();
        require_once($viewFileLayout);
        return ob_get_clean();

    }//view()
}