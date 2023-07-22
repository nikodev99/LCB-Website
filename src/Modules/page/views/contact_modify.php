<?php

use Web\App\Core\Model;
use Web\App\Core\AppFunction;
use Web\App\Core\Route\Route;
use Web\App\Core\session\Post;

AppFunction::user_not_connected($router->url('admin.index'));
$p = new Post();

$contact = Model::getContact(['url' => $params['slug'], 'url_ang' => $params['slug']]);

$errors = [];
$redirect_url = $router->url('page.index', ['slug' => $params['slug']]);

if (!$p->isEmpty()) {
    $description = $p->get('description') ?? $contact->description;
    $description_ang = $p->get('description_ang') ?? $contact->description_ang;
    $address = $p->get('address') ?? $contact->address;
    $tel = $p->get('tel') ?? $contact->tel;
    $email = $p->get('email') ?? $contact->email;

    if (filter_var($p->get('email'), FILTER_VALIDATE_EMAIL) === false) $errors['email'] = 'error found';

    if(empty($errors)) {
        Model::updateMetadata(['url' => $params['slug'], 'url_ang' => $params['slug']], [
            'title' =>  'Nous contacter',
            'title_ang' =>  'Contact us',
            'description'   =>  $description,
            'description_ang'   =>  $description,
        ]);
        Model::updateContact(['url' => $params['slug'], 'url_ang' => $params['slug']], [
            'description'   =>  $description,
            'description_ang'   =>  $description_ang,
            'address'   =>  $address,
            'tel'   => '(+242) ' . $tel,
            'email' =>  $email
        ]);
        Route::redirect($redirect_url);
    }else {
        Route::redirect($redirect_url);
    }
}