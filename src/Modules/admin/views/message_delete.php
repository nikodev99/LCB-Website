<?php

use Web\App\Core\Model;
use Web\App\Core\AppFunction;

AppFunction::user_not_connected($router->url('admin.index'));

Model::deleteMessage([
    'id'    =>  $params['id'],
]);
AppFunction::successMessage('Message effacer avec succès!', $router->url('admin.message'));