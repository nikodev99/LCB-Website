<?php

use Web\App\Core\Flash;
use Web\App\Core\Model;
use Web\App\Core\AppFunction;
use Web\App\Core\Route\Route;

AppFunction::user_not_connected($router->url('admin.index'));

$flash = new Flash();
$pages = Model::getAllPages(['navbar_id' => $params['id']]);
Model::deleteMenu([
    'id'    =>  $params['id'],
]);
foreach($pages as $page) {
    Model::deletePage([
        'url'   =>  $page->url,
        'navbar_id'    =>  $params['id']
    ]);
}
$flash->success("Item supprimer avec succès");
Route::redirect($router->url('admin.menu'));