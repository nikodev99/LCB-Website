<?php

use Web\App\Core\Model;
use Web\App\Core\AppFunction;

AppFunction::user_not_connected($router->url('admin.index'));

$post = Model::getPost(['id' => $params['id']]);
if(!is_null($post->content) | !empty($post->content)) {
    if ($post->online !== 1) {
        Model::updatePost(['id' => $params['id']], [
            'online'    =>  1
        ]);
        AppFunction::successMessage(
            'La page ' . $post->slug . ' à bien été mis à ligne. <a href="'.$router->url('page.index', ['slug' => $post->slug]).'" style="color:green; border-bottom:1px solid green">acceder</a>', 
            $router->url('admin.posts')
        );
    }else {
        AppFunction::errorMessage('Cette page est déjà en ligne', $router->url('admin.posts'));
    }
}else {
    AppFunction::infoMessage('Une page sans contenu ne peut pas être mis en ligne. Veuillez d\'abord créer la page', $router->url('admin.posts'));
}
