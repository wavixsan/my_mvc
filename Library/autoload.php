<?php

use Library\Error;

spl_autoload_register(function($className){
    $file=ROOT.str_replace('\\',DS,"{$className}.php");
    if(file_exists($file)){
        require_once $file;
        Error::includeFile($file);
        if(trait_exists($className)){}else
            if(!class_exists($className)){
                Error::fatal_error("Autoload: Не найден класс ('$className') в подключаемом файле: $file");
            }
    }else{
        Error::fatal_error("Autoload: Не найден подключаемый файл: $file");
    }
});