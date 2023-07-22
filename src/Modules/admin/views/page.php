<?php 
use Web\App\Components\Component;
use Web\App\Core\AppFunction;
use Web\App\Core\Model;

AppFunction::user_not_connected($router->url('admin.index'));

$pages = Model::getAllPages([], null, [], ['created', 2]);

$tds = [];
$i = 1;
foreach($pages as $page) {
    $buttons = [
        Component::button(['Créer', 'Modifier'], [
            $router->url('admin.page.constructor', ['slug' => $page->url]),
            $router->url('admin.page.modificator', ['slug' => $page->url])
        ], !is_null($page->content), ['primary', 'positive']),
        Component::form(['Online', 'Offline'], [
            $router->url('admin.page.online', ['slug' => $page->url, 'id' => $page->id]), 
            $router->url('admin.page.offline', ['slug' => $page->url, 'id' => $page->id])
        ], (int) $page->online === 1, ['positive', 'negative']),
        Component::button(['Meta-Info'], [$router->url('admin.metadata', ['slug' => $page->url, 'id' => $page->id])], false, ['teal'])
    ];
    $update = !is_null($page->updated) ? AppFunction::stamp($page->updated) : null;
    $tds[] = [
        $i++, 
        $page->url,
        $page->content, 
        AppFunction::online($page->online), 
        AppFunction::stamp($page->created),
        $update, 
        implode('', $buttons)
    ];
}

?>

<div class="ui grid stackable padded">
    <div class="column">
        <div class="ui fluid card">
            <div class="content">
                <div class="header">Listing des pages</div>
            </div>
            <?= Component::dataTable([
                'th'    =>  [
                    0   =>  ['#', 'LIEN', 'CONTENU', 'EN LIGNE', 'CREATION', 'MISE A JOUR', 'ACTIONS']
                ],
                'td'    =>  $tds
            ]) ?>
        </div>
    </div>
</div>