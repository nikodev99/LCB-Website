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

$errors = [];

$left = Model::getLeftExpose(['url' => $params['slug'], 'url_ang' => $params['slug']]);

$redirect_url = $router->url('page.index', ['slug' => $params['slug']]);

if (!$p->isEmpty()) {
    $title = $p->get('title');
    $title_ang = $p->get('title_ang');
    $introduction = $p->get('introduction');
    $introduction_ang = $p->get('introduction_ang');
    $url = $p->get('redirect_url');
    $url_ang = $p->get('redirect_url_ang');
    $image = $_FILES['left_expose_img'] ?? null;

    foreach($p->get() as $key => $value) {
        if ($p->isEmpty($key)) {
            $errors[$key] = 'Ce champs est vide';
        }
        if ($key == 'redirect_url' | $key == 'redirect_url_ang') {
            if(count(explode(' ', $p->get($key))) > 1) $errors[$key] = "Evitez les espaces dans le nom des liens";
        }
    }

    if(empty($errors)) {
        $file_name = !is_null($left) ? $left->image : null;
        if (!empty($image['name'])) {
            $img = new ImgUpload($image);
            $file_name = $img->newFilename();
            $img->upload($file_name, $redirect_url, 1920, 1280);
        }
        Model::updateLeftExpose(['url' => $params['slug'], 'url_ang' => $params['slug']], [
            'title'    =>  $title,
            'title_ang'    =>  $title_ang,
            'introduction'  =>  $introduction,
            'introduction_ang'  =>  $introduction_ang,
            'redirect_url'  =>  $url,
            'redirect_url_ang'  =>  $url_ang,
            'image' =>  $file_name,
        ]);
        $page = Model::getPage(['url' => $url]);
        if (is_null($page)) {
            Model::insertPage([
                'url'   =>  $url,
                'url_ang'   =>  $url_ang,
                'content'   =>  null,
                'created'   =>  time(),
                'updated'   =>  null,
                'online'    =>  0,
                'navbar_id' =>  0
            ]);
        } 
        $flash->success('Modifications effectuées avec success !');
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
                        'value' =>  !is_null($left) ? $left->title : null
                    ], $errors) ?>
                    <?= Form::text([
                        'label' =>  "Titre de la section en anglais",
                        'name'  =>  'title_ang',
                        'value' =>  !is_null($left) ? $left->title_ang : null
                    ], $errors) ?>
                </div>
            </div>
            <div class="could go">
               <div class="two fields">
               <?= Form::text([
                        'label' =>  "Lien de redirection vers les détails de la section",
                        'name'  =>  'redirect_url',
                        'value' =>  !is_null($left) ? $left->redirect_url : null
                    ], $errors) ?>
                    <?= Form::text([
                        'label' =>  "Lien de redirection vers les détails de la section en anglais",
                        'name'  =>  'redirect_url_ang',
                        'value' =>  !is_null($left) ? $left->redirect_url_ang : null
                    ], $errors) ?>
               </div>
           </div>
           <div class="could go">
               <div class="two fields">
                    <?= Form::textarea([
                        'name'  =>  'introduction',
                        'value' =>  !is_null($left) ? $left->introduction : null,
                    ], $errors) ?>
                    <?= Form::textarea([
                        'name'  =>  'introduction_ang',
                        'value' =>  !is_null($left) ? $left->introduction_ang : null,
                    ], $errors) ?>
               </div>
           </div>
            <img class="ui medium bordered image" src="../../webroot/img/bg-img/<?= !is_null($left) ? $left->image : null ?>"> Choississez une nouvelle image pour mofier celle-ci :
            <?= Form::file([
                'name'  =>  'left_expose_img'
            ]) ?>
            <?= Form::button('Modifier', 'primary') ?>
        </form>
    </div>
</div>