<?php

use Web\App\Core\Flash;
use Web\App\Core\Model;
use Web\App\Components\Form;
use Web\App\Core\AppFunction;
use Web\App\Core\session\Post;
use Web\App\Components\Component;

AppFunction::user_not_connected($router->url('admin.index'));
$p = new Post();

$acc = Model::getAccordion(['url' => $params['slug'], 'id' => $params['id']]);

$redirect_url = $router->url('page.modify.accordion', ['slug' => $params['slug']]);

$flash = new Flash();

$errors = [];

if (!$p->isEmpty()) {

    $title = $p->get('title');
    $title_ang = $p->get('title_ang');
    $control = $p->get('control');
    $content = $p->get('content');
    $content_ang = $p->get('content_ang');

    foreach($p->get() as $key => $value) {
        if ($p->isEmpty($key)) $errors[$key] = 'Ce champ est vide';
    }

    if (empty($errors)) {
        Model::updateAccordion(['url' => $params['slug'], 'id' => $params['id']], [
            'title' =>  $title,
            'title_ang' =>  $title_ang,
            'content'   =>  $content,
            'content_ang'   =>  $content_ang,
            'control'   =>  str_replace(' ', '-', $control),
        ]);
        AppFunction::successMessage("Nouvel element enregistrer avec success", $redirect_url);
    }else {
        $flash->error('Erreur rencontrer lors de l\'neregistrement du nouveau élément');
    }

}

?>

<div class="ui grid stackable padded">
    <div class="column">
        <?= Component::back_button($redirect_url) ?>
        <div class="ui divider"></div>
        <form action="" method="post" enctype="multipart/form-data" class="ui fluid form">
            <h1 class="ui dividing header">Modifiaction d'un élément</h1>
            <div class="could go">
                <div class="two fields">
                    <?= Form::text([
                        'label' =>  "Titre de la section",
                        'name'  =>  'title',
                        'value'    =>  !is_null($acc) ? $acc->title : null
                    ], $errors) ?>
                    <?= Form::text([
                        'label' =>  "Titre de la section en anglais",
                        'name'  =>  'title_ang',
                        'value'    =>  !is_null($acc) ? $acc->title_ang : null
                    ], $errors) ?>
                </div>
            </div>
            <?= Form::text([
                'label' =>  "Controller de l'élement",
                'name'  =>  'control',
                'value'    =>  !is_null($acc) ? $acc->control : null
            ], $errors) ?>
            <div class="could go">
               <div class="two fields">
               <?= Form::textarea([
                        'name'  =>  'content',
                        'value'   =>  !is_null($acc) ? $acc->content : null,
                        'id'    =>  'wysiwyg_accordion'
                    ]) ?>
                    <?= Form::textarea([
                        'name'  =>  'content_ang',
                        'value'   =>  !is_null($acc) ? $acc->content_ang : null,
                        'id'    =>  'wysiwyg_accordion_2'
                    ]) ?>
               </div>
           </div>
           
            <?= Form::button('Modifier') ?>
        </form>
    </div>
</div>