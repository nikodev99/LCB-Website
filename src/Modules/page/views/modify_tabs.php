<?php

use Web\App\Core\Model;
use Web\App\Core\AppFunction;
use Web\App\Components\Component;

AppFunction::user_not_connected($router->url('admin.index'));

$redirect_url = $router->url('page.index', ['slug' => $params['slug']]);

$tds = [];

$tabs = Model::getAllTabs(['url' => $params['slug'], 'url_ang' => $params['slug']]);
$i = 1;
foreach($tabs as $tab) {
    $buttons = [
        Component::button(['Modifier'], [
            $router->url('page.tabs.modify', ['id' => $tab->id, 'slug' => $tab->url]),
        ], false),
        Component::removal($router->url('page.tab.remove', ['id' => $tab->id, 'slug' => $tab->url]), 'ce bloc')
    ];
    $tds[] = [
        '#' . $i++,
        $tab->tab_title,
        $tab->tab_title_ang,
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
                <?= Component::ui_modify_anchor($router->url('page.tab.add', ['slug' => $params['slug']]), 'Ajouter un nouveau bloc') ?>
            </div>
            <div class="content">
                <div class="header">Listing des tabs contenant la page</div>
            </div>
            <?= Component::dataTable([
                'th'    =>  [
                    0   =>  ['#', 'TITRE', 'TITRE EN ANGLAIS', 'ACTIONS']
                ],
                'td'    =>  $tds
            ]) ?>
        </div>
    </div>
</div><!--  -->