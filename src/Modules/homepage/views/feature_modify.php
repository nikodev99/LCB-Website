<?php

use Web\App\Core\Model;
use Web\App\Components\Form;
use Web\App\Core\AppFunction;
use Web\App\Core\session\Post;
use Web\App\Components\Component;
use Web\App\Core\upload\ImgUpload;

AppFunction::user_not_connected($router->url('admin.index'));
$p = new Post();

$feature = Model::getFeature(['id' => $params['id']]);
$page = Model::getPage(['url' => $feature->url]);
$sections_condition = ['url_id' => $page->id];
$text = Model::getText($sections_condition);
$bread = Model::getBreadcrumb($sections_condition);

$limit_description = 110;
$width = 600;
$height = 676;
$errors = [];
$redirect_url = $router->url('homepage.modify.feature');
$timestamp = (new DateTime())->getTimestamp();

if (!$p->isEmpty()) {
    $title = trim(htmlentities($p->get('title'))) ?? null;
    $title_ang = trim(htmlentities($p->get('title_ang'))) ?? null;
    $description = trim(htmlentities($p->get('description'))) ?? null;
    $description_ang = trim(htmlentities($p->get('description_ang'))) ?? null;
    $content = $p->get('content') ?? null;
    $content_ang = $p->get('content_ang') ?? null;
    $url = trim(htmlentities($p->get('url'))) ?? null;
    $url_ang = trim(htmlentities($p->get('url_ang'))) ?? null;
    $image = $_FILES['feature_img'] ?? null;

    if(empty($title) | empty($title_ang))
        $errors[] = 'Erreur. Un ou plusieurs champs sont vide, l\'annonce ne peut pas être ajouté';
    if(strlen($description) > $limit_description | strlen($description_ang) > $limit_description)
        $errors[] = 'Erreur. La description en français et en anglais ne peux pas être superieur à '.$limit_description.' caractères';
    if(empty($url) | empty($url_ang)) $errors[] = 'Erreur. Une annonce doit être avoir un lien (français et en anglais) qui mène vers les détails de l\'annonce';
    if(empty($content) | empty($content_ang)) $errors[] = 'Ajouter du contenu détaillant une annonce est primordiale';
    
    if(empty($errors)) {
        $file_name = !is_null($feature) ? $feature->image : null;
        $breadcrumb = !is_null($text) ? $bread->background : null;
        if(!empty($image['name'])) {
            $file = new ImgUpload($image);
            $file_name = $file->newFilename();
            $file->upload($file_name, $redirect_url, $width, $height);
            $breadcrumb = $file->newFilename();
            $file->upload($breadcrumb, $redirect_url, 1920, 1280);
        }
        Model::updateBreadcrumb($sections_condition, [
            'header'    =>  $title,
            'header_ang'=>  $title_ang,
            'background' =>  $file_name
        ]);
        Model::updateText($sections_condition, [
            'title' =>  $title,
            'title_ang' => $title_ang,
            'description'   =>  null,
            'description_ang'   => null,
            'content'   => $content,
            'content_ang'   => $content_ang
        ]);
        Model::updateMetadata($sections_condition, [
            'title' =>  $title,
            'title_ang' =>  $title_ang,
            'description'   =>  $description,
            'description_ang'   =>  $description,
        ]);
        Model::updatePage(['id' => $page->id], [
            'url'   =>  $url,
            'url_ang'   =>  $url_ang,
            'updated'   =>  $timestamp,
        ]);
        Model::updateFeature(['id' => $params['id']], [
            'image' =>  $file_name,
            'title' =>  $title,
            'title_ang' =>  $title_ang,
            'description'   =>  $description,
            'description_ang'   =>  $description_ang,
            'url'   =>  $url,
            'url_ang'   =>  $url_ang
        ]);
        AppFunction::successMessage('Annonce modifié avec succes !', $redirect_url);
    }else {
        AppFunction::errorMessage($errors, $redirect_url);
    }
}


?>

<div class="ui grid stackable padded">
    <div class="column">
    <?= Component::back_button($redirect_url) ?>
    <div class="ui divider"></div>
        <form action="" method="post" enctype="multipart/form-data" class="ui fluid form">
            <h1 class="ui dividing header">Modification de l'annonce</h1>
            <div class="could go">
                <div class="two fields">
                    <?= Form::text([
                        'label' =>  "Le titre de l'annonce",
                        'name'  =>  'title',
                        'value' =>  !is_null($feature) ? $feature->title : null
                    ]) ?>
                    <?= Form::text([
                        'label' =>  "Le titre de l'annonce en anglais",
                        'name'  =>  'title_ang',
                        'value' =>  !is_null($feature) ? $feature->title_ang : null
                    ]) ?>
                </div>
            </div>
            <div class="could go">
               <div class="two fields">
               <?= Form::text([
                        'label' =>  "lien de l'annonce",
                        'name'  =>  'url',
                        'value' =>  !is_null($feature) ? $feature->url : null
                    ]) ?>
                    <?= Form::text([
                        'label' =>  "Le lien de l'annonce en anglais",
                        'name'  =>  'url_ang',
                        'value' =>  !is_null($feature) ? $feature->url_ang : null
                    ]) ?>
               </div>
           </div>
            <div class="could go">
                <div class="two fields">
                    <?= Form::textarea([
                        'label'   =>  'Description de l\'annonce',
                        'name'  =>  'description',
                        'value' =>  !is_null($feature) ? $feature->description : null
                    ]) ?>
                    <?= Form::textarea([
                        'label'   =>  'Description de l\'annonce en anglais',
                        'name'  =>  'description_ang',
                        'value' =>  !is_null($feature) ? $feature->description_ang : null
                    ]) ?>
                </div>
            </div>
            <div class="could go">
               <div class="two fields">
               <?= Form::textarea([
                        'name'  =>  'content',
                        'label'   =>  'Entrer le contenu de votre annonce',
                        'value'   =>  !is_null($text) ? $text->content : null,
                        'id'    =>  'wysiwyg_accordion'
                    ]) ?>
                    <?= Form::textarea([
                        'name'  =>  'content_ang',
                        'label'   =>  'Entrer le contenu de votre annonce en anglais',
                        'value'   =>  !is_null($text) ? $text->content_ang : null,
                        'id'    =>  'wysiwyg_accordion_2'
                    ]) ?>
               </div>
           </div>
            <img class="ui medium bordered image" src="../../webroot/img/bg-img/<?= !is_null($feature) ? $feature->image : null ?>"> Choississez une nouvelle image pour mofier celle-ci :
            <?= Form::file([
                'name'  =>  'feature_img'
            ]) ?>
            <?= Form::button('Modifier') ?>
        </form>
    </div>
</div>