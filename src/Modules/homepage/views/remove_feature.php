<?php

use Web\App\Core\Model;
use Web\App\Core\AppFunction;

AppFunction::user_not_connected($router->url('admin.index'));

$carousel = Model::removeFeature(['id' => $params['id']]);
AppFunction::successMessage("L'annonce a été supprimer avec succes !", $router->url('homepage.modify.feature'));