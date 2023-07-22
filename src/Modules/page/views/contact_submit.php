<?php

use Web\App\Core\AppFunction;
use Web\App\Core\Model;
use Web\App\Core\session\Post;

$errors = [];
$p = new Post();

if (!$p->isEmpty()) {

    $name = $p->get('nom');
    $email = $p->get('email');
    $subject = $p->get('sujet');
    $message = $p->get('message');

    foreach ($p->get() as $key => $value) {
        if ($p->isEmpty($key)) {
            $errors[$key] = 'Le champ ' . $key . ' est réquis. Veuillez à y inserer votre ' . $key;
        }
    }
    if (!empty($email) && filter_var($email, FILTER_VALIDATE_EMAIL) === false) $errors[] = 'Votre email ne ressemble pas à un email traditionnelle';

    if (empty($errors)) {

        Model::insertMessage([
            'sender'    =>  ucwords($name),
            'email'     =>  $email,
            'subject'   =>  ucfirst($subject),
            'message'   =>  $message,
            'send'      =>  time(),
            'read_date' =>  null,
            'already_read'  =>  0,
        ]);

        AppFunction::successMessage('Votre message à bien été envoyé. Nous vous repondrons dans les 24h', $router->url('page.index', ['slug' => $params['slug']]).'#form');

    }else {
        AppFunction::errorMessage($errors, $router->url('page.index', ['slug' => $params['slug']]).'#form');
    }

}
