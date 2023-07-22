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

$about = Model::getAbout(['url' => $params['slug'], 'url_ang' => $params['slug']]);
$bread = Model::getBreadcrumb(['url' => $params['slug'], 'url_ang' => $params['slug']]);

$errors = [];
$redirect_url = $router->url('page.index', ['slug' => $params['slug']]);

if (!$p->isEmpty()) {
    $header = $p->get('header');
    $header_ang = $p->get('header_ang');
    $introduction = $p->get('introduction');
    $introduction_ang = $p->get('introduction_ang');
    $url = $p->get('redirect_url');
    $url_ang = $p->get('redirect_url_ang');
    $excerpt = $p->get('excerpt');
    $excerpt_ang = $p->get('excerpt_ang');
    $content = $p->get('content');
    $content_ang = $p->get('content_ang');
    $image = $_FILES['about_img'] ?? null;
    foreach($p->get() as $key => $value) {
        if ($p->isEmpty($key)) {
            if($key !== 'content' && $key !== 'content_ang') $errors[$key] = 'Ce champs est vide';
        }
        if ($key == 'redirect_url' | $key == 'redirect_url_ang') {
            if(count(explode(' ', $p->get($key))) > 1) $errors[$key] = "Eviter les espaces dans le nom des lien";
        }
    }
    if(empty($errors)) {
        $file_name = !is_null($about) ? $about->image : null;
        $breadcump = !is_null($bread) ? $bread->background : null;
        if (!empty($image['name'])) {
            $img = new ImgUpload($image);
            $file_name = $img->newFilename();
            $img->upload($file_name, $redirect_url, 1200, 889);
            $breadcump = $img->newFilename();
            $img->upload($breadcump, $redirect_url, 1920, 1280);
        }
        $condition = ['url' => $params['slug'], 'url_ang' => $params['slug']];
        $cond = ['url' => $url];
        $bre = Model::getBreadcrumb($cond);
        $t = Model::getText($cond);
        Model::updateAbout($condition, [
            'header'    =>  $header,
            'header_ang'    =>  $header_ang,
            'introduction'  =>  $introduction,
            'introduction_ang'  =>  $introduction_ang,
            'excerpt'   =>  $excerpt,
            'excerpt_ang'   =>  $excerpt_ang,
            'content'   =>  $content,
            'content_ang'   =>  $content_ang,
            'redirect_url'  =>  $url,
            'redirect_url_ang'  =>  $url_ang,
            'image' =>  $file_name,
        ]);
        if (!$p->isEmpty('content') && !$p->isEmpty('content_ang')) {
            Model::updateBreadcrumb($condition, [
                'header'    =>  $header,
                'header_ang'=>  $header_ang,
                'background' =>  $breadcump
            ]);
            if (is_null($bre)) {
                Model::insertBreadcrumb([
                    'header'    =>  $header,
                    'header_ang'=>  $header_ang,
                    'background' =>  $breadcump,
                    'breadcrumb' =>  json_encode([$about->url => $about->header]),
                    'breadcrumb_ang' => json_encode([$about->url_ang => $about->header_ang]),
                    'url'   =>  $url,
                    'url_ang'   =>  $url_ang
                ]);
            }else {
                Model::updateBreadcrumb($cond, [
                    'header'    =>  $header,
                    'header_ang'=>  $header_ang,
                    'background' =>  $breadcump,
                    'breadcrumb' =>  json_encode([$about->url => $about->header]),
                    'breadcrumb_ang' => json_encode([$about->url_ang => $about->header_ang])
                ]);
            }
                
            Model::updateText($condition, [
                'title' =>  $header,
                'title_ang' => $header_ang,
                'description'   =>  null,
                'description_ang'   => null,
                'content'   => $content,
                'content_ang'   => $content_ang
            ]);    
            Model::updateMetadata($condition, [
                'title' =>  $header,
                'title_ang' =>  $header_ang,
                'description'   =>  $introduction,
                'description_ang'   =>  $introduction_ang,
            ]);
            $page = Model::getPage(['url' => $url]);
            if (is_null($page)) {
                $id = Model::insertSlug([
                    'url'   =>  $url,
                    'url_ang'   =>  $url_ang,
                    'content'   =>  json_encode(['banniere', 'text']),
                    'created'   =>  time(),
                    'updated'   =>  null,
                    'online'    =>  0,
                    'navbar_id' =>  0
                ]);
                if (is_null($t)) {
                    Model::insertNewText([
                        'title' =>  $header,
                        'title_ang' => $header_ang,
                        'description'   =>  null,
                        'description_ang'   => null,
                        'content'   => $content,
                        'content_ang'   => $content_ang
                    ]);
                }else {
                    Model::updateText($cond, [
                        'title' =>  $header,
                        'title_ang' => $header_ang,
                        'description'   =>  null,
                        'description_ang'   => null,
                        'content'   => $content,
                        'content_ang'   => $content_ang,
                        'url'   =>  $url,
                        'url_ang'   =>  $url_ang,
                        'url_id'    =>  $id
                    ]);
                }
            } 
        }
        $flash->success('Modifications effectuées avec succès !');
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
                        'name'  =>  'header',
                        'value' =>  !is_null($about) ? $about->header : null
                    ], $errors) ?>
                    <?= Form::text([
                        'label' =>  "Titre de la section en anglais",
                        'name'  =>  'header_ang',
                        'value' =>  !is_null($about) ? $about->header_ang : null
                    ], $errors) ?>
                </div>
            </div>
            <div class="could go">
               <div class="two fields">
               <?= Form::text([
                        'label' =>  "Introduction",
                        'name'  =>  'introduction',
                        'value' =>  !is_null($about) ? $about->introduction : null
                    ], $errors) ?>
                    <?= Form::text([
                        'label' =>  "Introduction en anglais",
                        'name'  =>  'introduction_ang',
                        'value' =>  !is_null($about) ? $about->introduction_ang : null
                    ], $errors) ?>
               </div>
           </div>
            <div class="could go">
               <div class="two fields">
               <?= Form::text([
                        'label' =>  "Lien de redirection vers les détails de la section",
                        'name'  =>  'redirect_url',
                        'value' =>  !is_null($about) ? $about->redirect_url : null
                    ], $errors) ?>
                    <?= Form::text([
                        'label' =>  "Lien de redirection vers les détails de la section en anglais",
                        'name'  =>  'redirect_url_ang',
                        'value' =>  !is_null($about) ? $about->redirect_url_ang : null
                    ], $errors) ?>
               </div>
           </div>
            <div class="could go">
               <div class="two fields">
               <?= Form::textarea([
                        'label' =>  'Entrer l\'intrdoduction',
                        'name'  =>  'excerpt',
                        'value' =>  !is_null($about) ? $about->excerpt : null
                    ]) ?>
                    <?= Form::textarea([
                        'label' =>  'Entrer l\'introduction en anglais',
                        'name'  =>  'excerpt_ang',
                        'value' =>  !is_null($about) ? $about->excerpt_ang : null
                    ]) ?>
               </div>
           </div>
            <div class="could go">
               <div class="two fields">
               <?= Form::textarea([
                        'label' =>  'Entrer le contenu de la page détail',
                        'name'  =>  'content',
                        'value' =>  !is_null($about) ? $about->content : null,
                        'id'    =>  'wysiwyg_accordion'
                    ]) ?>
                    <?= Form::textarea([
                        'label' =>  'Entrer le contenu de la page détail en anglais',
                        'name'  =>  'content_ang',
                        'value' =>  !is_null($about) ? $about->content_ang : null,
                        'id'    =>  'wysiwyg_accordion_2'
                    ]) ?>
               </div>
           </div>
            <img class="ui medium bordered image" src="../../webroot/img/bg-img/<?= !is_null($about) ? $about->image : null ?>"> Choississez une nouvelle image pour mofier celle-ci :
            <?= Form::file([
                'name'  =>  'about_img'
            ]) ?>
            <?= Form::button('Modifier') ?>
        </form>
    </div>
</div>