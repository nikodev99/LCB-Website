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

$condition = ['url' => $params['slug'], 'id' => $params['id']];
$pre = Model::getPresentation($condition);

$flash = new Flash();
$redirect_url = $router->url('page.motify.presentation', ['slug' => $params['slug']]);
$errors = [];

if (!$p->isEmpty()) {

    $title = !$p->isEmpty('title') ? $p->get('title') : $pre->title;
    $title_ang = !$p->isEmpty('title_ang') ? $p->get('title_ang') : $pre->title_ang;
    $content = !$p->isEmpty('content') ? $p->get('content') : $pre->content;
    $content_ang =!$p->isEmpty('content_ang') ? $p->get('content_ang') : $pre->content_ang;
    $image = $_FILES['presentation_img'] ?? null;

    $content = str_replace('<ul>', '<ul class="check-list">', $content);
    $content_ang = str_replace('<ul>', '<ul class="check-list">', $content_ang);

    if (is_null($title)) $errors['title'] = 'Ce champ est vide. Veuillez le remplir';
    if (is_null($title_ang)) $errors['title_ang'] = 'Ce champ est vide. Veuillez le remplir';
    if (is_null($content)) $errors['content'] = 'Ce champ est vide. Veuillez le remplir';
    if (is_null($content_ang)) $errors['content_ang'] = 'Ce champ est vide. Veuillez le remplir';

    if (empty($errors)) {
        $file_name = !is_null($pre) ? $pre->image : null;
        if (!empty($image['name'])) {
            $img = new ImgUpload($image);
            $file_name = $img->newFilename();
            $img->upload($file_name, $redirect_url);
        }
        Model::updatePresentation($condition, [
            'image' =>  $file_name,
            'title' =>  $title,
            'title_ang' =>  $title_ang,
            'content'   =>  $content,
            'content_ang'   =>  $content_ang
        ]);
        AppFunction::successMessage("Modification effectuée avec success", $redirect_url);
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
            <h1 class="ui dividing header">Modification du bloc</h1>
            <div class="could go">
                <div class="two fields">
                    <?= Form::text([
                        'required'  => true,
                        'label' =>  "Titre de l'item",
                        'name'  =>  'title',
                        'placeholder'    =>  'Entrer le titre du bloc',
                        'value' =>  !is_null($pre) ? $pre->title : null
                    ], $errors) ?>
                    <?= Form::text([
                        'required'  => true,
                        'label' =>  "Titre de l'item en anglais",
                        'name'  =>  'title_ang',
                        'placeholder'    =>  'Entrer le titre du bloc en anglais',
                        'value' =>  !is_null($pre) ? $pre->title_ang : null
                    ], $errors) ?>
                </div>
            </div>
            <div class="could go">
               <div class="two fields">
                    <?= Form::textarea([
                        'name'  =>  'content',
                        'label'   =>  'Entrer le contenu du bloc',
                        'id'    =>  'wysiwyg_accordion',
                        'value' =>  !is_null($pre) ? $pre->content : null
                    ], $errors) ?>
                    <?= Form::textarea([
                        'name'  =>  'content_ang',
                        'label'   =>  'Entrer le contenu du bloc en anglais',
                        'id'    =>  'wysiwyg_accordion_2',
                        'value' =>  !is_null($pre) ? $pre->content_ang : null
                    ], $errors) ?>
               </div>
           </div>
           <img class="ui medium bordered image" src="<?= !is_null($pre) ? AppFunction::getImgRoute($pre->image) : null ?>"> Choississez une nouvelle image pour mofier celle-ci :
            <?= Form::file([
                'label' =>  'Ajouter une image',
                'name'  =>  'presentation_img'
            ]) ?>
            <?= Form::button('Modifier', 'primary') ?>
        </form>
    </div>
</div>