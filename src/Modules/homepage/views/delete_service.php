<?php

use Web\App\Core\AppFunction;
use Web\App\Core\Model;

AppFunction::user_not_connected($router->url('admin.index'));

Model::deleteService(['id' => $params['id']]);
AppFunction::successMessage('Service supprimer avec success !', $router->url('homepage.service'));