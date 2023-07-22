<?php

use Web\App\Core\Flash;
use Web\App\Core\Model;
use Web\App\Components\Form;
use Web\App\Core\AppFunction;
use Web\App\Core\session\Post;
use Web\App\Components\Component;

AppFunction::user_not_connected($router->url('admin.index'));
$p = new Post();

$errors = [];

$service = Model::getServices(['id' => $params['id']]);

if(!$p->isEmpty()) {
    
    $title = $p->get('title');
    $title_ang = $p->get('title_ang');
    $icon = $p->get('icon');
    $description = $p->get('description');
    $description_ang = $p->get('description_ang');

    foreach($p->get() as $key => $value) {
        if($p->isEmpty($key) | is_null($p->get($key))) {
            $errors[$key] = 'Le champ '. $key .' est vide';
        }
    }
    if (strlen($description) > 200 | strlen($description_ang) > 200) $errors[] = 'La description à plus de 200 caractères. Faite en sorte qu\'elle ait moins de caractère';

    if (empty($errors)) {

        Model::updateService(['id' => $params['id']], [
            'icon'  =>  $icon,
            'title' =>  $title,
            'title_ang' =>  $title_ang,
            'description'   =>  $description,
            'description_ang'   =>  $description_ang
        ]);
        AppFunction::successMessage("Service modifié avec succes !", $router->url('homepage.service'));
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
            <h1 class="ui dividing header">Modification d'un service</h1>
            <div class="could go">
                <div class="two fields">
                    <?= Form::text([
                        'label' =>  "Entrer le titre",
                        'name'  =>  'title',
                        'value'    =>  !is_null($service) ? $service->title : null
                    ], $errors) ?>
                    <?= Form::text([
                        'label' =>  "Entrer le titre en anglais",
                        'name'  =>  'title_ang',
                        'value'    =>  !is_null($service) ? $service->title_ang : null
                    ], $errors) ?>
                </div>
            </div>
            <?= Form::text([
                'label' =>  "Entrer le nom de l'icon, choississez en un dans ce <a style=\"color:red\" href=\"".$router->url('page.icon')."\">repertoire</a>",
                'name'  =>  'icon',
                'value'    =>  !is_null($service) ? $service->icon : null
            ], $errors) ?>
            <div class="could go">
               <div class="two fields">
               <?= Form::textarea([
                        'label' =>  "Entrer la description",
                        'name'  =>  'description',
                        'value'   =>  !is_null($service) ? $service->description : null,
                    ]) ?>
                    <?= Form::textarea([
                        'label' =>  "Entrer la description",
                        'name'  =>  'description_ang',
                        'value'   =>  !is_null($service) ? $service->description_ang : null
                    ]) ?>
               </div>
           </div>
            <?= Form::button('Modifier') ?>
        </form>
    </div>
</div>