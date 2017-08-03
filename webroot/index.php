<?php
error_reporting(E_ALL);

use Library\Error;
use Library\Core;

define('DS',DIRECTORY_SEPARATOR);
define("ROOT",dirname(__DIR__).DS);

require_once(ROOT."vendor".DS.'autoload.php');//vendor/

require_once(ROOT."Library".DS."autoload.php");

//Error::$fatalErrorMonitor = false;

echo (new Core())->core();

//Error::status();


//helper - добавить контент

//доделать регистрацию

//Router - добавить последнее значение в массив true для поиска по контроллеру


//будстрап ?
//доделать админку
//зделать категории


