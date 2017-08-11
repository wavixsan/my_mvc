<?php
/**
 * Created by PhpStorm.
 * User: User
 * Date: 25.05.2017
 * Time: 8:36
 */

namespace Library;

class Config
{
    private $errorController = 'default';
    private $errorAction = '404';
    private $layout = 'layout.php';
    private $containerPublic = false;

    public function get($key=null){
        if($key==null){
            $object = new config_object();
            foreach(get_object_vars($this) as $k=>$v){
                $object->$k = $v;
            }
            return $object;
        }else if(is_array($key)){
            $object = new config_object();
            foreach($key as $v) {
                $object->$v = isset($this->$v) ? $this->$v : null ;
                if($object->$v === null){
                    Error::error("Config: Параметр '$v' не найден, возвратил 'null'");
                }
            }
            return $object;
        }else if(isset($this->$key)){
            return $this->$key;
        }
        Error::error("Config: Параметр '$key' не найден, возвратил 'null'");
        return null;
    }//get()

    public function set($key,$value){
        $this->$key = $value;
        return $this;
    }

    public function __construct()
    {
        $res = $this->includeConfig('config');
        if(!$res){$res =$this->includeConfig('Config');}
        if($res){
            if(is_array($res)){
                foreach($res as $k=>$v){
                    $this->set($k,$v);
                }
            }else{
                Error::error('Config: Конфигурационный файл поврежден, используем стандартные настройки');
            }
        }else{
            Error::error('Config: Нет конфигурационного файла, используем стандартные настройки');
        }
    }//__construct()

    public function includeConfig($file,$p=false)
    {
        $file=ROOT.'Config'.DS."{$file}.php";
        if(file_exists($file)){
            Error::includeFile($file);
            $res = require_once($file);
            if(is_array($res)){
                if($p){
                    return new Vars($res);
                }
                return $res;
            }
            Error::fatal_error("Config: Подключаемый файл ('{$file}') не нередал массив");
        }
        if($p){Error::fatal_error("Config: Не найден подключаемый файл ('{$file}')");}
        return false;
    }//includeConfig() -

}//class Config

class config_object{}
