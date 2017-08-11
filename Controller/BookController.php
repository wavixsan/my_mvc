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

class BookController extends Controller
{
    private $count = 6;
    private $page=1;

    public function actionIndex($request)
    {
        $model = $this->get('model')->get('book');
        $count = ceil($model->countBooks()/$this->count);
        if(isset($request->page)){
            if($request->page == 0 or $request->page > $count){return false;}
            $this->page = $request->page;
        }
        $buttons = new Pagination($this->page,$count);

        $books = $model->pageBooks((($this->page-1)*$this->count),$this->count);
//        var_dump($books);
        return $this->view("index.phtml",["books"=>$books,"buttons"=>$buttons->buttons]);
    }

    public function actionShow($params)
    {
        $model = $this->get('model')->get('book');
        $book = $model->showBook($params->id);
//        var_dump($book);
        return $this->view("book.phtml",["book"=>$book]);
    }
}