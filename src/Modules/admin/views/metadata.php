<?php

use Web\App\Components\Form;
use Web\App\Core\AppFunction;
use Web\App\Components\Component;
use Web\App\Core\Flash;
use Web\App\Core\Model;
use Web\App\Core\session\Post;

AppFunction::user_not_connected($router->url('admin.index'));
$post = new Post();

$errors = [];

$conditions = ['url' => $params['slug'], 'url_id' => $params['id']];

$redirect_url = $router->url('admin.page');

$meta = Model::getMetadata($conditions);

if (!$post->isEmpty()) {

    $title = $post->get('title');
    $title_ang = $post->get('title_ang');
    $description = $post->get('description');
    $description_ang = $post->get('description_ang');
    $keyword = $post->get('keywords');
    $keyword_ang = $post->get('keywords_ang');

    if (is_null($title) | is_null($title_ang) | is_null($description) | is_null($description_ang) | is_null($keyword) | is_null($keyword_ang))
        $errors[] = 'Un ou plusieurs champs sont vides. Tous les champs sont réquis';

    if (empty($errors)) {

        $keyword = json_encode(AppFunction::array_trim(explode(',', $keyword)));
        $keyword_ang = json_encode(AppFunction::array_trim(explode(',', $keyword_ang)));

        if (is_null($meta)) {
            $page = Model::getPage(['url' => $params['slug'], 'id' => $params['id']], null, ['url', 'url_ang', 'id']);
            Model::insertMetadata([
                'title' =>  $title,
                'title_ang' =>  $title_ang,
                'description'   =>  $description,
                'description_ang'   =>  $description_ang,
                'url'   =>  $page->url,
                'url_ang'   =>  $page->url_ang,
                'url_id'    =>  $page->id,
                'keywords'  =>  $keyword,
                'keywords_ang'  =>  $keyword_ang
            ]);
            AppFunction::successMessage('Les informations meta-data de la page ajoutées avec succès !', $redirect_url); 
        }else {
            Model::updateMetadata($conditions, [
                'title' =>  $title,
                'title_ang' =>  $title_ang,
                'description'   =>  $description,
                'description_ang'   =>  $description_ang,
                'keywords'  =>  $keyword,
                'keywords_ang'  =>  $keyword_ang
            ]);
            AppFunction::successMessage('Modifications effcetuées avec succès !', $redirect_url);
        }
    }else {
        (new Flash())->error($errors);
    }

}

?>

<div class="ui grid stackable padded">
    <div class="column">
    <?= Component::back_button($redirect_url) ?>
    <div class="ui divider"></div>
        <form action="" method="post" enctype="multipart/form-data" class="ui fluid form">
            <h1 class="ui dividing header">Modification de la page: [slug: <?= $params['slug'] ?>]</h1>
            <div class="could go">
                <div class="two fields">
                    <?= Form::text([
                        'label' =>  "Titre de la page *",
                        'name'  =>  'title',
                        'value' =>  is_null($meta) ? '' : $meta->title
                    ], $errors) ?>
                    <?= Form::text([
                        'label' =>  "Titre de la page en anglais *",
                        'name'  =>  'title_ang',
                        'value' =>  is_null($meta) ? '' : $meta->title_ang
                    ], $errors) ?>
                </div>
            </div>
            <div class="could go">
               <div class="two fields">
                <?= Form::textarea([
                        'label' => 'Description de la page *',
                        'name'  =>  'description',
                        'value' =>  is_null($meta) ? '' : $meta->description
                    ]) ?>
                    <?= Form::textarea([
                        'label' => 'Description de la page en anglais *',
                        'name'  =>  'description_ang',
                        'value' =>  is_null($meta) ? '' : $meta->description_ang
                    ]) ?>
               </div>
           </div>
            <div class="could go">
               <div class="two fields">
                    <?= Form::textarea([
                        'label' => 'Les mots clés de la page. Entrer chaque mot clé en les séparant par la virgule (,) *',
                        'name'  =>  'keywords',
                        'value' =>  is_null($meta) ? '' : (!is_null($meta->keywords) ? implode(',', json_decode($meta->keywords, true)) : null),
                        'id'    =>  'keyword'
                    ]) ?>
                    <?= Form::textarea([
                        'label' => 'Les mots clés de la page en anglais. Entrer chaque mot clé en les séparant la virgule (,) *',
                        'name'  =>  'keywords_ang',
                        'value' =>  is_null($meta) ? '' : (!is_null($meta->keywords_ang) ? implode(',', json_decode($meta->keywords_ang, true)) : null),
                        'id'    =>  'keyword'
                    ]) ?>
               </div>
           </div>
            <?= Form::button('Modifier') ?>
        </form>
    </div>
</div>