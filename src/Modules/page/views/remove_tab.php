<?php

use Web\App\Core\AppFunction;
use Web\App\Core\Model;

AppFunction::user_not_connected($router->url('admin.index'));

Model::deleteTab(['id' => $params['id'], 'url' => $params['slug']]);
AppFunction::successMessage('Bloc supprimer avec success !', $router->url('page.modify.tabs', ['slug' => $params['slug']]));