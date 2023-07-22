<?php

use Web\App\Core\Model;
use Web\App\Core\AppFunction;
use Web\App\Components\Component;

AppFunction::user_not_connected($router->url('admin.index'));

$redirect_url = $router->url('homepage.index');

$tds = [];

$services = Model::getAllServices();
$i = 1;
foreach($services as $service) {
    $buttons = [
        Component::button(['Modifier'], [
            $router->url('homepage.modify.service', ['id' => $service->id]),
        ], false),
        Component::removal($router->url('homepage.delete.service', ['id' => $service->id]), 'ce service')
    ];
    $tds[] = [
        '#' . $i++,
        '<i class="'.$service->icon.'"></i>',
        $service->title,
        $service->title_ang,
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
                <?= Component::ui_modify_anchor($router->url('homepage.add.service'), 'Ajouter un nouveau service', ['icon' => 'plus', 'color' => 'positive']) ?>
            </div>
            <div class="content">
                <div class="header">Listing des services de la LCB BANK</div>
            </div>
            <?= Component::dataTable([
                'th'    =>  [
                    0   =>  ['#', 'ICON', 'TITRE', 'TITRE EN ANGLAIS', 'ACTIONS']
                ],
                'td'    =>  $tds
            ]) ?>
        </div>
    </div>
</div>