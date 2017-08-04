<?php
return array(
    "home"=>array("home","index","/"),
    "home_"=>array("home","index","/index.html"),

    "books"=>["book","index","/books.html"],
    "books_page"=>["book","index","/books-page_{page}.html",["page"=>"[0-9]+"]],
    "book_show"=>["book","show","/book-{id}.html",["id"=>"[0-9]+"]],

    "names_list"=>["name","index","/names.html"],
    "name_page"=>["name","name","/name-{id}.html",["id"=>"[0-9]+"]],

    "login"=>["Security","login","/login.html"],
    "register"=>['SECURITY',"register",'/register.html'],
    "logout"=>['security',"logout",'/logout.html'],

    "active"=>['security','active','/active-{email}-{code}.html',['email'=>'[a-zA-Z0-9_\-\.\@]+','code'=>'[0-9a-z]+']],
    'email'=>['security','email','/email-{email}-{code}.html',['email'=>'[a-zA-Z0-9_\-\.\@]+','code'=>'[0-9a-z]+']],

    "analyzer"=>["analyzer","index","/analyzer"],

    /* admin */
    "admin"=>["admin\\default","index","/admin",null,"admin.phtml",["admin\\security","login",'admin_session']],
    "admin_"=>["admin\\default","PANEL","/admin.html"],
    "admin_logout" => ["admin\\security","logout","/admin_logout.html"],

    "admin_books"=>["admin\\book","index","/admin/books"],
    "admin_book_add"=>['admin\\book','add',"/admin/book/add"],
    "admin_book_delete"=>["admin\\book","delete","/admin/book/delete/{id}",['id'=>'[0-9]+']],
    "admin_book_edit"=>["admin\\book","edit","/admin/book/edit/{id}",['id'=>'[0-9]+']],

    "admin_help" => ['admin\\help',"index",'/admin-help.html'],
    "admin_help_show"=>['admin\\help',"show","/admin-help-show-{id}.html",["id"=>"[0-9]+"]],
    "admin_help_all"=>["admin\\help","all","/admin-helper.html"],
    "admin_help_add"=>["admin\\help","add","/admin-helper_add.html"],
    "admin_help_edit"=>["admin\\help","edit","/admin-helper-edit-{id}.html",["id"=>"[0-9]+"]],

);
