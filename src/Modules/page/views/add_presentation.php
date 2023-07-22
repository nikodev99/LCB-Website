<?php

use Web\App\Core\Flash;
use Web\App\Core\Model;
use Web\App\Components\Form;
use Web\App\Core\AppFunction;
use Web\App\Core\session\Post;
use Web\App\Components\Component;
use Web\App\Core\upload\ImgUpload;

AppFunction::user_not_connected($router->url('admin.index'));
$p = new Post();

$flash = new Flash();
$redirect_url = $router->url('page.motify.presentation', ['slug' => $params['slug']]);
$errors = [];

$title = !$p->isEmpty('title') ? $p->get('title') : null;
$title_ang = !$p->isEmpty('title_ang') ? $p->get('title_ang') : null;
$content = !$p->isEmpty('content') ? $p->get('content') : null;
$content_ang =!$p->isEmpty('content_ang') ? $p->get('content_ang') : null;
$image = $_FILES['presentation_img'] ?? null;

if (!$p->isEmpty()) {

    $content = str_replace('<ul>', '<ul class="check-list">', $content);
    $content_ang = str_replace('<ul>', '<ul class="check-list">', $content_ang);

    if (is_null($title)) $errors['title'] = 'Ce champ est vide. Veuillez le remplir';
    if (is_null($title_ang)) $errors['title_ang'] = 'Ce champ est vide. Veuillez le remplir';
    if (is_null($content)) $errors['content'] = 'Ce champ est vide. Veuillez le remplir';
    if (is_null($content_ang)) $errors['content_ang'] = 'Ce champ est vide. Veuillez le remplir';
    if(empty($image['name'])) $errors[] = 'Erreur. Un bloc doit être accompagné d\'une image. Pensez à uploader une';

    if (empty($errors)) {
        $file_name = null;
        if (!empty($image['name'])) {
            $img = new ImgUpload($image);
            $file_name = $img->newFilename();
            $img->upload($file_name, $redirect_url);
        }
        $p = Model::getPage(['url' => $params['slug'], 'url_ang' => $params['slug']], null, ['id', 'url', 'url_ang']);
        $tab = Model::getPresentation(['title' => $title, 'url_id' => $p->id]);
        if (is_null($tab)) {
            Model::insertPresentation([
                'image' =>  $file_name,
                'title' =>  $title,
                'title_ang' =>  $title_ang,
                'content'   =>  $content,
                'content_ang'   =>  $content_ang,
                'url'   =>  $p->url,
                'url_ang'   =>  $p->url_ang,
                'url_id'   =>  $p->id
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
            <h1 class="ui dividing header">Ajout d'un nouveau bloc</h1>
            <div class="could go">
                <div class="two fields">
                    <?= Form::text([
                        'label' =>  "Titre de l'item",
                        'name'  =>  'title',
                        'placeholder'    =>  'Entrer le titre du bloc',
                        'value' => $title
                    ], $errors) ?>
                    <?= Form::text([
                        'label' =>  "Titre de l'item en anglais",
                        'name'  =>  'title_ang',
                        'placeholder'    =>  'Entrer le titre du bloc en anglais',
                        'value' => $title_ang
                    ], $errors) ?>
                </div>
            </div>
            <div class="could go">
               <div class="two fields">
                    <?= Form::textarea([
                        'name'  =>  'content',
                        'label'   =>  'Entrer le contenu du bloc',
                        'id'    =>  'wysiwyg_accordion',
                        'value' => $content
                    ], $errors) ?>
                    <?= Form::textarea([
                        'name'  =>  'content_ang',
                        'label'   =>  'Entrer le contenu du bloc en anglais',
                        'id'    =>  'wysiwyg_accordion_2',
                        'value' => $content_ang
                    ], $errors) ?>
               </div>
           </div>
            <?= Form::file([
                'label' =>  'Ajouter une image',
                'name'  =>  'presentation_img'
            ]) ?>
            <?= Form::button('Ajouter') ?>
        </form>
    </div>
</div>