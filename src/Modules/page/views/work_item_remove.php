<?php

use Web\App\Core\AppFunction;
use Web\App\Core\Model;

AppFunction::user_not_connected($router->url('admin.index'));
$condition = ['id' => $params['id'], 'url' => $params['slug']];
$w = Model::getAllWork($condition);
if (empty($w)) Model::deleteTitle($condition);
Model::deleteWork($condition);
AppFunction::successMessage('Item supprimer avec success !', $router->url('page.modify.work', ['slug' => $params['slug']]));