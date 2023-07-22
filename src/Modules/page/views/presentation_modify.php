<?php

use Web\App\Core\Model;
use Web\App\Core\AppFunction;
use Web\App\Components\Component;

AppFunction::user_not_connected($router->url('admin.index'));

$redirect_url = $router->url('page.index', ['slug' => $params['slug']]);

$tds = [];
$condition = ['url' => $params['slug'], 'url_ang' => $params['slug']];
$packs = Model::getAllPresentation($condition);

$i = 1;
foreach($packs as $tab) {
    $buttons = [
        Component::button(['Modifier'], [
            $router->url('page.modify.presentation', ['id' => $tab->id, 'slug' => $tab->url])
        ], false, [], ['edit']),
        Component::removal($router->url('page.presentation.remove', ['id' => $tab->id, 'slug' => $tab->url]), 'ce bloc')
    ];
    $tds[] = [
        '#' . $i++,
        $tab->title,
        $tab->title_ang,
        '<img src="'.AppFunction::getImgRoute($tab->image).'"  width="100" height="100">',
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
                <?= Component::ui_modify_anchor($router->url('page.add.presentation', ['slug' => $params['slug']]), 'Ajouter un nouveau item', ['icon' => 'plus circle', 'color' => 'positive']) ?>
            </div>
            <div class="content">
                <div class="header">Listing</div>
            </div>
            <?= Component::dataTable([
                'th'    =>  [
                    0   =>  ['#', 'TITRE', 'TITRE EN ANGLAIS', 'IMAGE', 'ACTIONS']
                ],
                'td'    =>  $tds
            ]) ?>
        </div>
    </div>
</div>