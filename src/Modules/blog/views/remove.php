<?php

use Web\App\Core\AppFunction;
use Web\App\Core\Model;

AppFunction::user_not_connected($router->url('admin.index'));

$condition = ['id' => $params['id']];

$post = Model::getPost($condition);
AppFunction::unlinking($post->image);
AppFunction::unlinking($post->thumbnail);
AppFunction::unlinking($post->breadcrumb);

Model::deletePost($condition);
AppFunction::successMessage('L\article à été supprimé avec succès !', $router->url('admin.posts'));