<?php
$app->get('/', 'App\Controllers\Demo\HomeController:login');
$app->post('/login', 'App\Controllers\Demo\HomeController:login');

$app->get('/index', 'App\Controllers\Demo\HomeController:index');
$app->post('/index', 'App\Controllers\Demo\HomeController:index');
$app->get('/profile', 'App\Controllers\Demo\HomeController:profile');
$app->get('/logout', 'App\Controllers\Demo\HomeController:logout');

$app->get('/hello/{name}', 'App\Controllers\Demo\HelloController:index');
