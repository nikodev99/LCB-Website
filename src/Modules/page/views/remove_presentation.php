<?php

use Web\App\Core\AppFunction;
use Web\App\Core\Model;

AppFunction::user_not_connected($router->url('admin.index'));

$condition = ['id' => $params['id'], 'url' => $params['slug']];
$image = Model::getPresentation($condition, 'image')->image;
AppFunction::unlinking($image);
Model::deletePresentation($condition);
AppFunction::successMessage('Bloc supprimer avec success !', $router->url('page.motify.presentation', ['slug' => $params['slug']]));