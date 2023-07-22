<?php

use Web\App\Core\Flash;
use Web\App\Core\Model;
use Web\App\Components\Form;
use Web\App\Core\AppFunction;
use Web\App\Components\Component;
use Web\App\Core\session\Post;

AppFunction::user_not_connected($router->url('admin.index'));
$p = new Post();

$flash = new Flash();
$redirect_url = $router->url('page.modify.work', ['slug' => $params['slug']]);
$errors = [];

if (!$p->isEmpty()) {

    $title = !$p->isEmpty('title') ? $p->get('title') : null;
    $title_ang = !$p->isEmpty('title_ang') ? $p->get('title_ang') : null;
    $content = !$p->isEmpty('content') ? $p->get('content') : null;
    $content_ang =!$p->isEmpty('content_ang') ? $p->get('content_ang') : null;

    if (is_null($title)) $errors['title'] = 'Ce champ est vide. Veuillez le remplir';
    if (is_null($title_ang)) $errors['title_ang'] = 'Ce champ est vide. Veuillez le remplir';
    if (!is_null($content) && strlen($content) > 150) $errors['content'] = 'Ce champ ne peut avoir plus de 150 caractères';
    if (!is_null($content_ang) && strlen($content_ang) > 150) $errors['content_ang'] = 'Ce champ ne peut avoir plus de 150 caractères';

    if (empty($errors)) {
        $url = Model::getPage(['url' => $params['slug'], 'url_ang' => $params['slug']], null, ['id', 'url', 'url_ang']);
        $tab = Model::getWork(['title' => $title]);
        if (is_null($tab)) {
            Model::insertWork([
                'title' =>  $title,
                'title_ang' =>  $title_ang,
                'description'   =>  $content,
                'description_ang'   =>  $content_ang,
                'icon'      => null,
                'url'       =>  $url->url,
                'url_ang'   =>  $url->url_ang,
                'url_id'    =>  $url->id
            ]);
            AppFunction::successMessage("Nouvel item enregistrer avec success", $redirect_url);
        }
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
            <h1 class="ui dividing header">Ajout d'un nouveau item</h1>
            <div class="could go">
                <div class="two fields">
                    <?= Form::text([
                        'label' =>  "Titre de l'item",
                        'name'  =>  'title',
                        'placeholder'    =>  'Entrer le titre de l\'item',
                        'value' =>  $p->get('title')
                    ], $errors) ?>
                    <?= Form::text([
                        'label' =>  "Titre de l'item en anglais",
                        'name'  =>  'title_ang',
                        'placeholder'    =>  'Entrer le titre de l\'item en anglais',
                        'value' =>  $p->get('title_ang')
                    ], $errors) ?>
                </div>
            </div>
            <div class="could go">
               <div class="two fields">
                    <?= Form::textarea([
                        'name'  =>  'content',
                        'label'   =>  'Entrer une description de l\'item',
                        'value' =>  $p->get('content')
                    ], $errors) ?>
                    <?= Form::textarea([
                        'name'  =>  'content_ang',
                        'label'   =>  'Entrer une description de l\'item en anglais',
                        'value' => $p->get('content_ang')
                    ], $errors) ?>
               </div>
           </div>
           
            <?= Form::button('Ajouter') ?>
        </form>
    </div>
</div>