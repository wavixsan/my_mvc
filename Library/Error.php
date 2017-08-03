<?php

namespace Library;

abstract class Error
{
    static private $error = [];
    static private $include = [];
    static public $fatalErrorMonitor = true;

    static function fatal_error($err){
        echo "<br><u><b style='color:#f00; z-index:9999;'>FATAL ERROR:</b></u><br>";
        if(self::$fatalErrorMonitor){
            echo "- <span style='color:#f00; z-index:9999;'>$err!</span><br>";
            self::status(false);
        }else{
            echo "- <span style='color:#f00; z-index:9999;'>Off view error!</span><br>";
        }
        exit;
    }

    static function error($err){
        self::$error[] = $err;
    }

    static function status($n=true){
        if($n){echo "<div style='border-radius:7px; z-index:9999; padding:0 10px; position:fixed; top:60px; right:10px; background:rgba(100,100,100,0.7);'>";}
        if(self::$error!=[]){
            echo "<br><u><b style='color:#f90;'>ERROR: </b></u><br>";
            foreach(self::$error as $v){
                echo "- <span style='color:#fb0;'>$v;</span><br>";
            }
        }else if($n==true){
            echo "<br><u><b style='color:#0d0;'>NOT ERROR</b></u><br>";
        }

        if(self::$include!=[]){
            echo "<br><u><b style='color:#00f;'>INCLUDE: </b></u><br>";
            foreach(self::$include as $v){
                echo "- <span style='color:#59f;'>$v;</span><br>";
            }
            echo "<br>";
        }
        if($n){echo "</div>";}
    }

    static function includeFile($inc){
        self::$include[] = $inc;
    }
}