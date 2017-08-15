<?php
return array(
    "home"=>array("home","index","/"),
    "home_"=>array("home","index","/index.html"),

    "books"=>["book","index","/books.html"],
    "books_page"=>["book","index","/books-page_{page}.html",["page"=>"[0-9]+"]],
    "book_show"=>["book","show","/book-{id}.html",["id"=>"[0-9]+"]],
    "books_category"=>['book','index','books_{category}',['category'=>'[0-9]+']],
    "books_category_page"=>['book','index','books_{category}_{page}',['category'=>'[0-9]+','page'=>'[0-9]+']],

    "cart"=>['cart','show','/cart'],
    "cart_add_book"=>['cart','addBook','/cart-add-book-{id}/',['id'=>'[0-9]+']],
    "cart_options"=>['cart','options','/cart-book-options-{option}-{id}/',['option'=>'minus|plus|delete','id'=>'[0-9]+']],

    "api_cart_add"=>['api\\cart','add','/api/cart-add-{id}',['id'=>'[0-9]+']],

    "names_list"=>["name","index","/names.html"],
    "name_page"=>["name","name","/name-{id}.html",["id"=>"[0-9]+"]],

    "login"=>["Security","login","/login.html"],
    "register"=>['Security',"register",'/register.html'],
    "logout"=>['security',"logout",'/logout.html'],

    "active"=>['security','active','/active-{code}.html',['code'=>'[0-9a-z]+']],
    'email'=>['security','email','/email.html'],

    "analyzer"=>["analyzer","index","/analyzer"],

    /* admin */
    "admin"=>["admin\\default","index","/admin",null,"admin.phtml",["admin\\security","login",'admin_session']],
    "admin_"=>["admin\\default","Panel","/admin.html"],
    "admin_logout" => ["admin\\security","logout","/admin_logout.html"],

    "admin_books"=>["admin\\book","index","/admin/book"],
    "admin_book_add"=>['admin\\book','add',"/admin/book/add"],
    "admin_book_delete"=>["admin\\book","delete","/admin/book/delete/{id}",['id'=>'[0-9]+']],
    "admin_book_edit"=>["admin\\book","edit","/admin/book/edit/{id}",['id'=>'[0-9]+']],
    "admin_books_sorting"=>['admin\\book',"index","/admin/book/sort-{sort}-{param}",['sort'=>"[a-z_]+","param"=>"[0-9]+"]],
    "admin_book_style"=>['admin\\book','style','/admin/book/style'],
    "admin_book_style_option"=>['admin\\book','style','/admin/book/style-{option}-{value}',['option'=>'[a-z_]+','value'=>'[0-9]+']],

    "admin_help" => ['admin\\help',"index",'/admin-help.html'],
    "admin_help_show"=>['admin\\help',"show","/admin-help-show-{id}.html",["id"=>"[0-9]+"]],
    "admin_help_all"=>["admin\\help","all","/admin-helper.html"],
    "admin_help_add"=>["admin\\help","add","/admin-helper_add.html"],
    "admin_help_edit"=>["admin\\help","edit","/admin-helper-edit-{id}.html",["id"=>"[0-9]+"]],

);
