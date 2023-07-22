<?php

use Web\App\Core\Model;
use Web\App\Core\AppFunction;
use Web\App\Components\Component;

AppFunction::user_not_connected($router->url('admin.index'));

$redirect_url = $router->url('page.index', ['slug' => $params['slug']]);

$tds = [];

$accordions = Model::getAllAccordion(['url' => $params['slug'], 'url_ang' => $params['slug']]);
$i = 1;
foreach($accordions as $acc) {
    $buttons = [
        Component::button(['Modifier'], [
            $router->url('page.accordion.modify', ['id' => $acc->id, 'slug' => $acc->url]),
        ], false),
        Component::removal($router->url('page.accordion.remove', ['id' => $acc->id, 'slug' => $acc->url]), 'cet item')
    ];
    $tds[] = [
        '#' . $i++,
        $acc->title,
        $acc->title_ang,
        $acc->control,
        implode(' ', $buttons)
    ];
}

?>

<div class="ui grid stackable padded">
    <div class="column">
        <?= Component::back_button($redirect_url) ?>
        <div class="ui divider"></div>
        <div class="ui fluid card">
            <div class="header">
                <?= Component::ui_modify_anchor($router->url('page.accordion.add', ['slug' => $params['slug']]), 'Ajouter un nouvel élement') ?>
            </div>
            <div class="content">
                <div class="header">Listing des accordions contenant la page</div>
            </div>
            <?= Component::dataTable([
                'th'    =>  [
                    0   =>  ['#', 'TITRE', 'TITRE EN ANGLAIS', 'CONTROLLER', 'ACTIONS']
                ],
                'td'    =>  $tds
            ]) ?>
        </div>
    </div>
</div>