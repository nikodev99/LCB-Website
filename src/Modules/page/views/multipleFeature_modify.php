<?php

use Web\App\Core\Model;
use Web\App\Core\AppFunction;
use Web\App\Components\Component;
use Web\App\Core\Request;
use Web\App\Core\session\Session;

AppFunction::user_not_connected($router->url('admin.index'));

$redirect_url = $router->url('page.index', ['slug' => $params['slug']]);

$s = new Session();
$s->set('redirect', Request::getRequest());

$tds = [];
$condition = ['url' => $params['slug'], 'url_ang' => $params['slug']];
$packs = Model::getMultipleFeatures($condition);
$work_title = Model::getTitle($condition);

$i = 1;
foreach($packs as $tab) {
    $buttons = [
        Component::button(['Modifier'], [
            $router->url('page.multipleFeature.modify', ['id' => $tab->id, 'slug' => $tab->url])
        ], false, [], ['edit']),
        Component::removal($router->url('page.multipleFeauture.remove', ['id' => $tab->id, 'slug' => $tab->url]), 'ce bloc')
    ];
    $tds[] = [
        '#' . $i++,
        $tab->title,
        $tab->title_ang,
        '<img src="'.AppFunction::getImgRoute($tab->image).'"  width="100" height="100">',
        AppFunction::excerpt($tab->description, 50),
        AppFunction::excerpt( $tab->description_ang, 50),
        implode(' ', $buttons)
    ];
}

?>

<div class="ui grid stackable padded">
    <div class="column">
        <?= Component::back_button($redirect_url) ?>
        <?= AppFunction::multipleURL([
            Component::button(['Modifier le titre du bloc'], [$router->url('page.motify.title', ['slug' => $params['slug']])], false, [], ['edit']),
            Component::button(['Ajouter un titre au bloc'], [$router->url('page.add.title', ['slug' => $params['slug']])], false, ['purple'], ['plus circle'])   
        ], is_null($work_title)) ?>
        <div class="ui divider"></div>
        <div class="ui fluid card">
            <div class="header">
                <?= Component::ui_modify_anchor($router->url('page.add.multipleFeature', ['slug' => $params['slug']]), 'Ajouter un nouveau item', ['icon' => 'plus circle', 'color' => 'positive']) ?>
            </div>
            <div class="content">
                <div class="header">Listing</div>
            </div>
            <?= Component::dataTable([
                'th'    =>  [
                    0   =>  ['#', 'TITRE', 'TITRE EN ANGLAIS', 'IMAGE', 'DESCRIPTION', 'DESCRIPTION EN ANGLAIS', 'ACTIONS']
                ],
                'td'    =>  $tds
            ]) ?>
        </div>
    </div>
</div>