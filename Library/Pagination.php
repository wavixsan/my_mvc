<?php
/**
 * Created by PhpStorm.
 * User: User
 * Date: 16.07.2017
 * Time: 23:21
 */

namespace Library;


class Pagination
{
//todo
    public $buttons=[];

    public function __construct($page,$count){//« 1... 3 4 5 6 7 ...10 »
        $this->buttons[]=(new Vars())->set($this->button($page-1,'&laquo;',false,$page == 1));
        if($page>3){$this->buttons[] =new Vars($this->button(1,'1'));}
        if($page>4){$this->buttons[] =new Vars($this->button(null,'...'));}
        for($i=$page-2;$i<=$page+2;$i++){
            if($i>0 and $i<=$count){
                $this->buttons[]=(new Vars())->set($this->button($i,$i,$page==$i));
            }
        }
        if($page<$count-3){$this->buttons[] =new Vars($this->button(null,'...'));}
        if($page<$count-2){$this->buttons[] =new Vars($this->button($count,$count));}
        $this->buttons[]=(new Vars())->set($this->button($page+1,'&raquo;',false,$page>$count-1));
    }

    private function button($page,$text,$active=false,$disabled=false)
    {
        return ['page'=>$page,'text'=>$text,'active'=>$active,"disabled"=>$disabled];
    }
}
