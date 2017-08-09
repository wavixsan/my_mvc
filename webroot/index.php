<?php
error_reporting(E_ALL);

use Library\Error;
use Library\Core;

define('DS',DIRECTORY_SEPARATOR);
define("ROOT",dirname(__DIR__).DS);

if(file_exists(ROOT."vendor".DS.'autoload.php')){
    require_once(ROOT."vendor".DS.'autoload.php');
}

require_once(ROOT."Library".DS."autoload.php");

Error::$fatalErrorMonitor = false;

echo (new Core())->core();

//Error::status();


//доделать регистрацию


//будстрап ?
//доделать админку
//зделать категории


