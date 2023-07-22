<?php

use Web\App\Core\AppFunction;
use Web\App\Core\Model;
use Web\App\Core\session\Post;

$errors = [];
$p = new Post();

if(!$p->isEmpty()) {

    $email = $p->get('nl-email') ?? null;

    $nl = Model::getNewsletter(['emails' => $email]);
    if (filter_var($email, FILTER_VALIDATE_EMAIL) === false) $errors[] = AppFunction::langVerify() ? "Your email didn't looks like an email" :"L'email rentrer ne ressemble pas à un email";
    if (!is_null($nl)) $errors[] = AppFunction::langVerify() ? "You email already exists in the system" :"L'email rentrer existe déjà dans le system";
    
    if(empty($errors)) {

        Model::insertNewsletter([
            'emails'    =>  $email
        ]);
        $message = AppFunction::langVerify() ? "Thank you. Your email has been added to the newsletter" : 'Merci. Votre email à bien été ajouté au newsletter';
        AppFunction::successMessage($message, AppFunction::multipleURL([
            $router->url('homepage.fr.newsletter'),
            $router->url('homepage.en.newsletter')
        ], AppFunction::langVerify()));
    }
    AppFunction::errorMessage($errors, AppFunction::multipleURL([
        $router->url('homepage.fr.newsletter'),
        $router->url('homepage.en.newsletter')
    ], AppFunction::langVerify()));
}