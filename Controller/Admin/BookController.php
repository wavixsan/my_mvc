<?php
/**
 * Created by PhpStorm.
 * User: User
 * Date: 06.07.2017
 * Time: 21:55
 */

namespace Controller\Admin;

use Library\Controller;
use Library\Pagination;

class BookController extends Controller
{
    private $model;
    private $count = 6;
    private $page=1;

    public function actionIndex($request)
    {
//        $this->model = $this->get('model')->model('book');
//        $count = ceil($this->model->countBooks()/$this->count);
//        if(isset($request->page)){
//            if($request->page == 0 or $request->page > $count){return false;}
//            $this->page = $request->page;
//        }
//        $buttons = new Pagination($this->page,$count);
//
//        $books = $this->model->pageBooks((($this->page-1)*$this->count),$this->count);
////        var_dump($books);
//        return $this->view("index.phtml",["books"=>$books,"buttons"=>$buttons->buttons]);
        return $this->view("index.phtml");
    }

    public function actionShow($params)
    {
//        $this->model = $this->get('model')->model('book');
//        $book = $this->model->showBook($params->id);
////        var_dump($book);
//        return $this->view("book.phtml",["book"=>$book]);
    }
}