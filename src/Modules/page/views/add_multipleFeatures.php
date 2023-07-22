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

$flash = new Flash();
$image_width = 600;
$image_height = 800;
$redirect_url = $router->url('page.motify.multipleFeature', ['slug' => $params['slug']]);
$errors = [];

$title = !$p->isEmpty('title') ? $p->get('title') : null;
$title_ang = !$p->isEmpty('title_ang') ? $p->get('title_ang') : null;
$redirect = !$p->isEmpty('redirect') ? $p->get('redirect') : null;
$redirect_ang = !$p->isEmpty('redirect_ang') ? $p->get('redirect_ang') : null;
$content = !$p->isEmpty('content') ? $p->get('content') : null;
$content_ang =!$p->isEmpty('content_ang') ? $p->get('content_ang') : null;
$image = $_FILES['feature_img'] ?? null;

if (!$p->isEmpty()) {

    if (is_null($title)) $errors['title'] = 'Ce champ est vide. Veuillez le remplir';
    if (is_null($title_ang)) $errors['title_ang'] = 'Ce champ est vide. Veuillez le remplir';
    if (is_null($redirect)) $errors['redirect'] = 'Ce champ est vide. Veuillez le remplir';
    if (is_null($redirect_ang)) $errors['redirect_ang'] = 'Ce champ est vide. Veuillez le remplir';
    if (is_null($content)) $errors['content'] = 'Ce champ est vide. Veuillez le remplir';
    if (is_null($content_ang)) $errors['content_ang'] = 'Ce champ est vide. Veuillez le remplir';
    if (!is_null($content) && strlen($content) > 215) $errors['content'] = 'Ce champ ne peut avoir plus de 200 caractères';
    if (!is_null($content_ang) && strlen($content_ang) > 215) $errors['content_ang'] = 'Ce champ ne peut avoir plus de 200 caractères';
    if(empty($image['name'])) $errors[] = 'Erreur. Un bloc doit être accompagné d\'une image. Pensez à uploader une';

    if (empty($errors)) {
        $file_name = null;
        $breadcrumb = null;
        if (!empty($image['name'])) {
            $img = new ImgUpload($image);
            $file_name = $img->newFilename();
            $breadcrumb = $img->newFilename();
            $img->upload($file_name, $redirect_url, $image_width, $image_height);
            $img->upload($breadcrumb, $redirect_url, 1920, 1280);
        }
        $url = Model::getPage(['url' => $params['slug'], 'url_ang' => $params['slug']], null, ['id', 'url', 'url_ang']);
        $bread = Model::getBreadcrumb(['url' => $params['slug'], 'url_ang' => $params['slug']]);
        $tab = Model::getMultipleFeature(['title' => $title]);
        if (is_null($tab)) {
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
            Model::insertMultipleFeature([
                'title' =>  $title,
                'title_ang' =>  $title_ang,
                'description'   =>  $content,
                'description_ang'   =>  $content_ang,
                'image' =>  $file_name,
                'redirect'  =>  $redirect,
                'redirect_ang'  =>  $redirect_ang,
                'url'       =>  $url->url,
                'url_ang'   =>  $url->url_ang,
                'url_id'    =>  $url->id
            ]);
            AppFunction::successMessage("Nouvel item enregistrer avec success", $redirect_url);
        }
    }else {
        $flash->error('Erreur rencontrer lors de l\'neregistrement du nouveau élément');
    }

}

?>

<div class="ui grid stackable padded">
    <div class="column">
        <?= Component::back_button($redirect_url) ?>
        <div class="ui divider"></div>
        <form action="" method="post" enctype="multipart/form-data" class="ui fluid form">
            <h1 class="ui dividing header">Ajout d'un nouveau bloc</h1>
            <div class="could go">
                <div class="two fields">
                    <?= Form::text([
                        'label' =>  "Titre de l'item",
                        'name'  =>  'title',
                        'placeholder'    =>  'Entrer le titre du bloc',
                        'value' =>  $title
                    ], $errors) ?>
                    <?= Form::text([
                        'label' =>  "Titre de l'item en anglais",
                        'name'  =>  'title_ang',
                        'placeholder'    =>  'Entrer le titre du bloc en anglais',
                        'value' =>  $title_ang,
                    ], $errors) ?>
                </div>
            </div>
            <div class="could go">
                <div class="two fields">
                    <?= Form::text([
                        'label' =>  "URL de rédirection",
                        'name'  =>  'redirect',
                        'placeholder'    =>  'Entrer URL de rédirection',
                        'value' =>  $redirect,
                    ], $errors) ?>
                    <?= Form::text([
                        'label' =>  "URL de rédirection en anglais",
                        'name'  =>  'redirect_ang',
                        'placeholder'    =>  'Entrer URL de rédirection en anglais',
                        'value' =>  $redirect_ang,
                    ], $errors) ?>
                </div>
            </div>
            <div class="could go">
               <div class="two fields">
                    <?= Form::textarea([
                        'name'  =>  'content',
                        'label'   =>  'Entrer une description du bloc',
                        'value' =>  $content,
                    ], $errors) ?>
                    <?= Form::textarea([
                        'name'  =>  'content_ang',
                        'label'   =>  'Entrer une description du bloc en anglais',
                        'value' =>  $content_ang,
                    ], $errors) ?>
               </div>
           </div>
            <?= Form::file([
                'label' =>  'Ajouter une image',
                'name'  =>  'feature_img'
            ]) ?>
            <?= Form::button('Ajouter') ?>
        </form>
    </div>
</div>