<?php

use Web\App\Core\Model;
use Web\App\Core\AppFunction;
use Web\App\Core\Route\Route;

AppFunction::user_not_connected($router->url('admin.index'));

$post = Model::getPost(['id' => $params['id']]);
if($post->online !== 0) {
    Model::updatePost(['id' => $params['id']], [
        'online'    =>  0
    ]);
    AppFunction::successMessage('La page ' . $post->slug . ' est désormais hors ligne', $router->url('admin.posts'));
}else {
    AppFunction::errorMessage('Cette page est déjà hors ligne', $router->url('admin.posts'));
}
