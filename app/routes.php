<?php 

$route[] = ['/', 'HomeController@index'];

$route[] = ['/posts', 'PostsController@index'];
$route[] = ['/post/{id}/view', 'PostsController@view'];
$route[] = ['/post/create', 'PostsController@create', 'auth'];
$route[] = ['/post/store', 'PostsController@store', 'auth'];
$route[] = ['/post/{id}/edit', 'PostsController@edit', 'auth'];
$route[] = ['/post/{id}/update', 'PostsController@update', 'auth'];
$route[] = ['/post/{id}/delete', 'PostsController@delete', 'auth'];

$route[] = ['/user/create', 'UserController@create'];
$route[] = ['/user/store', 'UserController@store'];

$route[] = ['/login', 'UserController@login'];
$route[] = ['/login/auth', 'UserController@auth'];
$route[] = ['/logout', 'UserController@logout'];

return $route;

?>