<?php

use Web\App\Core\Flash;
use Web\App\Core\Model;
use Web\App\Components\Form;
use Web\App\Core\AppFunction;
use Web\App\Components\Component;
use Web\App\Core\session\Post;

AppFunction::user_not_connected($router->url('admin.index'));
$p = new Post();

$condition = ['url' => $params['slug'], 'id' => $params['id']];
$tab = Model::getWork($condition);
$wt = Model::getTitle(['url' => $params['slug']]);

$flash = new Flash();
$redirect_url = $router->url('page.modify.work', ['slug' => $params['slug']]);
$errors = [];

if (!$p->isEmpty()) {

    $item = !$p->isEmpty('item') ? $p->get('item') : null;
    $item_ang = !$p->isEmpty('item_ang') ? $p->get('item_ang') : null;
    $title = !$p->isEmpty('title') ? $p->get('title') : null;
    $title_ang = !$p->isEmpty('title_ang') ? $p->get('title_ang') : null;
    $content = !$p->isEmpty('description') ? $p->get('description') : null;
    $content_ang = !$p->isEmpty('description_ang') ? $p->get('description_ang') : null;

    if (is_null($item)) $errors['item'] = 'Ce champ est vide. Veuillez le remplir';
    if (is_null($item_ang))    $errors['item_ang'] = 'Ce champ est vide. Veuillez le remplir';
    if (!is_null($content) && strlen($content) > 150) $errors['description'] = 'Ce champ description ne peut avoir plus de 150 caractères';
    if (!is_null($content_ang) && strlen($content_ang) > 150) $errors['description_ang'] = 'Ce champ description en anglais ne peut avoir plus de 150 caractères';
    if (is_null($wt)) $errors[] = "Un groupe d'item doit avoir au moins un titre. Veuillez ajouter un titre à ce groupe pour pouvoir ajouter un nouvel item";

    if (empty($errors)) {
        Model::updateWork($condition, [
            'title' =>  $item,
            'title_ang' =>  $item_ang,
            'description'   =>  $content,
            'description_ang'   =>  $content_ang,
        ]);
        Model::updateTitle(['url' => $params['slug']], [
            'title' =>  $title,
            'title_ang' =>  $title_ang,
        ]);
        AppFunction::successMessage("Modification réussi", $redirect_url);
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
                        'label' =>  "Titre du bloc",
                        'name'  =>  'title',
                        'value'    =>  !is_null($wt) ? $wt->title : null,
                        'disabled'  =>  true,
                    ], $errors) ?>
                     <?= Form::text([
                        'label' =>  "Titre du bloc en anglais",
                        'name'  =>  'title_ang',
                        'value'    =>  !is_null($wt) ? $wt->title_ang : null,
                        'disabled'  =>  true,
                    ], $errors) ?>
                </div>
            </div>
            <div class="could go">
                <div class="two fields">
                    <?= Form::text([
                        'label' =>  "Titre de l'item",
                        'name'  =>  'item',
                        'value'    =>  !is_null($tab) ? $tab->title : null
                    ], $errors) ?>
                    <?= Form::text([
                        'label' =>  "Titre de l'item en anglais",
                        'name'  =>  'item_ang',
                        'value'    =>  !is_null($tab) ? $tab->title_ang : null
                    ], $errors) ?>
                </div>
            </div>
            <div class="could go">
               <div class="two fields">
                    <?= Form::textarea([
                                'label' =>  "Description de l'item",
                                'name'  =>  'description',
                                'value'   =>  !is_null($tab) ? $tab->description : null,
                    ], $errors) ?>
                    <?= Form::textarea([
                        'label' =>  "Description de l'item en anglais",
                        'name'  =>  'description_ang',
                        'value'   =>  !is_null($tab) ? $tab->description_ang : null,
                    ], $errors) ?>
               </div>
           </div>
           
            <?= Form::button('Modifier') ?>
        </form>
    </div>
</div>