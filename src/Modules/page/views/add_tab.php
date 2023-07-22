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

$flash = new Flash();

$errors = [];

if (!$p->isEmpty()) {

    $title = $p->get('title');
    $title_ang = $p->get('title_ang');
    $content = $p->get('content');
    $content_ang = $p->get('content_ang');

    foreach($p->get() as $key => $value) {
        if ($p->isEmpty($key)) $errors[$key] = 'Ce champ est vide';
    }

    if (empty($errors)) {
        $url = Model::getPage(['url' => $params['slug'], 'url_ang' => $params['slug']], null, ['id', 'url', 'url_ang']);
        $tab = Model::getTabs(['tab_title' => $title]);
        if (is_null($tab)) {
            Model::insertTabs([
                'tab_title' =>  $title,
                'tab_title_ang' =>  $title_ang,
                'tab_content'   =>  $content,
                'tab_content_ang'   =>  $content_ang,
                'url'       =>  $url->url,
                'url_ang'   =>  $url->url_ang,
                'url_id'    =>  $url->id
            ]);
            AppFunction::successMessage("Nouvel element enregistrer avec success", $redirect_url);
        }
    }else {
        $flash->error('Erreur rencontrer lors de l\'enregistrement du nouveau élément');
    }

}

?>

<div class="ui grid stackable padded">
    <div class="column">
        <?= Component::back_button($redirect_url) ?>
        <div class="ui divider"></div>
        <form action="" method="post" enctype="multipart/form-data" class="ui fluid form">
            <h1 class="ui dividing header">Ajout d'un nouveau bloc</h1>
            <div class="could go">
                <div class="two fields">
                    <?= Form::text([
                        'label' =>  "Titre du bloc. Eviter les apostrophes dans le titre",
                        'name'  =>  'title',
                        'placeholder'    =>  'Entrer le titre du bloc'
                    ], $errors) ?>
                    <?= Form::text([
                        'label' =>  "Titre du bloc en anglais. Eviter les apostrophes dans le titre",
                        'name'  =>  'title_ang',
                        'placeholder'    =>  'Entrer le titre du bloc en anglais'
                    ], $errors) ?>
                </div>
            </div>
            <div class="could go">
               <div class="two fields">
               <?= Form::textarea([
                        'name'  =>  'content',
                        'placeholder'   =>  'Entrer le contenu de l\'élement',
                        'id'    =>  'wysiwyg_accordion'
                    ]) ?>
                    <?= Form::textarea([
                        'name'  =>  'content_ang',
                        'placeholder'   =>  'Entrer le contenu de l\'élement en anglais',
                        'id'    =>  'wysiwyg_accordion_2'
                    ]) ?>
               </div>
           </div>
           
            <?= Form::button('Ajouter') ?>
        </form>
    </div>
</div>