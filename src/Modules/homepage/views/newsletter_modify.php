<?php

use Web\App\Core\AppFunction;
use Web\App\Core\Flash;
use Web\App\Core\Model;
use Web\App\Core\session\Post;
use Web\App\Core\upload\ImgUpload;

AppFunction::user_not_connected($router->url('admin.index'));
$p = new Post();

$newsletter = Model::getNewsletterInfos(['id' => 1]);

$flash = new Flash();

$errors = [];

if (!$p->isEmpty()) {

    $title = $p->get('title');
    $title_ang = $p->get('title_ang');
    $description = $p->get('description');
    $description_ang = $p->get('description_ang');
    $image = $_FILES['newsletter_img'] ?? null;

    foreach ($p->get() as $key => $value) {
        if ($p->isEmpty($key)) {
            $errors[$key] = 'Le champ ' . $key . ' est requis.';
        }
    }
    
    if (empty($errors)) {

        $fileName = !is_null($newsletter) ? $newsletter->image : null;

        if (!empty($image['name'])) {
            $img = new ImgUpload($image);
            $fileName = $img->newFilename();
            $img->upload($fileName, $router->url('homepage.modify.newsletter'), 1920, 1280);
        }

        Model::updateNewsletterInfos(['id' => 1], [
            'image' =>  $fileName,
            'title' =>  $title,
            'title_ang' =>  $title_ang,
            'description'   =>  $description,
            'description_ang'   =>  $description_ang
        ]);

        AppFunction::successMessage('Modification effectuée avec success !', $router->url('homepage.modify.newsletter'));
    
    }else {
        AppFunction::errorMessage($errors, $router->url('homepage.modify.newsletter'));
    }

}

?>