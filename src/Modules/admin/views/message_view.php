<?php

use Web\App\Core\AppFunction;
use Web\App\Core\Model;

AppFunction::user_not_connected($router->url('admin.index'));

$message = Model::getAMessage(['id' => $params['id']]);
if((int)$message->already_read !== 1) {
    Model::updateMessage(['id' => $params['id']], [
        'already_read' => 1
    ]);
}

?>

<div class="ui grid stackable padded">
    <div class="column">
        <div class="ui fluid card">
            <div class="content">
                <div class="header"><?= $message->sender ?? null ?></div>
            </div>
            <div class="content">
                <div class="header">Sujet: <?= $message->subject ?? null ?></div>
            </div>
            <div class="meta">
                email: <?= $message->email ?? null ?>
            </div>
            <div class="meta">
                date: <?= AppFunction::stamp($message->send, 'd M Y à H:i:s') ?? null ?>
            </div>
            <div class="description">
                pouvez vous me dire comment ouvrir un compte à LCB Bank ?
            </div>
            <div class="extra content">
                <div class="ui three buttons">
                    <a href="#" class="ui basic green button">Répondre</a>
                    <a href="#" class="ui basic violet button">Imprimé</a>
                    <a href="#" class="ui basic red button">Supprimé</a>
                </div>
            </div>
        </div>
    </div>
</div>