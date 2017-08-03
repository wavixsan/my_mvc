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
        $books = $this->get('model')->get('book')->adminAllBooks();

        return $this->view("index.phtml",["books"=>$books]);
    }

    public function actionShow($params)
    {
//        $this->model = $this->get('model')->model('book');
//        $book = $this->model->showBook($params->id);
////        var_dump($book);
//        return $this->view("book.phtml",["book"=>$book]);
    }
}