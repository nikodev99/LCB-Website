<?php

use Web\App\Core\AppFunction;
use Web\App\Core\Model;

AppFunction::user_not_connected($router->url('admin.index'));

Model::deleteAccodrion(['id' => $params['id'], 'url' => $params['slug']]);
AppFunction::successMessage('Element supprimer avec success !', $router->url('page.modify.accordion', ['slug' => $params['slug']]));