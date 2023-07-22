<?php

use Web\App\Core\Flash;
use Web\App\Core\Model;
use Web\App\Components\Form;
use Web\App\Core\AppFunction;
use Web\App\Core\Route\Route;
use Web\App\Core\session\Post;
use Web\App\Components\Component;
use Web\App\Core\upload\ImgUpload;

AppFunction::user_not_connected($router->url('admin.index'));
$p = new Post();
$flash = new Flash();

$errors = [];

$reason = Model::getReason(['url' => $params['slug'], 'url_ang' => $params['slug']]);
$page = Model::getPage(['url' => $params['slug'], 'url_ang' => $params['slug']], null, ['id', 'url', 'url_ang']);

$redirect_url = $router->url('page.index', ['slug' => $params['slug']]);

if (!$p->isEmpty()) {
    $title = $p->get('title');
    $title_ang = $p->get('title_ang');
    $introduction = $p->get('description');
    $introduction_ang = $p->get('description_ang');
    $items = $p->get('items');
    $items_ang = $p->get('items_ang');
    $image = $_FILES['image'] ?? null;

    foreach($p->get() as $key => $value) {
        if ($p->isEmpty($key)) {
            $errors[$key] = 'Ce champs est vide';
        }
    }

    if(empty($errors)) {
        $file_name = !is_null($reason) ? $reason->image : null;
        if (!empty($image['name'])) {
            $img = new ImgUpload($image);
            $file_name = $img->newFilename();
            $img->upload($file_name, $redirect_url, 1200, 889);
        }
        if (is_null($reason)) {
            Model::insertReason([
                'image' =>  $file_name,
                'title' =>  $title,
                'title_ang' =>  $title_ang,
                'description'   =>  $introduction,
                'description_ang'   =>  $introduction_ang,
                'items' =>  json_encode(explode(';', $items)),
                'items_ang' =>  json_encode(explode(';', $items_ang)),
                'url'   => $page->url,
                'url_ang'   =>  $page->url_ang,
                'url_id'    =>  (int)$page->id
            ]);
        }else {
            Model::updateReason(['url' => $params['slug'], 'url_ang' => $params['slug']], [
                'image' =>  $file_name,
                'title' =>  $title,
                'title_ang' =>  $title_ang,
                'description'   =>  $introduction,
                'description_ang'   =>  $introduction_ang,
                'items' =>  json_encode(explode(';', $items)),
                'items_ang' =>  json_encode(explode(';', $items_ang))
            ]); 
        }
        $flash->success("Modifications effectuées avec success !");
    }else {
        $flash->error('Un ou plusieurs erreurs ont été enregistrés');
    }
}


?>

<div class="ui grid stackable padded">
    <div class="column">
        <?= Component::back_button($redirect_url) ?>
        <div class="ui divider"></div>
        <form action="" method="post" enctype="multipart/form-data" class="ui fluid form">
            <h1 class="ui dividing header">Modification de la section</h1>
            <div class="could go">
                <div class="two fields">
                    <?= Form::text([
                        'label' =>  "Titre de la section",
                        'name'  =>  'title',
                        'value' =>  !is_null($reason) ? $reason->title : null
                    ], $errors) ?>
                    <?= Form::text([
                        'label' =>  "Titre de la section en anglais",
                        'name'  =>  'title_ang',
                        'value' =>  !is_null($reason) ? $reason->title_ang : null
                    ], $errors) ?>
                </div>
            </div>
            <div class="could go">
                <div class="two fields">
                    <?= Form::textarea([
                        'label' =>  "Les items en français. Entrer plusieurs item en les séparant par les point-virgules (;)",
                        'name'  =>  'items',
                        'value' =>  !is_null($reason) ? implode(';', json_decode($reason->items)) : null
                    ]) ?>
                    <?= Form::textarea([
                        'label' =>  "Items in english. Enter several items by joining them with semicolons (;)",
                        'name'  =>  'items_ang',
                        'value' =>  !is_null($reason) ? implode(';', json_decode($reason->items_ang)) : null
                    ]) ?>
                </div>
            </div>
           <div class="could go">
               <div class="two fields">
               <?= Form::textarea([
                        'label' =>  'Entrer l\'introduction en français',
                        'name'  =>  'description',
                        'value' =>  !is_null($reason) ? $reason->description : null
                    ]) ?>
                    <?= Form::textarea([
                        'label' =>  'Enter the introduction in english',
                        'name'  =>  'description_ang',
                        'value' =>  !is_null($reason) ? $reason->description_ang : null
                    ]) ?>
               </div>
           </div>
            <img class="ui medium bordered image" src="../../webroot/img/bg-img/<?= !is_null($reason) ? $reason->image : null ?>"> Choississez une nouvelle image pour modifier celle-ci :
            <?= Form::file([
                'name'  =>  'image'
            ]) ?>
            <?= is_null($reason) ? Form::button('Ajouter du contenu à la section') : Form::button('Modifier') ?>
        </form>
    </div>
</div>