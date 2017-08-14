<?php
/**
 * Created by PhpStorm.
 * User: User
 * Date: 06.07.2017
 * Time: 21:55
 */

namespace Controller;

use Library\Controller;
use Library\Pagination;
use Library\Vars;

class BookController extends Controller
{
    private $count = 3;
    private $page=1;

    public function actionIndex($request)
    {
        $category = (isset($request->category) and is_numeric($request->category))?$request->category:false;
        $model = $this->get('model')->get('book');
        $router = $this->get('router');
        $count = ceil($model->countBooks($category)/$this->count);

        if(isset($request->page) and is_numeric($request->page)){
            if($request->page == 0 or $request->page > $count){return false;}
            $this->page = $request->page;
        }

        $books = $model->pageBooks((($this->page-1)*$this->count),$this->count,$category);
        $style = $buttons = [];
        foreach($model->allStyles() as $v){
            if($v->id==1) continue;
            $url = $router->getUrl('books_category',['category'=>$v->id]);
            $style[]=new Vars([
                'id'=>$v->id,
                'name'=>$v->title,
                'url'=>$url,
                'active'=>($category==$v->id?true:false)
            ]);
            if($category==$v->id) $category = new Vars(['id'=>$category,'name'=>$v->title,'url'=>$url]);
        }
        foreach((new Pagination($this->page,$count))->buttons as $v){
            if(is_object($category)){
                $buttons[] = $v->set('url',$router->getUrl('books_category_page',['category'=>$category->id,'page'=>$v->page]));
            }else{
                $buttons[] = $v->set('url',$router->getUrl('books_page',['page'=>$v->page]));
            }
        }

        return $this->view("index.phtml",compact('books','buttons','style','category'));
    }

    public function actionShow($params)
    {
        $model = $this->get('model')->get('book');
        $book = $model->showBook($params->id);
        return $this->view("book.phtml",["book"=>$book]);
    }
}