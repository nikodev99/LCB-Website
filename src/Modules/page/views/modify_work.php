<?php

use Web\App\Core\Model;
use Web\App\Core\AppFunction;
use Web\App\Components\Component;
use Web\App\Core\Request;
use Web\App\Core\session\Session;

AppFunction::user_not_connected($router->url('admin.index'));
$s = new Session();
$s->set('redirect', Request::getRequest());

$tds = [];
$condition = ['url' => $params['slug'], 'url_ang' => $params['slug']];
$work = Model::getAllWork($condition);
$work_title = Model::getTitle($condition);

$i = 1;
foreach($work as $tab) {
    $buttons = [
        Component::button(['Modifier'], [
            $router->url('page.work.modify', ['id' => $tab->id, 'slug' => $tab->url]),
        ], false),
        Component::removal($router->url('page.work.remove', ['id' => $tab->id, 'slug' => $tab->url]), 'ce bloc')
    ];
    $tds[] = [
        '#' . $i++,
        $tab->title,
        $tab->title_ang,
        AppFunction::excerpt($tab->description, 50),
        AppFunction::excerpt( $tab->description_ang, 50),
        implode(' ', $buttons)
    ];
}

?>

<div class="ui grid stackable padded">
    <div class="column">
        <?= Component::back_button($router->url('page.index', ['slug' => $params['slug']])) ?>
        <div class="ui divider"></div>
        <div class="header">
            <h2>Titre du bloc: <strong style="text-decoration: underline;"><?= !is_null($work_title) ?$work_title->title : null ?></strong></h2>
            <?php if (is_null($work_title)): ?>
                <?= Component::button(['Ajouter un titre au bloc'], [$router->url('page.add.title', ['slug' => $params['slug']])], false, ['positive'], ['plus circle']) ?>
            <?php endif ?>
            <?= Component::button(['Modifier le titre du bloc'], [$router->url('page.motify.title', ['slug' => $params['slug']])], false, [], ['edit']) ?>
        </div>
        <div class="ui fluid card">
            <div class="header">
                <?= Component::ui_modify_anchor($router->url('page.work.add', ['slug' => $params['slug']]), 'Ajouter un nouveau item') ?>
            </div>
            <div class="content">
                <div class="header">Listing</div>
            </div>
            <?= Component::dataTable([
                'th'    =>  [
                    0   =>  ['#', 'TITRE', 'TITRE EN ANGLAIS', 'DESCRIPTION', 'DESCRIPTION EN ANGLAIS', 'ACTIONS']
                ],
                'td'    =>  $tds
            ]) ?>
        </div>
    </div>
</div>