<?php

use Web\App\Core\Flash;
use Web\App\Core\Model;
use Web\App\Components\Form;
use Web\App\Core\AppFunction;
use Web\App\Core\session\Post;
use Web\App\Core\upload\ImgUpload;

AppFunction::user_not_connected($router->url('admin.index'));
$p = new Post();

$errors = [];
$blog = Model::getPost(['id' => $params['id']]);
$conditions = ['url' => $blog->slug];
$page = Model::getPage($conditions);
$ba = Model::getBreadcrumb(['url_id' => $page->id]);

$flash = new Flash();

$redirect_url = $router->url('admin.posts');

if (!$p->isEmpty()) {

    $title = $p->get('title') ?? $blog->title;
    $title_ang = $p->get('title_ang') ?? $blog->title_ang;
    $slug = $p->get('slug') ?? $blog->slug;
    $slug_ang = $p->get('slug_ang') ?? $blog->slug_ang;
    $intro = $p->get('excerpt') ?? $blog->excerpt;
    $intro_ang = $p->get('excerpt_ang') ?? $blog->excerpt_ang;
    $content = $p->get('content') ?? $blog->content;
    $content_ang = $p->get('content_ang') ?? $blog->content_ang;
    $image = $_FILES['post-img'] ?? null;

    if(empty($title) | is_null($title)) $errors['title'] = "Le champs titre de l'article est obligatoire.";
    if(empty($title_ang) | is_null($title_ang)) $errors['title_ang'] = "Le champs titre de l'article en anglais est obligatoire.";
    if(empty($intro) | is_null($intro)) $errors[] = "Le champs introduction de l'article est obligatoire. Cette introduction est utilisée dans la page d'acceuil.";
    if(empty($intro_ang) | is_null($intro_ang)) $errors[] = "Le champs introduction de l'article en anglais est obligatoire. Cette introduction est utilisée dans la page d'acceuil.";
    if(empty($content) | is_null($content)) $errors[] = "Le champs contenu de l'article en anglais est obligatoire.";
    if(empty($content) | is_null($content)) $errors[] = "Le champs contenu de l'article en anglais est obligatoire. Cette introduction est utilisée dans la page d'acceuil.";
    if(empty($slug) | is_null($slug)) $errors['slug'] = "Le champs slug est obligatoire. C'est le lien de l'article.";
    if(empty($slug_ang) | is_null($slug_ang)) $errors['slug'] = "Le champs slug en anglais est obligatoire. C'est le lien pour afficher l'article en anglais.";

    if (empty($errors)) {
        $file_name = $blog->image;
        $thumbnail = $blog->thumbnail;
        $breadcrump = $blog->breadcrump;
        if (!empty($image['name'])) {
            $img = new ImgUpload($image);
            $file_name = $img->newFilename();
            $img->upload($file_name, $redirect_url, 900, 439);
            $thumbnail = $img->newFilename();
            $img->upload($thumbnail, $redirect_url, 200, 174);
            $breadcrump = $img->newFilename();
            $img->upload($breadcrump, $redirect_url, 1920, 1280);
        }
        Model::updateBreadcrumb(['url_id' => $page->id], [
            'header'    =>  $title,
            'header_ang'=>  $title_ang,
            'background' =>  $breadcrump,
            'breadcrumb' =>  json_encode(['actualites' => 'Actualités']),
            'breadcrumb_ang' => json_encode(['news' => 'News']),
            'url'       =>  $slug,
            'url_ang'   =>  $slug_ang,
        ]);
        Model::updateMetadata(['url_id' => $page->id], [
            'title' =>  $title,
            'title_ang' =>  $title_ang,
            'description'   =>  $intro,
            'description_ang'   =>  $intro_ang
        ]);
        Model::updatePage(['id' => $page->id], [
            'url'   =>  $slug,
            'url_ang'   =>  $slug_ang,
            'updated'   =>  time(),
        ]);
        Model::updatePost(['id' => $params['id']], [
            'image' =>  $file_name,
            'thumbnail' =>  $thumbnail,
            'breadcrump'    =>  $breadcrump,
            'updated'   =>  time(),
            'slug'   =>  $slug,
            'slug_ang'       =>  $slug_ang,
            'title'   =>  $title,
            'title_ang'    =>  $title_ang,
            'excerpt'   =>  $intro,
            'excerpt_ang'   =>  $intro_ang,
            'content'   =>  $content,
            'content_ang'   =>  $content_ang,
        ]);
        AppFunction::successMessage("L'article a été actualisé avec réussite.", $redirect_url);
    }else {
        $flash->error($errors);
    }
}

?>

<div class="ui grid stackable padded">
    <div class="column">
        <form action="" method="post" enctype="multipart/form-data" class="ui fluid form">
            <h1 class="ui dividing header">Modification d'article</h1>
            <div class="could go">
                <div class="two fields">
                    <?= Form::text([
                        'label' =>  "Titre de l'article",
                        'name'  =>  'title',
                        'value'    =>  !is_null($blog) ? $blog->title : null
                    ], $errors) ?>
                    <?= Form::text([
                        'label' =>  "Titre de l'article en anglais",
                        'name'  =>  'title_ang',
                        'value'    =>  !is_null($blog) ? $blog->title_ang : null
                    ], $errors) ?>
                </div>
            </div>
            <div class="could go">
                <div class="two fields">
                    <?= Form::text([
                        'label' =>  "Lien de l'article. Evitez les espaces et l'accentuation. ex: qui_somme_nous",
                        'name'  =>  'slug',
                        'value'    =>  !is_null($blog) ? $blog->slug : null
                    ], $errors) ?>
                    <?= Form::text([
                        'label' =>  "Lien de l'article en anglais. Evitez les espaces et l'accentuation. ex: qui_somme_nous",
                        'name'  =>  'slug_ang',
                        'value'    =>  !is_null($blog) ? $blog->slug_ang : null
                    ], $errors) ?>
                </div>
            </div>
            <div class="could go">
                <div class="two fields">
                    <?= Form::textarea([
                        'name'  =>  'excerpt',
                        'value'    =>  !is_null($blog) ? $blog->excerpt : null
                    ]) ?>
                    <?= Form::textarea([
                        'name'  =>  'excerpt_ang',
                        'value'    =>  !is_null($blog) ? $blog->excerpt_ang : null
                    ]) ?>
                </div>
            </div>
            <div class="could go">
               <div class="two fields">
               <?= Form::textarea([
                        'name'  =>  'content',
                        'value'   =>  !is_null($blog) ? $blog->content : null,
                        'id'    =>  'wysiwyg_accordion'
                    ]) ?>
                    <?= Form::textarea([
                        'name'  =>  'content_ang',
                        'value'   =>  !is_null($blog) ? $blog->content_ang : null,
                        'id'    =>  'wysiwyg_accordion_2'
                    ]) ?>
               </div>
           </div>
           <img class="ui medium bordered image" src="<?= !is_null($blog) ? AppFunction::getImgRoute($blog->image) : null ?>"> Choississez une nouvelle image pour remplacer celle-ci :
           <?= Form::file([
                'name'  =>  'post-img'
            ]) ?>
            <?= Form::button('Modifier l\'article') ?>
        </form>
    </div>
</div>