<?php

use Web\App\Components\Form;
use Web\App\Core\AppFunction;
use Web\App\Core\Flash;
use Web\App\Core\Model;
use Web\App\Core\session\Post;

AppFunction::user_not_connected($router->url('admin.index'));
$p = new Post();

$flash = new Flash();

$errors = [];

$title = $p->get('title');
$title_ang = $p->get('title_ang');
$control = $p->get('control');
$content = $p->get('content');
$content_ang = $p->get('content_ang');

if (!$p->isEmpty()) {

    foreach($p->get() as $key => $value) {
        if ($p->isEmpty($key)) $errors[$key] = 'Ce champ est vide';
    }

    if (empty($errors)) {
        $url = Model::getPage(['url' => $params['slug'], 'url_ang' => $params['slug']], null, ['id', 'url', 'url_ang']);
        $acc = Model::getAccordion(['title' => $title]);
        if (is_null($acc)) {
            Model::insertAccordion([
                'title' =>  $title,
                'title_ang' =>  $title_ang,
                'content'   =>  $content,
                'content_ang'   =>  $content_ang,
                'control'   =>  str_replace(' ', '-', $control),
                'url'       =>  $url->url,
                'url_ang'   =>  $url->url_ang,
                'url_id'    =>  $url->id
            ]);
            AppFunction::successMessage("Nouvel element enregistrer avec success", $router->url('page.modify.accordion', ['slug' => $params['slug']]));
        }
    }else {
        $flash->error('Erreur rencontrer lors de l\'neregistrement du nouveau élément');
    }

}

?>

<div class="ui grid stackable padded">
    <div class="column">
        <form action="" method="post" enctype="multipart/form-data" class="ui fluid form">
            <h1 class="ui dividing header">Ajout d'un element</h1>
            <div class="could go">
                <div class="two fields">
                    <?= Form::text([
                        'label' =>  "Titre de la section",
                        'name'  =>  'title',
                        'placeholder'    =>  'Entrer le titre de l\'élement',
                        'value' =>  $title
                    ], $errors) ?>
                    <?= Form::text([
                        'label' =>  "Titre de la section en anglais",
                        'name'  =>  'title_ang',
                        'placeholder'    =>  'Entrer le titre de l\'élement en anglais',
                        'value' =>  $title_ang
                    ], $errors) ?>
                </div>
            </div>
            <?= Form::text([
                'label' =>  "Controller de l'élement",
                'name'  =>  'control',
                'placeholder'    =>  'Entrer le controller de l\'element',
                'value' =>  $control
            ], $errors) ?>
            <div class="could go">
               <div class="two fields">
               <?= Form::textarea([
                        'name'  =>  'content',
                        'placeholder'   =>  'Entrer le contenu de l\'élement',
                        'id'    =>  'wysiwyg_accordion',
                        'value' =>  $content
                    ]) ?>
                    <?= Form::textarea([
                        'name'  =>  'content_ang',
                        'placeholder'   =>  'Entrer le contenu de l\'élement en anglais',
                        'id'    =>  'wysiwyg_accordion_2',
                        'value' =>  $content_ang
                    ]) ?>
               </div>
           </div>
           
            <?= Form::button('Ajouter') ?>
        </form>
    </div>
</div>