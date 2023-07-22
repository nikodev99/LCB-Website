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
$icons_got = [];
$counters_got = [];
$labels_got = [];

$expose = Model::getExpose(['url' => $params['slug'], 'url_ang' => $params['slug']]);

$redirect_url = $router->url('page.index', ['slug' => $params['slug']]);

$single_facts = !is_null($expose) ? json_decode($expose->single_facts, true) : [];
if (!empty($single_facts))
    foreach($single_facts as $facts) {
        $icons_got[] = $facts['icon'];
        $counters_got[] = $facts['counter'];
        $labels_got[] = $facts['label'];
    }

if (!$p->isEmpty()) {
    $title = $p->get('title');
    $title_ang = $p->get('title_ang');
    $description = $p->get('description');
    $description_ang = $p->get('description_ang');
    $icons = $p->get('icons') ?? [];
    $counters = $p->get('counters') ?? [];
    $labels = $p->get('labels') ?? [];
    $image = $_FILES['expose_img'] ?? null;

    if (empty($p->get('title'))) $errors['title'] =  'Ce champs title est vide. Veuillez le remplir';
    if (empty($p->get('title_ang'))) $errors['title_ang'] = 'Ce champs title en anglais est vide. Veuillez le remplir';
    if (empty($p->get('description'))) $errors['description'] = 'Ce champs description est vide. Veuillez le remplir';
    if (empty($p->get('description_ang'))) $errors['description_ang'] = 'Ce champs description en anglais est vide. Veuillez le remplir';

    $icons_posted = !empty($icons) ? AppFunction::array_trim(explode(';', $icons)): [];
    $counters_posted = !empty($counters) ? AppFunction::array_trim(explode(';', $counters)): [];
    $labels_posted = !empty($labels) ? AppFunction::array_trim(explode(';', $labels)): [];
    $illustration = [];
    if (!empty($icons_posted)) {
        if (count($icons_posted) === count($counters_posted) && count($icons_posted) === count($labels_posted)) {
            for ($i = 0; $i < count($icons_posted); $i++) {
                $illustration[] = [
                    'icon'  =>  $icons_posted[$i],
                    'counter'   =>  $counters_posted[$i],
                    'label' =>  $labels_posted[$i]
                ];
            }
        }
    }

    if(empty($errors)) {
        $file_name = !is_null($expose) ? $expose->image : null;
        if (!empty($image['name'])) {
            $img = new ImgUpload($image);
            $file_name = $img->newFilename();
            $img->upload($file_name, $redirect_url, 1920, 1280);
        }
        Model::updateExpose(['url' => $params['slug'], 'url_ang' => $params['slug']], [
            'title' =>  $title,
            'title_ang' =>  $title_ang,
            'description'   =>  $description,
            'description_ang'   =>  $description_ang,
            'image'   =>  $file_name,
            'single_facts'  =>  json_encode($illustration),
        ]);
        $flash->success('Modifications effectuées avec succès !');
    }else {
        $flash->error('Un ou plusieurs erreur ont été enregistré');
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
                        'value' =>  !is_null($expose) ? $expose->title : null
                    ], $errors) ?>
                    <?= Form::text([
                        'label' =>  "Titre de la section en anglais",
                        'name'  =>  'title_ang',
                        'value' =>  !is_null($expose) ? $expose->title_ang : null
                    ], $errors) ?>
                </div>
            </div>
            <div class="could go">
               <div class="two fields">
               <?= Form::textarea([
                        'name'  =>  'description',
                        'value' =>  !is_null($expose) ? $expose->description : null
                    ]) ?>
                    <?= Form::textarea([
                        'name'  =>  'description_ang',
                        'value' =>  !is_null($expose) ? $expose->description_ang : null
                    ]) ?>
               </div>
           </div>
            <img class="ui medium bordered image" src="../../webroot/img/bg-img/<?= !is_null($expose) ? $expose->image : null ?>"> Choississez une nouvelle image pour mofier celle-ci :
            <?= Form::file([
                'name'  =>  'expose_img'
            ]) ?>
            <div class="ui secondary segment">
                <h2>Ajouter les icons et nombre pour illustration de l'annonce (Pas Obligatoire)</h2>
                <?= Form::text([
                        'label' =>  "Les icons. Il suffit d'ajouter le nom d'un icon <a href=\"{$router->url('page.icon')}\" style=\"color:red\">Parmi ceux dans cette banque</a>, séparé chaque nom d'icon par un point virgule",
                        'name'  =>  'icons',
                        'value' =>  !is_null($expose) ? implode(';', $icons_got) : null
                ], $errors) ?>
                <?= Form::text([
                        'label' =>  "Les nombres. Le nombre appartenant à l'icon qui illustre l'annonce. Séparé chaque nombre par un point virgule et veuillez a ce que le nombre d'icon soit égale au nombre des chiffre entré",
                        'name'  =>  'counters',
                        'value' =>  !is_null($expose) ? implode(';', $counters_got) : null
                ], $errors) ?>
                <?= Form::text([
                        'label' =>  "Les Etiquettes. ou nom de l'illustration (Associant l'icon et le nombre). Le nombre d'étiquettes doit être égale au nombre d'icons et de chiffre entré. Veuillez à cela.",
                        'name'  =>  'labels',
                        'value' =>  !is_null($expose) ? implode(';', $labels_got) : null
                ], $errors) ?>
                <h4 style="color:red">*Ne pas y touché si l'annonce dans cet espace d'exposition n'a aucune illustration.</h4>
            </div>
            <?= Form::button('Modifier') ?>
        </form>
    </div>
</div>