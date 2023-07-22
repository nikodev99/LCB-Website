<?php

use Web\App\Core\Model;
use Web\App\Core\AppFunction;

AppFunction::user_not_connected($router->url('admin.index'));
$condition = ['id' => $params['id']];

$carousel = Model::getCarousel($carousel);
AppFunction::unlinking($carousel->image);

$carousel = Model::removeCarousel($condition);
AppFunction::successMessage("L'annonce a été supprimer avec succes !", $router->url('homepage.modify.carousel'));