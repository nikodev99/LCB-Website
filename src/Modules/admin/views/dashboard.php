<?php

use Web\App\Components\Component;
use Web\App\Components\PageRender;
use Web\App\Core\AppFunction;
use Web\App\Core\Model;

AppFunction::user_not_connected($router->url('admin.index'));

$page = new PageRender();
$menu = Model::getAllMenuItem('id');
$pages = Model::getAllPages([], 'id');
$posts = Model::getAllPosts([], 'id');
$newsletter = Model::getAllNewsletter('id');

?>


<div class="ui grid stackable padded">
    <?= $page->getGrid([
        0   =>  [
            'icon'  =>  'laptop',
            'stat'  =>  count($menu),
            'title' =>  'Elements du Menu',
            'url'   =>  $router->url('admin.menu'),
            "color" =>  '#79b543',
            'description'   =>  '',
            'button'    =>  'Consulter'
        ],
        1   =>  [
            'icon'  =>  'linkify',
            'stat'  =>  count($pages),
            'title' =>  'Liens de Pages',
            'url'   =>  $router->url('admin.page'),
            "color" =>  '#454b52',
            'description'   =>  '',
            'button'    =>  'Consulter'
        ],
        2   =>  [
            'icon'  =>  'chart pie',
            'stat'  =>  count($posts),
            'title' =>  'Articles',
            'url'   =>  $router->url('admin.posts'),
            "color" =>  '#79b543',
            'description'   =>  '',
            'button'    =>  'Consulter'
        ],
        3   =>  [
            'icon'  =>  'tasks',
            'stat'  =>  count($newsletter),
            'title' =>  'Abonnés à la newsletter',
            'url'   =>  $router->url('admin.newsletter'),
            "color" =>  '#454b52',
            'description'   =>  '',
            'button'    =>  'Consulter'
        ]
    ]) ?>
</div>
<div class="ui grid stackable padded">
    <div class="column">
        <div class="ui fluid card">
            <div class="content">
                <div class="header">Listing des articles</div>
            </div>
            <?= Component::dataTable([
                'th'    =>  [
                    0   =>  ['Article', 'Titre', 'Action']
                ],
                'td'    =>  [
                    0   =>  ['Initial Commit', 'Second Commit', 'Third Commit'],
                    1   =>  ['Fourth Commit', 'Fith Commit', 'sixth commit']
                ]
            ]) ?>
        </div>
    </div>
</div>