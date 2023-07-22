<?php

use Web\App\Core\AppFunction;
use Web\App\Core\Model;
use Web\App\Core\session\Post;
use Web\App\Core\upload\ImgUpload;

AppFunction::user_not_connected($router->url('admin.index'));
$p = new Post();

$limit_description = 500;
$width = 1920;
$height = 1280;
$errors = [];

$image = $_FILES['carousel_img'] ?? null;
$timestamp = (new DateTime())->getTimestamp();
$redirect_url = $router->url('homepage.modify.carousel');

if (!$p->isEmpty()) {
    $title = trim(htmlentities($p->get('title'))) ?? null;
    $title_ang = trim(htmlentities($p->get('title_ang'))) ?? null;
    $description = trim(htmlentities($p->get('description'))) ?? null;
    $description_ang = trim(htmlentities($p->get('description_ang'))) ?? null;
    $url = trim(htmlentities($p->get('url'))) ?? null;
    $url_ang = trim(htmlentities($p->get('url_ang'))) ?? null;
    $image = $_FILES['carousel_img'] ?? null;

    $titre = str_replace(['{%', '%}'], ['', ''], $title);
    $titre_ang = str_replace(['{%', '%}'], ['', ''], $title_ang);

    if(empty($title) | empty($title_ang))
        $errors[] = 'Erreur. Un ou plusieurs champs sont vide, l\'annonce ne peut pas être ajouté';
    if(strlen($description) > $limit_description | strlen($description_ang) > $limit_description)
        $errors[] = 'Erreur. La description en français et en anglais ne peux pas être superieur à '.$limit_description.' caractères';
    if(empty($image['name'])) $errors[] = 'Erreur. Une annonce doit être accompagnée d\'une image. Pensé a télécharger une';
    if(empty($url) | empty($url_ang)) $errors[] = 'Erreur. Une annonce doit être avoir un lien (français et en anglais) qui mène vers les détails de l\'annonce';
    
    if(empty($errors)) {
        $file_name = null;
        if(!empty($image['name'])) {
            $file = new ImgUpload($image);
            $file_name = $file->newFilename();
            $file->upload($file_name, $redirect_url, $width, $height);
        }
        $car = Model::getCarousel(['title' => $title]);
        if (is_null($car)):
            Model::insertCarousel([
                'image' =>  $file_name,
                'title' =>  $title,
                'title_ang' =>  $title_ang,
                'description'   =>  $description,
                'description_ang'   =>  $description_ang,
                'url'   =>  $url,
                'url_ang'   =>  $url_ang
            ]);
            $id = Model::insertSlug([
                'url'   =>  $url,
                'url_ang'   =>  $url_ang,
                'content'   =>  json_encode(['banniere', 'text']),
                'created'   =>  $timestamp,
                'updated'   =>  null,
                'online'    =>  0,
                'navbar_id' =>  0
            ], true);
            Model::insertBreadcrumb([
                'header'    =>  $titre,
                'header_ang'=>  $titre_ang,
                'background' =>  $file_name,
                'url'       =>  $url,
                'url_ang'   =>  $url_ang,
                'url_id'    =>  $id
            ]);
            Model::insertNewText([
                'title' =>  $titre,
                'title_ang' => $titre_ang,
                'description'   =>  null,
                'description_ang'   => null,
                'content'   => null,
                'content_ang'   => null,
                'url'       =>  $url,
                'url_ang'   =>  $url_ang,
                'url_id'    =>  $id
            ]);
            Model::insertMetadata([
                'title' =>  $titre,
                'title_ang' =>  $titre_ang,
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
