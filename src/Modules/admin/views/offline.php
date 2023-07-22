<?php

use Web\App\Core\Model;
use Web\App\Core\AppFunction;
use Web\App\Core\Route\Route;

AppFunction::user_not_connected($router->url('admin.index'));

$page = Model::getPage(['id' => $params['id']]);
if ($page->online !== 0) {
    Model::updatePage(['id' => $params['id']], [
        'online'    =>  0
    ]);
    AppFunction::successMessage('La page ' . $page->url . 'est désormais hors ligne', $router->url('admin.page'));
}else {
    AppFunction::errorMessage('Cette page est déjà hors ligne', $router->url('admin.page'));
}