<?php

use Whoops\Run;
use Web\App\WebApp;
use App\Blog\BlogModule;
use App\admin\AdminModule;
use Web\App\Core\PHPViewRender;
use App\homepage\HomePageModule;
use App\page\PagesModule;
use Whoops\Handler\PrettyPageHandler;

require "../vendor/autoload.php";

$whoops = new Run();
$whoops->pushHandler(new PrettyPageHandler);
$whoops->register();

define('SQLITE_PATH', dirname(__DIR__));

$renderer = new PHPViewRender();

$app = new WebApp([
    AdminModule::class,
    HomePageModule::class,
    PagesModule::class,
    BlogModule::class
], [
    'renderer' => $renderer
]);

$app->run();