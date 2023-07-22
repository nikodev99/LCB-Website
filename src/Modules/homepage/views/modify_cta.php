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

$cta = Model::cta(['id'  =>  1]);

$page = null;
$condition = [];
if (!is_null($cta->url)) $page = Model::getPage(['url' => $cta->url]);
if (!is_null($cta->url)) $condition = ['url' => $cta->url];
$bread = !empty($condition) ? Model::getBreadcrumb($condition) : null;
$text = !empty($condition) ? Model::getText($condition) : null;
$skills = [];
$skills_ang = [];
if (!is_null($cta) && !is_null($cta->skills)) {
    for($i = 0; $i < count(json_decode($cta->skills, true)); $i++) {
        $skills[] = implode(';', json_decode($cta->skills, true)[$i]);
        $skills_ang[] = implode(';', json_decode($cta->skills_ang, true)[$i]);
    }
}
$errors = [];
$redirect_url = $router->url('homepage.modify.cta');

if (!$p->isEmpty()) {

    $main_title = $p->get('main_title');
    $main_title_ang = $p->get('main_title_ang');
    $title = $p->get('title');
    $title_ang = $p->get('title_ang');
    $url = $p->get('url');
    $url_ang = $p->get('url_ang');
    $description = $p->get('description');
    $description_ang = $p->get('description_ang');
    $skilles = $p->get('skills');
    $skilles_ang = $p->get('skills_ang');
    $content = $p->get('content');
    $content_ang = $p->get('content_ang');
    $image = $_FILES['cta_img'] ?? null;

    $empty_fields = ['url', 'url_ang', 'content', 'content_ang', 'skills','skills_ang'];
    foreach($p->get() as $key => $value) {
        if (empty($p->get($key))) {
            if(!in_array($key, $empty_fields)) $errors[$key] = 'Ce champs est vide. Veuillez le remplir';
        }
    }

    $skill = !empty($skilles) ? AppFunction::array_trim(explode('|', $skilles)) : null;
    $skill_ang = !empty($skilles_ang) ? AppFunction::array_trim(explode('|', $skilles_ang)) : null;
    $a = [];
    $b = [];
    $skills_content = [];
    $skills_content_ang = [];
    if (!is_null($skill) && !is_null($skill_ang)) {
        foreach($skill as $key => $kill) {
            $ex = explode(';', $kill);
            $a['percent'] = $ex[0];
            $a['description'] = $ex[1];
            $skills_content[] = $a;
        }
        foreach($skill_ang as $key => $kill) {
            $ex_ang = explode(';', $kill);
            $b['percent'] = $ex_ang[0];
            $b['description'] = $ex_ang[1];
            $skills_content_ang[] = $b;
        }
    }
    
    if(empty($errors)) {
        $file_name = !is_null($cta) ? $cta->image : null;
        if (!empty($image['name'])) {
            $img = new ImgUpload($image);
            $file_name = $img->newFilename();
            $img->upload($file_name, $redirect_url, 1920, 1280);
        }
        if(is_null($page) && !is_null($url) && !is_null($url_ang)) {
            $id = Model::insertSlug([
                'url'   =>  $url,
                'url_ang'   =>  $url_ang,
                'content'   =>  json_encode(['banniere', 'text']),
                'created'   =>  time(),
                'updated'   =>  null,
                'online'    =>  0,
                'navbar_id' =>  0
            ], true);
            Model::insertMetadata([
                'title' =>  $title,
                'title_ang' =>  $title_ang,
                'description'   =>  $description,
                'description_ang'   =>  $description,
                'url'       =>  $url,
                'url_ang'   =>  $url_ang,
                'url_id'    =>  $id
            ]);
            if(is_null($bread)) {
                Model::insertBreadcrumb([
                    'header'    =>  $title,
                    'header_ang'=>  $title_ang,
                    'background' =>  $file_name,
                    'url'       =>  $url,
                    'url_ang'   =>  $url_ang,
                    'url_id'    =>  $id
                ]);
            }
            if(is_null($text)) {
                Model::insertNewText([
                    'title' =>  $title,
                    'title_ang' => $title_ang,
                    'description'   =>  null,
                    'description_ang'   => null,
                    'content'   => $content,
                    'content_ang'   => $content_ang,
                    'url'       =>  $url,
                    'url_ang'   =>  $url_ang,
                    'url_id'    =>  $id
                ]);
            }
            Model::insertCTA([
                'image' =>  $file_name,
                'main_title'    =>  $main_title,
                'main_title_ang'    =>  $main_title_ang,
                'title' =>  $title,
                'title_ang' =>  $title_ang,
                'description'   =>  $description,
                'description_ang'   =>  $description_ang,
                'skills'    =>  json_encode($skills_content),
                'skills_ang'    =>  json_encode($skills_content_ang)
            ]);
        }else {
            if (!empty($condition)) {
                Model::updateBreadcrumb($condition, [
                    'header'    =>  $title,
                    'header_ang'=>  $title_ang,
                    'background' =>  $file_name
                ]);
                Model::updateText($condition, [
                    'title' =>  $title,
                    'title_ang' => $title_ang,
                    'description'   =>  null,
                    'description_ang'   => null,
                    'content'   => $content,
                    'content_ang'   => $content_ang
                ]);
                Model::updateMetadata($condition, [
                    'title' =>  $title,
                    'title_ang' =>  $title_ang,
                    'description'   =>  $description,
                    'description_ang'   =>  $description,
                ]);
                Model::updatePage(['id' => $page->id], [
                    'url'   =>  $url,
                    'url_ang'   =>  $url_ang,
                    'updated'   =>  time(),
                ]);
                Model::updateCTA($condition, [
                    'image' =>  $file_name,
                    'main_title'    =>  $main_title,
                    'main_title_ang'    =>  $main_title_ang,
                    'title' =>  $title,
                    'title_ang' =>  $title_ang,
                    'description'   =>  $description,
                    'description_ang'   =>  $description_ang,
                    'skills'    =>  json_encode($skills_content),
                    'skills_ang'    =>  json_encode($skills_content_ang)
                ]);
            }else {
                Model::updateCTA(["id" => 1], [
                    'image' =>  $file_name,
                    'main_title'    =>  $main_title,
                    'main_title_ang'    =>  $main_title_ang,
                    'title' =>  $title,
                    'title_ang' =>  $title_ang,
                    'description'   =>  $description,
                    'description_ang'   =>  $description_ang,
                    'skills'    =>  json_encode($skills_content),
                    'skills_ang'    =>  json_encode($skills_content_ang)
                ]);
            }
        }
        $flash->success("Modifications effectuées avec succès.");
    }
    $flash->error('Erreur rencontrer lors de la modification');
}


