<?php

use Web\App\Core\Model;
use Web\App\Core\AppFunction;
use Web\App\Core\session\Post;
use Web\App\Core\upload\ImgUpload;

AppFunction::user_not_connected($router->url('admin.index'));
$p = new Post();

$limit_description = 150;
$width = 600;
$height = 676;
$errors = [];

$image = $_FILES['carousel_img'] ?? null;
$timestamp = (new DateTime())->getTimestamp();
$redirect_url = $router->url('homepage.modify.feature');

if (!$p->isEmpty()) {
    $header = $p->get('header') ?? null;
    $header_ang = $p->get('header_ang') ?? null;
    $delay = 200;
    $title = trim(htmlentities($p->get('title'))) ?? null;
    $title_ang = trim(htmlentities($p->get('title_ang'))) ?? null;
    $description = trim(htmlentities($p->get('description'))) ?? null;
    $description_ang = trim(htmlentities($p->get('description_ang'))) ?? null;
    $url = trim(htmlentities($p->get('url'))) ?? null;
    $url_ang = trim(htmlentities($p->get('url_ang'))) ?? null;
    $image = $_FILES['feature_img'] ?? null;

    if(empty($title) | empty($title_ang))
        $errors[] = 'Erreur. Un ou plusieurs champs sont vide, l\'annonce ne peut pas être ajouté';
    if(strlen($description) > $limit_description | strlen($description_ang) > $limit_description)
        $errors[] = 'Erreur. La description en français et en anglais ne peux pas être superieur à '.$limit_description.' caractères';
    if(empty($image['name'])) $errors[] = 'Erreur. Une annonce doit être accompagnée d\'une image. Pensé a télécharger une';
    if(empty($url) | empty($url_ang)) $errors[] = 'Erreur. Une annonce doit être avoir un lien (français et en anglais) qui mène vers les détails de l\'annonce';
    
    if(empty($errors)) {
        $file_name = null;
        $breadcrump = null;
        if(!empty($image['name'])) {
            $file = new ImgUpload($image);
            $file_name = $file->newFilename();
            $file->upload($file_name, $redirect_url, $width, $height);
            $breadcrump = $file->newFilename();
            $file->upload($breadcrump, $redirect_url, 1920, 1280);
        }
        $feat = Model::getFeature(['title' => $title]);
        if (is_null($feat)):
            Model::insertFeature([
                'header' =>  $header,
                'header_ang' =>  $header,
                'image' =>  $file_name,
                'title'   =>  $title,
                'title_ang'   =>  $title_ang,
                'description'   =>  $description,
                'description_ang'   =>  $description_ang,
                'url'   =>  $url,
                'url_ang'   =>  $url_ang
            ]);
            $id = Model::insertSlug([
                'url'   =>  $url,
                'url_ang'   =>  $url_ang,
                'content'   =>  json_encode(['banniere', 'text']),
                'created'   =>  time(),
                'updated'   =>  null,
                'online'    =>  0,
                'navbar_id' =>  0
            ], true);
            Model::insertBreadcrumb([
                'header'    =>  $title,
                'header_ang'=>  $title_ang,
                'background' =>  $breadcrump,
                'url'       =>  $url,
                'url_ang'   =>  $url_ang,
                'url_id'    =>  $id
            ]);
            Model::insertNewText([
                'title' =>  $title,
                'title_ang' => $title_ang,
                'description'   =>  null,
                'description_ang'   => null,
                'content'   => null,
                'content_ang'   => null,
                'url'       =>  $url,
                'url_ang'   =>  $url_ang,
                'url_id'    =>  $id
            ]);
            Model::insertMetadata([
                'title' =>  $title,
                'title_ang' =>  $title_ang,
                'description'   =>  $description,
                'description_ang'   =>  $description,
                'url'       =>  $url,
                'url_ang'   =>  $url_ang,
                'url_id'    =>  $id
            ]);
            AppFunction::successMessage('Nouvelle annonce ajouté avec succes !', $redirect_url);
        endif;
    }else {
        AppFunction::errorMessage($errors, $redirect_url);
    }
}
