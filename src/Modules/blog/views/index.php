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

$flash = new Flash();

$redirect_url = $router->url('admin.posts');

if (!$p->isEmpty()) {

    $title = $p->get('title');
    $title_ang = $p->get('title_ang');
    $slug = $p->get('slug');
    $slug_ang = $p->get('slug_ang');
    $intro = $p->get('excerpt');
    $intro_ang = $p->get('excerpt_ang');
    $content = $p->get('content');
    $content_ang = $p->get('content_ang');
    $image = $_FILES['post-img'] ?? null;

    if(empty($title) | is_null($title)) $errors['title'] = "Le champs titre de l'article est obligatoire.";
    if(empty($title_ang) | is_null($title_ang)) $errors['title_ang'] = "Le champs titre de l'article en anglais est obligatoire.";
    if(empty($intro) | is_null($intro)) $errors[] = "Le champs introduction de l'article est obligatoire. Cette introduction est utilisée dans la page d'acceuil.";
    if(empty($intro_ang) | is_null($intro_ang)) $errors[] = "Le champs introduction de l'article en anglais est obligatoire. Cette introduction est utilisée dans la page d'acceuil.";
    if(empty($content) | is_null($content)) $errors[] = "Le champs contenu de l'article en anglais est obligatoire.";
    if(empty($content) | is_null($content)) $errors[] = "Le champs contenu de l'article en anglais est obligatoire. Cette introduction est utilisée dans la page d'acceuil.";
    if(empty($slug) | is_null($slug)) $errors['slug'] = "Le champs slug est obligatoire. C'est le lien de l'article.";
    if(empty($slug_ang) | is_null($slug_ang)) $errors['slug'] = "Le champs slug en anglais est obligatoire. C'est le lien pour afficher l'article en anglais.";
    if(empty($image['name']) | is_null($image)) $errors['image'] = "Veuillez ajouter une image pour cet article.";

    if (empty($errors)) {
        $file_name = null;
        $thumbnail = null;
        $breadcrump = null;
        if (!empty($image['name'])) {
            $img = new ImgUpload($image);
            $file_name = $img->newFilename();
            $img->upload($file_name, $redirect_url, 900, 439);
            $thumbnail = $img->newFilename();
            $img->upload($thumbnail, $redirect_url, 200, 174);
            $breadcrump = $img->newFilename();
            $img->upload($breadcrump, $redirect_url, 1920, 1280);
        }
        $url = Model::getPage(['url' => $slug, 'url_ang' => $slug_ang]);
        $post = Model::getPost(['title' => $title]);
        if (is_null($url)) {
            $id = Model::insertSlug([
                'url'   =>  $slug,
                'url_ang'   =>  $slug_ang,
                'content'   =>  json_encode(['banniere', 'single-post']),
                'created'   =>  time(),
                'updated'   =>  null,
                'online'    =>  0,
                'navbar_id' =>  0
            ], true);
            Model::insertBreadcrumb([
                'header'    =>  $title,
                'header_ang'=>  $title_ang,
                'background' =>  $breadcrump,
                'breadcrumb' =>  json_encode(['actualites' => 'Actualités']),
                'breadcrumb_ang' => json_encode(['news' => 'News']),
                'url'       =>  $slug,
                'url_ang'   =>  $slug_ang,
                'url_id'    =>  $id
            ]);
        }  
        if (is_null($post)) {
            Model::insertMetadata([
                'title' =>  $title,
                'title_ang' =>  $title_ang,
                'description'   =>  $intro,
                'description_ang'   =>  $intro_ang,
                'url'       =>  $slug,
                'url_ang'   =>  $slug_ang,
                'url_id'    =>  $id
            ]);
            Model::insertPost([
                'image' =>  $file_name,
                'thumbnail' =>  $thumbnail,
                'date'   =>  time(),
                'breadcrump'    =>  $breadcrump,
                'updated'   =>  null,
                'slug'   =>  $slug,
                'slug_ang'       =>  $slug_ang,
                'title'   =>  $title,
                'title_ang'    =>  $title_ang,
                'excerpt'   =>  $intro,
                'excerpt_ang'   =>  $intro_ang,
                'content'   =>  $content,
                'content_ang'   =>  $content_ang,
                'online'    =>  0
            ]);
            AppFunction::successMessage("Nouvel element enregistrer avec success", $redirect_url);
        }
    }else {
        $flash->error($errors);
    }
}

?>

<div class="ui grid stackable padded">
    <div class="column">
        <form action="" method="post" enctype="multipart/form-data" class="ui fluid form">
            <h1 class="ui dividing header">Ajouter un nouvel article</h1>
            <div class="could go">
                <div class="two fields">
                    <?= Form::text([
                        'label' =>  "Titre de l'article",
                        'name'  =>  'title',
                        'placeholder'    =>  'Entrer le titre de l\'article'
                    ], $errors) ?>
                    <?= Form::text([
                        'label' =>  "Titre de l'article en anglais",
                        'name'  =>  'title_ang',
                        'placeholder'    =>  'Entrer le titre de l\'article en anglais'
                    ], $errors) ?>
                </div>
            </div>
            <div class="could go">
                <div class="two fields">
                    <?= Form::text([
                        'label' =>  "Lien de l'article. Evitez les espaces et l'accentuation. ex: qui_somme_nous",
                        'name'  =>  'slug',
                        'placeholder'    =>  'Donner un slug à l\'article'
                    ], $errors) ?>
                    <?= Form::text([
                        'label' =>  "Lien de l'article en anglais. Evitez les espaces et l'accentuation. ex: qui_somme_nous",
                        'name'  =>  'slug_ang',
                        'placeholder'    =>  'Donner un slug à l\'article en anglais'
                    ], $errors) ?>
                </div>
            </div>
            <div class="could go">
                <div class="two fields">
                    <?= Form::textarea([
                        'name'  =>  'excerpt',
                        'placeholder'    =>  'Entrer l\'introduction de l\'article'
                    ]) ?>
                    <?= Form::textarea([
                        'name'  =>  'excerpt_ang',
                        'placeholder'    =>  'Entrer l\'introduction de l\'article en anglais'
                    ]) ?>
                </div>
            </div>
            <div class="could go">
               <div class="two fields">
               <?= Form::textarea([
                        'name'  =>  'content',
                        'placeholder'   =>  'Entrer le contenu de l\'article',
                        'id'    =>  'wysiwyg_accordion'
                    ]) ?>
                    <?= Form::textarea([
                        'name'  =>  'content_ang',
                        'placeholder'   =>  'Entrer le contenu de l\'article en anglais',
                        'id'    =>  'wysiwyg_accordion_2'
                    ]) ?>
               </div>
           </div>
           <?= Form::file([
                'name'  =>  'post-img'
            ]) ?>
            <?= Form::button('Ajouter l\'article') ?>
        </form>
    </div>
</div>