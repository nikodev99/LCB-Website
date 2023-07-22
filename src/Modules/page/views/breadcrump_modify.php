<?php

use Web\App\Core\AppFunction;
use Web\App\Core\Model;
use Web\App\Core\Route\Route;
use Web\App\Core\session\Post;
use Web\App\Core\upload\ImgUpload;

AppFunction::user_not_connected($router->url('admin.index'));
$p = new Post();

$breadcrump = Model::getBreadcrumb(['url' => $params['slug'], 'url_ang' => $params['slug']]);

$errors = [];
$redirect_url = $router->url('page.index', ['slug' => $params['slug']]);

$title = !$p->isEmpty('header') ? $p->get('header') : $breadcrump->header;
$title_ang = !$p->isEmpty('header_ang') ? $p->get('header_ang') : $breadcrump->header_ang;
$background = $_FILES['background'] ?? null;

if (!$p->isEmpty() | !empty($_FILES['background']['name'])) {

    if (empty($title) | is_null($title) | empty($title_ang) | is_null($title_ang)) $errors = 'Erreur. Les champs titre en français et en anglais ne peuvent pas être vides.';

    if(empty($errors)) {
        $file_name = !is_null($breadcrump) ? $breadcrump->background : null;
        if (!empty($background['name'])) {
            if (!is_null($file_name)) AppFunction::unlinking($breadcrump->background);
            $img = new ImgUpload($background);
            $file_name = $img->newFilename();
            $img->upload($file_name, $redirect_url, 1920, 1280);
        }
        Model::updateBreadcrumb(['url' => $params['slug'], 'url_ang' => $params['slug']], [
            'header'    =>  $title,
            'header_ang'    =>  $title_ang,
            'background'    =>  $file_name
        ]);
        Route::redirect($redirect_url);
    }else {
        Route::redirect($redirect_url);
    }
}