<?php

use Web\App\Core\Flash;
use Web\App\Core\Model;
use Web\App\Components\Form;
use Web\App\Core\AppFunction;
use Web\App\Core\session\Post;
use Web\App\Components\Component;

AppFunction::user_not_connected($router->url('admin.index'));
$p = new Post();

$redirect_url = $router->url('page.modify.tabs', ['slug' => $params['slug']]);

$tab = Model::getTabs(['url' => $params['slug'], 'id' => $params['id']]);

$flash = new Flash();

$errors = [];

if (!$p->isEmpty()) {

    $title = $p->get('title');
    $title_ang = $p->get('title_ang');
    $content = $p->get('content');
    $content_ang = $p->get('content_ang');

    foreach($p->get() as $key => $value) {
        if ($p->isEmpty($key)) $errors[$key] = 'Ce champ est vide. Veuillez le remplir';
    }

    if (empty($errors)) {
        Model::updateTabs(['url' => $params['slug'], 'id' => $params['id']], [
            'tab_title' =>  $title,
            'tab_title_ang' =>  $title_ang,
            'tab_content'   =>  $content,
            'tab_content_ang'   =>  $content_ang,
        ]);
        AppFunction::successMessage("Modification réussi", $redirect_url);
    }else {
        $flash->error('Erreur rencontrer lors de la modification du bloc');
    }

}

?>

<div class="ui grid stackable padded">
    <div class="column">
        <?= Component::back_button($redirect_url) ?>
        <div class="ui divider"></div>
        <form action="" method="post" enctype="multipart/form-data" class="ui fluid form">
            <h1 class="ui dividing header">Modifiaction d'un bloc</h1>
            <div class="could go">
                <div class="two fields">
                    <?= Form::text([
                        'label' =>  "Titre de la section",
                        'name'  =>  'title',
                        'value'    =>  !is_null($tab) ? $tab->tab_title : null
                    ], $errors) ?>
                    <?= Form::text([
                        'label' =>  "Titre de la section en anglais",
                        'name'  =>  'title_ang',
                        'value'    =>  !is_null($tab) ? $tab->tab_title_ang : null
                    ], $errors) ?>
                </div>
            </div>
            <div class="could go">
               <div class="two fields">
               <?= Form::textarea([
                        'name'  =>  'content',
                        'value'   =>  !is_null($tab) ? $tab->tab_content : null,
                        'id'    =>  'wysiwyg_accordion'
                    ]) ?>
                    <?= Form::textarea([
                        'name'  =>  'content_ang',
                        'value'   =>  !is_null($tab) ? $tab->tab_content_ang : null,
                        'id'    =>  'wysiwyg_accordion_2'
                    ]) ?>
               </div>
           </div>
           
            <?= Form::button('Modifier') ?>
        </form>
    </div>
</div>