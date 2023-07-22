<?php

use Web\App\Core\Logged;
use Web\App\Core\AppFunction;

AppFunction::user_not_connected($router->url('admin.index'));
$logged = new Logged();
$logged->removeCookie('auth');
$logged->getLoggedOut($router->url('admin.index'));