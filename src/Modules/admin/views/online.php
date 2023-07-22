<?php

use Web\App\Core\Model;
use Web\App\Core\AppFunction;
use Web\App\Core\Route\Route;

AppFunction::user_not_connected($router->url('admin.index'));

$page = Model::getPage(['id' => $params['id']]);
if(!is_null($page->content) | !empty($page->content)) {
    if ($page->online !== 1) {
        Model::updatePage(['id' => $params['id']], [
            'online'    =>  1
        ]);
        AppFunction::successMessage(
            'La page ' . $page->url . ' à bien été mis à ligne. <a href="'.$router->url('page.index', ['slug' => $page->url]).'" style="color:green; border-bottom:1px solid green;">acceder</a>', 
            $router->url('admin.page')
        );
    }else {
        AppFunction::errorMessage('Cette page est déjà en ligne', $router->url('admin.page'));
    }
}else {
    AppFunction::infoMessage('Une page sans contenu ne peut pas être mis en ligne. Veuillez d\'abord créer la page', $router->url('admin.page'));
}

