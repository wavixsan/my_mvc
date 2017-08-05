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
use Library\Vars;

class BookController extends Controller
{
    public function actionIndex($request)
    {
        $session = $this->get('session');
        $sort_id = $session->get('sort_id');

        if($request->get('sort') and $request->get('param')){
            ${"sort_".$request->get('sort')} = $request->get('param');
        }
        switch($sort_id){case '1':case '2':break; default: $sort_id=0;}
        $books = $this->get('model')->get('book')->adminAllBooks($sort_id);

        $session->set('sort_id',$sort_id);

        $sorting = new Vars(["sort_id"=>$sort_id]);

        return $this->view("index.phtml",compact('books','sorting'));
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