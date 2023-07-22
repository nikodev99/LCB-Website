<?php

use Web\App\Core\AppFunction;
use Web\App\Components\Component;
use Web\App\Core\Model;

AppFunction::user_not_connected($router->url('admin.index'));

$tds = [];
$features = [];

$messages = Model::getAllMessages([], null, [], ['send', 2]);

$i = 1;
foreach($messages as $message) {
    $buttons = [
        Component::removal($router->url('admin.message.delete', ['id' => $message->id]), 'cet article')
    ];
    $tds[] = [
        $i++,
        $message->sender,
        AppFunction::excerpt($message->subject, 40),
        AppFunction::excerpt($message->message, 100),
        AppFunction::stamp($message->send, 'd M Y H:i:s'),
        implode(' ', $buttons),
    ];
    $features[] = [
        'url' => $router->url('admin.message.view', ['id' => $message->id]),
        'bg' => (int) $message->already_read === 0  
    ];
}

?>

<div class="ui grid stackable padded">
    <div class="column">
        <div class="ui fluid card">
            <div class="content">
                <div class="header">Listing des messages</div>
            </div>
            <?= Component::dataTable([
                'th'    =>  [
                    0   =>  ['#', 'NOM', 'SUJET', 'MESSAGE', 'DATE', 'ACTIONS']
                ],
                'td'    =>  $tds
            ], $features) ?>
        </div>
    </div>
</div>