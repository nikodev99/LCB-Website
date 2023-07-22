<?php

use Web\App\Core\Flash;
use Web\App\Core\Model;
use Web\App\Components\Form;
use Web\App\Core\AppFunction;
use Web\App\Core\Route\Route;
use Web\App\Core\session\Post;
use Web\App\Components\Component;

AppFunction::user_not_connected($router->url('admin.index'));
$p = new Post();
$redirect_url = $router->url('page.index', ['slug' => $params['slug']]);

$text = Model::getText(['url' => $params['slug'], 'url_ang' => $params['slug']]);
$u = Model::getPage(['url' => $params['slug'], 'url_ang' => $params['slug']], null, ['url', 'url_ang', 'id']);
$flash = new Flash();

$errors = [];

if (!$p->isEmpty()) {

    $title = $p->get('title');
    $title_ang = $p->get('title_ang');
    $description = $p->get('description');
    $description_ang = $p->get('description_ang');
    $content = $p->get('content');
    $content_ang = $p->get('content_ang');

    if (empty($title) | is_null($title) | empty($content) | is_null($content)) $errors[] = 'Un ou plusieurs champs obligatoires sont vides';
    if (empty($errors)) {
        if (is_null($text)) {
            Model::insertNewText([
                'title' =>  $title,
                'title_ang' =>  $title_ang,
                'description'   =>  $description,
                'description_ang'   =>  $description_ang,
                'content'   =>  $content,
                'content_ang'   =>  $content_ang,
                'url'   =>  $u->url,
                'url_ang'   =>  $u->url_ang,
                'url_id'    =>  $u->id
            ]);
        }
        Model::updateText(['url' => $params['slug'], 'url_ang' => $params['slug']], [
            'title' =>  $title,
            'title_ang' =>  $title_ang,
            'description'   =>  $description,
            'description_ang'   =>  $description_ang,
            'content'   =>  $content,
            'content_ang'   =>  $content_ang,
        ]);
        $flash->success("Modifications effectuées avec success !");
    }else {
        $flash->error($errors);
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
                        'value'    =>  !is_null($text) ? $text->title : null
                    ]) ?>
                    <?= Form::text([
                        'label' =>  "Titre de la section en anglais",
                        'name'  =>  'title_ang',
                        'value'    =>  !is_null($text) ? $text->title_ang : null
                    ]) ?>
                </div>
            </div>
            <div class="could go">
                <div class="two fields">
                    <?= Form::textarea([
                        'placeholder' =>  "Description (Pas obligatoire)",
                        'name'  =>  'description',
                        'value'    =>  !is_null($text) ? $text->description : null
                    ]) ?>
                    <?= Form::textarea([
                        'placeholder' =>  "Description en anglais (Pas obligatoire)",
                        'name'  =>  'description_ang',
                        'value'    =>  !is_null($text) ? $text->description_ang : null
                    ]) ?>
                </div>
            </div>
            <div class="could go">
               <div class="two fields">
               <?= Form::textarea([
                        'name'  =>  'content',
                        'value'   =>  !is_null($text) ? $text->content : null,
                        'id'    =>  'wysiwyg_accordion'
                    ]) ?>
                    <?= Form::textarea([
                        'name'  =>  'content_ang',
                        'value'   =>  !is_null($text) ? $text->content_ang : null,
                        'id'    =>  'wysiwyg_accordion_2'
                    ]) ?>
               </div>
           </div>
           
            <?= Form::button('Modifier') ?>
        </form>
    </div>
</div>