?>

<div class="ui grid stackable padded">
    <div class="column">
    <?= Component::back_button($router->url('homepage.index')) ?>
    <div class="ui divider"></div>
        <form action="" method="post" enctype="multipart/form-data" class="ui fluid form">
            <h1 class="ui dividing header">Modification de l'annonce</h1>
            <div class="could go">
                <div class="two fields">
                    <?= Form::text([
                        'label' =>  "Le titre principal",
                        'name'  =>  'main_title',
                        'value' =>  !is_null($cta) ? $cta->main_title : null
                    ], $errors) ?>
                    <?= Form::text([
                        'label' =>  "Le titre principal en anglais",
                        'name'  =>  'main_title_ang',
                        'value' =>  !is_null($cta) ? $cta->main_title_ang : null
                    ], $errors) ?>
                </div>
            </div>
            <div class="could go">
                <div class="two fields">
                    <?= Form::text([
                        'label' =>  "Le titre de l'annonce",
                        'name'  =>  'title',
                        'value' =>  !is_null($cta) ? $cta->title : null
                    ], $errors) ?>
                    <?= Form::text([
                        'label' =>  "Le titre de l'annonce en anglais",
                        'name'  =>  'title_ang',
                        'value' =>  !is_null($cta) ? $cta->title_ang : null
                    ], $errors) ?>
                </div>
            </div>
            <div class="could go">
               <div class="two fields">
               <?= Form::text([
                        'label' =>  "lien de l'annonce",
                        'name'  =>  'url',
                        'value' =>  !is_null($cta) ? $cta->url : null
                    ], $errors) ?>
                    <?= Form::text([
                        'label' =>  "Le lien de l'annonce en anglais",
                        'name'  =>  'url_ang',
                        'value' =>  !is_null($cta) ? $cta->url_ang : null
                    ], $errors) ?>
               </div>
           </div>
            <div class="could go">
                <div class="two fields">
                    <?= Form::textarea([
                        'label'   =>  'Description de l\'annonce',
                        'name'  =>  'description',
                        'value' =>  !is_null($cta) ? $cta->description : null
                    ]) ?>
                    <?= Form::textarea([
                        'label'   =>  'Description de l\'annonce en anglais',
                        'name'  =>  'description_ang',
                        'value' =>  !is_null($cta) ? $cta->description_ang : null
                    ]) ?>
                </div>
            </div>
            <div class="could go">
                <div class="two fields">
                    <?= Form::text([
                        'label'   =>  'Illustration ex: 60;2009 | 80;2010 | 45;2011',
                        'name'  =>  'skills',
                        'value' =>  !is_null($cta) ? implode(' | ', $skills) : null
                    ], $errors) ?>
                    <?= Form::text([
                        'label'   =>  'Illustration en anglais',
                        'name'  =>  'skills_ang',
                        'value' =>  !is_null($cta) ? implode(' | ', $skills_ang) : null
                    ], $errors) ?>
                </div>
            </div>
            <div class="could go">
               <div class="two fields">
               <?= Form::textarea([
                        'name'  =>  'content',
                        'label'   =>  'Entrer le contenu de votre annonce',
                        'value'   =>  !is_null($text) ? $text->content : null,
                        'id'    =>  'wysiwyg_accordion'
                    ]) ?>
                    <?= Form::textarea([
                        'name'  =>  'content_ang',
                        'label'   =>  'Entrer le contenu de votre annonce en anglais',
                        'value'   =>  !is_null($text) ? $text->content_ang : null,
                        'id'    =>  'wysiwyg_accordion_2'
                    ]) ?>
               </div>
           </div>
            <img class="ui medium bordered image" src="../../webroot/img/bg-img/<?= !is_null($cta) ? $cta->image : null ?>"> Choississez une nouvelle image pour mofier celle-ci :
            <?= Form::file([
                'name'  =>  'cta_img'
            ]) ?>
            <?= Form::button('Modifier') ?>
        </form>
    </div>
</div>