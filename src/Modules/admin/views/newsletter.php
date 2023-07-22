<?php

use Web\App\Core\Model;
use Web\App\Core\AppFunction;
use Web\App\Components\Component;

AppFunction::user_not_connected($router->url('admin.index'));

$tds = [];

$posts = Model::getAllNewsletter();
$i = 1;
foreach($posts as $post) {
    $tds[] = [
        '#' . $i++,
        $post->emails,
        AppFunction::stamp($post->subscription_date),
        Component::removal()
    ];
}

?>

<div class="ui grid stackable padded">
    <div class="column">
        <div class="ui fluid card">
            <div class="content">
                <div class="header">Listing des abonnés à la newsletter</div>
            </div>
            <?= Component::dataTable([
                'th'    =>  [
                    0   =>  ['#', 'EMAIL', 'DATE DE SUSCRIPTION', 'ACTIONS']
                ],
                'td'    =>  $tds
            ]) ?>
        </div>
    </div>
</div>