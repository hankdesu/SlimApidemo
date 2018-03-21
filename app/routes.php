<?php

$app->get('/hello/{name}', ['App\Controllers\AuthController', 'helloName']);

$app->post('/register', ['App\Controllers\AuthController', 'register'])->add(new \App\Middleware\RegisterMiddleware());
$app->post('/login', ['App\Controllers\AuthController', 'login']);

$app->post('/pageOfArticle', ['App\Controllers\ArticleController', 'pageOfArticle']);
$app->post('/postArticle', ['App\Controllers\ArticleController', 'postArticle']);
$app->post('/content', ['App\Controllers\ArticleController', 'content']);
$app->post('/deleteArticle', ['App\Controllers\ArticleController', 'delete']);
