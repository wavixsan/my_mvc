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
        return false;
//        $this->model = $this->get('model')->model('book');
//        $book = $this->model->showBook($params->id);
////        var_dump($book);
//        return $this->view("book.phtml",["book"=>$book]);
    }

    public function actionAdd($request)
    {
        if($request->isPost()){
            $title = $request->post('title');
            $price = $request->post('price');
            $style = $request->post('style');
            $description = $request->post('description');
            $status = $request->post('status');
            $this->get('model')->get('book')->addBook($title,$price,$style,$description,$status);
            $this->get('router')->redirect('/admin/books');
        }
        return $this->view("add.phtml",['styles'=>$this->get('model')->get('book')->allStyles()]);
    }

    public function actionDelete($request)
    {
        if($request->get("id")){
            $this->get('model')->get('book')->deleteBook($request->get("id"));
            $this->get("router")->redirect("/admin/books");
        }
        return false;
    }

    public function actionEdit($request)
    {
        if($request->isPost()){
            $id = $request->post('id');
            $title = $request->post('title');
            $price = $request->post('price');
            $style = $request->post('style');
            $description = $request->post('description');
            $status = $request->post('status');
            $this->get('model')->get('book')->update($id,$title,$price,$style,$description,$status);
            $this->get('router')->redirect('/admin/books');
        }
        if($request->get("id")){
            $book = $this->get('model')->get('book')->editBook($request->get("id"));
            if($book == null){return null;}
            $styles = $this->get('model')->get('book')->allStyles();
            return $this->view("edit.phtml",['book'=>$book,"styles"=>$styles]);
        }
        return false;
    }
}