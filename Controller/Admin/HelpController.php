<?php
/**
 * Created by PhpStorm.
 * User: User
 * Date: 01.08.2017
 * Time: 0:44
 */

namespace Controller\Admin;

use Library\Controller;
use Library\Vars;

class HelpController extends Controller
{
    public function actionIndex()
    {
        return $this->view("help.phtml",['contents'=>$this->get('model')->get('help')->all()]);
    }

    public function actionShow($var)
    {
        return $this->view("show.phtml",["res"=>$this->get('model')->get('help')->content($var->id)]);
    }

    public function actionAll($request)
    {
        if($request->post('delete')){
            $this->get('model')->get('help')->delete($request->post('delete'));
        }
        return $this->view('index.phtml',['contents'=>$this->get('model')->get('help')->all()]);
    }

    public function actionAdd($request)
    {
        $var = new Vars(["name"=>"","title"=>"","content"=>""]);

        if($request->isPost()){
            $var->name = $request->post('name');
            $var->title = $request->post('title');
            $var->content = $request->post('content');
            $this->get('model')->get('help')->add($var->name,$var->title,$var->content);
            $this->get('router')->redirect($this->get('router')->getUrl('admin_help_all'));
        }
        return $this->view('add.phtml',['var'=>$var]);
    }

    public function actionEdit($request)
    {
        if($request->isPost()){
            $name = $request->post('name');
            $title = $request->post('title');
            $content = $request->post('content');
            $this->get('model')->get('help')->save($request->post('id'),$name,$title,$content);
            $this->get('router')->redirect($this->get('router')->getUrl('admin_help_all'));
        }
        return $this->view("edit.phtml",["help"=>$this->get('model')->get('help')->edit($request->id)]);
    }
}