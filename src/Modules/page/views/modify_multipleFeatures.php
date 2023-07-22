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
$tab = Model::getMultipleFeature($condition);

$flash = new Flash();
$redirect_url = $router->url('page.motify.multipleFeature', ['slug' => $params['slug']]);
$errors = [];

if (!$p->isEmpty()) {

    $title = !$p->isEmpty('title') ? $p->get('title') : $tab->title;
    $title_ang = !$p->isEmpty('title_ang') ? $p->get('title_ang') : $tab->title_ang;
    $content = !$p->isEmpty('description') ? $p->get('description') : $tab->description;
    $content_ang = !$p->isEmpty('description_ang') ? $p->get('description_ang') : $tab->description_ang;
    $redirect = !$p->isEmpty('redirect') ? $p->get('redirect') : $tab->redirect;
    $redirect_ang = !$p->isEmpty('redirect') ? $p->get('redirect_ang') : $tab->redirect_ang;
    $image = $_FILES['feature_img'] ?? null;

    if (is_null($title)) $errors['title'] = 'Ce champ est vide. Veuillez le remplir';
    if (is_null($title_ang)) $errors['title_ang'] = 'Ce champ est vide. Veuillez le remplir';
    if (is_null($content)) $errors['description'] = 'Ce champ est vide. Veuillez le remplir';
    if (is_null($content_ang)) $errors['description_ang'] = 'Ce champ est vide. Veuillez le remplir';
    if (!is_null($content) && strlen($content) > 215) $errors['description'] = 'Ce champ ne peut avoir plus de 150 caractères';
    if (!is_null($content_ang) && strlen($content_ang) > 215) $errors['description_ang'] = 'Ce champ ne peut avoir plus de 150 caractères';

    $bread = Model::getBreadcrumb(['url' => $params['slug'], 'url_ang' => $params['slug']]);

    if (empty($errors)) {
        $file_name = !is_null($tab) ? $tab->image : null;
        $breadcrumb = !is_null($bread) ? $bread->background : null;
        if (!empty($image['name']) && $image['name'] !== $file_name) {
            if ($file_name !== null) AppFunction::unlinking($file_name);
            $img = new ImgUpload($image);
            $file_name = $img->newFilename();
            $breadcrumb = $img->newFilename();
            $img->upload($file_name, $redirect_url, 600, 800);
            $img->upload($breadcrumb, $redirect_url, 1920, 1280);
        }
        if ($redirect === $tab->redirect && $redirect_ang === $tab->redirect_ang) {
            Model::updateMetadata(['url' => $redirect], [
                'title' =>  $title,
                'title_ang' =>  $title_ang,
                'description'   =>  $content,
                'description_ang'   =>  $content_ang,
            ]);
            Model::updateBreadcrumb(['url' => $redirect], [
                'header'    =>  $title,
                'header_ang'    =>  $title_ang,
                'background'    =>  $breadcrumb,
                'breadcrumb'    =>  json_encode([$bread->url => $bread->header]),
                'breadcrumb_ang'    =>  json_encode([$bread->url_ang => $bread->header_ang])
            ]);
        }else {
            if (Model::getPage(['url' => $redirect]) === null) {
                $id = Model::insertSlug([
                    'url'   =>  $redirect,
                    'url_ang'   =>  $redirect_ang,
                    'content'   =>  json_encode(['banniere', 'text', 'presentation']),
                    'created'   => time(),
                    'updated'   =>  null,
                    'online'    =>  0,
                    'navbar_id' =>  0
                ], true);
                Model::insertMetadata([
                    'title' =>  $title,
                    'title_ang' =>  $title_ang,
                    'description'   =>  $content,
                    'description_ang'   =>  $content_ang,
                    'url'       =>  $redirect,
                    'url_ang'   =>  $redirect_ang,
                    'url_id'    =>  $id
                ]);
                Model::insertBreadcrumb([
                    'header'    =>  $title,
                    'header_ang'    =>  $title_ang,
                    'background'    =>  $breadcrumb,
                    'breadcrumb'    =>  json_encode([$bread->url => $bread->header]),
                    'breadcrumb_ang'    =>  json_encode([$bread->url_ang => $bread->header_ang]),
                    'url'       =>  $redirect,
                    'url_ang'   =>  $redirect_ang,
                    'url_id'    =>  $id
                ]);
            }
        }
        Model::updateMultipleFeature($condition, [
            'title' =>  $title,
            'title_ang' =>  $title_ang,
            'description'   =>  $content,
            'description_ang'   =>  $content_ang,
            'image' =>  $file_name,
            'redirect'   =>  $redirect,
            'redirect_ang'   =>  $redirect_ang,
        ]);
        AppFunction::successMessage("Modification réussi", $redirect_url);
    }else {
        $flash->error('Un item doit avoir au moins un titre.');
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
                        'value'    =>  !is_null($tab) ? $tab->title : null,
                        'required'  => true
                    ], $errors) ?>
                     <?= Form::text([
                        'label' =>  "Titre du bloc en anglais",
                        'name'  =>  'title_ang',
                        'value'    =>  !is_null($tab) ? $tab->title_ang : null,
                        'required'  => true
                    ], $errors) ?>
                </div>
            </div>
            <div class="could go">
                <div class="two fields">
                    <?= Form::text([
                        'label' =>  "Titre de l'item",
                        'name'  =>  'redirect',
                        'value'    =>  !is_null($tab) ? $tab->redirect : null,
                        'required'  => true
                    ], $errors) ?>
                    <?= Form::text([
                        'label' =>  "Titre de l'item en anglais",
                        'name'  =>  'redirect_ang',
                        'value'    =>  !is_null($tab) ? $tab->redirect_ang : null,
                        'required'  => true
                    ], $errors) ?>
                </div>
            </div>
            <div class="could go">
               <div class="two fields">
               <?= Form::textarea([
                        'label' =>  "Description de l'item",
                        'name'  =>  'description',
                        'value'   =>  !is_null($tab) ? $tab->description : null,
                    ]) ?>
                    <?= Form::textarea([
                        'label' =>  "Description de l'item en anglais",
                        'name'  =>  'description_ang',
                        'value'   =>  !is_null($tab) ? $tab->description_ang : null,
                    ]) ?>
               </div>
           </div>
           <img class="ui medium bordered image" src="<?= !is_null($tab) ? AppFunction::getImgRoute($tab->image) : null ?>"> Choississez une nouvelle image pour mofier celle-ci :
            <?= Form::file([
                'name'  =>  'feature_img'
            ]) ?>
            <?= Form::button('Modifier') ?>
        </form>
    </div>
</div>