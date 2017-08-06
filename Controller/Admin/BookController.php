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
        $model = $this->get('model')->get('book');

        $sort_id = $sort_title = $sort_price = 0;
        $sorting = unserialize($session->get('admin_book_sorting'));
        $sort_status = $session->get('admin_book_status');
        $sort_style = $session->get('admin_book_style')?$session->get('admin_book_style'):'0';
        $sort = $request->get('sort');
        $param = $request->get('param')?$request->get('param'):"0";

        if($sort){
            if($sort == 'status'){
                switch($param){case '1':case '2':break; default: $param=0;}
                $session->set("admin_book_$sort",$param);
                ${'sort_'.$sort} = $param;
            }elseif($sort == 'style' and ($model->testStyle($param) or $param == "0")){
                $session->set("admin_book_$sort",$param);
                ${'sort_'.$sort} = $param;
            }else{
                $sorting['sort'] = $sort;
                $sorting['param'] = $param;
                switch($sorting['param']){case '1':case '2':break; default: $sorting['param']=0;}
                $session->set('admin_book_sorting',serialize($sorting));
            }
        }

        if($sorting) ${"sort_".$sorting['sort']} = $sorting['param'];

        $books = $model->adminAllBooks($sorting,$sort_style,$sort_status);
        $styles[0] = new Vars(['id'=>'0','title'=>'---']);
        $styles += $model->allStyles();

        $sorting = new Vars(compact('sort_id','sort_price','sort_title','sort_style','sort_status'));

        return $this->view("index.phtml",compact('books','sorting','styles'));
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