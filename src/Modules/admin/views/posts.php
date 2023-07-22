<?php

use Web\App\Core\Model;
use Web\App\Core\AppFunction;
use Web\App\Components\Component;

AppFunction::user_not_connected($router->url('admin.index'));

$tds = [];

$posts = Model::getAllPosts([], null, [], ['date', 2]);
$i = 1;
foreach($posts as $post) {
    $buttons = [
        Component::button(['Modifier'], [$router->url('post.modify', ['id' => $post->id])], false),
        Component::form(['Online', 'Offline'], [
            $router->url('post.online', ['id' => $post->id]),
            $router->url('post.offline', ['id' => $post->id])
        ], (int) $post->online === 1, ['positive', 'negative']),
        Component::removal($router->url('post.remove', ['id' => $post->id]), 'cet article')
    ];
    $update = !is_null($post->updated) ? AppFunction::stamp($post->updated) : null;
    $tds[] = [
        $i++,
        AppFunction::excerpt($post->title, 40),
        AppFunction::excerpt($post->title_ang, 40),
        AppFunction::stamp($post->date),
        $update,
        AppFunction::online($post->online),
        implode(' ', $buttons)
    ];
}

?>

<div class="ui grid stackable padded">
    <div class="column">
        <div class="ui fluid card">
            <div class="header">
                <?= Component::ui_modify_anchor($router->url('blog.index'), 'Ajouter un nouvel article') ?>
            </div>
            <div class="content">
                <div class="header">Listing des articles</div>
            </div>
            <?= Component::dataTable([
                'th'    =>  [
                    0   =>  ['#', 'TITRE', 'TITRE EN ANGLAIS', 'DATE DE CREATION', 'MISE A JOUR', 'MISE EN LIGNE', 'ACTIONS']
                ],
                'td'    =>  $tds
            ]) ?>
        </div>
    </div>
</div>