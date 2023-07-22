<?php

use Web\App\Core\Model;
use Web\App\Components\Form;
use Web\App\Core\AppFunction;
use Web\App\Core\session\Post;
use Web\App\Components\Component;
use Web\App\Core\Flash;
use Web\App\Core\session\Session;

AppFunction::user_not_connected($router->url('admin.index'));
$p = new Post();

$conditions = ['url' => $params['slug'], 'url_ang' => $params['slug']];
$t = Model::getTitle($conditions);

$limit_description = 350;
$errors = [];
$redirect_url = (new Session())->get('redirect');
if (is_null($redirect_url)) $redirect_url = $router->url('page.index', ['slug' => $params['slug']]);

if (!$p->isEmpty()) {
    $title = trim($p->get('title')) ?? null;
    $title_ang = trim($p->get('title_ang')) ?? null;
    $description = trim($p->get('description')) ?? null;
    $description_ang = trim($p->get('description_ang')) ?? null;

    if(empty($title) | is_null($title) | empty($title_ang) | is_null($title_ang))
        $errors[] = 'Erreur. Un ou plusieurs champs sont vide, le titre ne peut pas être modifié';
    if(empty($description) | is_null($description) | empty($description_ang) | is_null($description_ang))
        $errors[] = 'Erreur. Un ou plusieurs champs sont vide, le titre ne peut pas être modifié';
    if(strlen($description) > $limit_description | strlen($description_ang) > $limit_description)
        $errors[] = 'Erreur. La description en français et en anglais ne peux pas être superieur à '.$limit_description.' caractères';

    if(empty($errors)) {
        Model::updateTitle($conditions, [
            'title' =>  htmlentities($title),
            'title_ang' =>  htmlentities($title_ang),
            'description'   =>  htmlentities($description),
            'description_ang'   =>  htmlentities($description_ang)
        ]);
        AppFunction::successMessage('Annonce modifié avec succes !', $redirect_url);
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
            <h1 class="ui dividing header">Modification de l'annonce</h1>
            <div class="could go">
                <div class="two fields">
                    <?= Form::text([
                        'label' =>  "Le titre du bloc",
                        'name'  =>  'title',
                        'value' =>  !is_null($t) ? $t->title : null
                    ], $errors) ?>
                    <?= Form::text([
                        'label' =>  "Le titre du bloc en anglais",
                        'name'  =>  'title_ang',
                        'value' =>  !is_null($t) ? $t->title_ang : null
                    ], $errors) ?>
                </div>
            </div>
            <div class="could go">
                <div class="two fields">
                    <?= Form::textarea([
                        'label'   =>  'Description du bloc',
                        'name'  =>  'description',
                        'value' =>  !is_null($t) ? $t->description : null
                    ], $errors) ?>
                    <?= Form::textarea([
                        'label'   =>  'Description du bloc en anglais',
                        'name'  =>  'description_ang',
                        'value' =>  !is_null($t) ? $t->description_ang : null
                    ], $errors) ?>
                </div>
            </div>
            <?= Form::button('Modifier', 'primary') ?>
        </form>
    </div>
</div>