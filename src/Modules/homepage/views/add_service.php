<?php

use Web\App\Core\Flash;
use Web\App\Core\Model;
use Web\App\Components\Form;
use Web\App\Core\AppFunction;
use Web\App\Core\session\Post;
use Web\App\Components\Component;

$errors = [];
$p = new Post();

if(!$p->isEmpty()) {
    
    $title = $p->get('title') ?? null;
    $title_ang = $p->get('title_ang') ?? null;
    $icon = $p->get('icon') ?? null;
    $description = $p->get('description') ?? null;
    $description_ang = $p->get('description_ang') ?? null;

    foreach($p->get() as $key => $value) {
        if($p->isEmpty($key) | is_null($p->get($key))) {
            $errors[$key] = 'Le champ '. $key .' est vide';
        }
    }
    if (strlen($description) > 200 | strlen($description_ang) > 200) $errors[] = 'La description à plus de 200 caractères. Faite en sorte qu\'elle ait moins de caractère';
    
    if (empty($errors)) {

        Model::insertService([
            'icon'  =>  $icon,
            'title' =>  $title,
            'title_ang' =>  $title_ang,
            'description'   =>  $description,
            'description_ang'   =>  $description_ang
        ]);
        AppFunction::successMessage("Nouveau service ajouté avec succes !", $router->url('homepage.service'));
    }else {
        $flash = new Flash();
        $flash->error($errors);
    }

}

?>

<div class="ui grid stackable padded">
    <div class="column">
        <?= Component::back_button($router->url('homepage.service')) ?>
        <div class="ui divider"></div>
        <form action="" method="post" enctype="multipart/form-data" class="ui fluid form">
            <h1 class="ui dividing header">Ajout d'un service</h1>
            <div class="could go">
                <div class="two fields">
                    <?= Form::text([
                        'label' =>  "Entrer le titre",
                        'name'  =>  'title',
                        'placeholder'    =>  'Entrer le titre du service'
                    ], $errors) ?>
                    <?= Form::text([
                        'label' =>  "Entrer le titre en anglais",
                        'name'  =>  'title_ang',
                        'placeholder'    =>  'Entrer le titre du service en anglais'
                    ], $errors) ?>
                </div>
            </div>
            <?= Form::text([
                'label' =>  "Entrer le nom de l'icon. Choississez en un dans ce <a style=\"color:red\" href=\"".$router->url('page.icon')."\">repertoire</a>",
                'name'  =>  'icon',
                'placeholder'    =>  'Entrer le nom de l\'icon'
            ], $errors) ?>
            <div class="could go">
               <div class="two fields">
               <?= Form::textarea([
                        'label' =>  "Entrer la description",
                        'name'  =>  'description',
                        'placeholder'   =>  'Entrer la description du service',
                    ]) ?>
                    <?= Form::textarea([
                        'label' =>  "Entrer la description",
                        'name'  =>  'description_ang',
                        'placeholder'   =>  'Entrer la description du service en anglais',
                    ]) ?>
               </div>
           </div>
            <?= Form::button('Ajouter') ?>
        </form>
    </div>
</div>