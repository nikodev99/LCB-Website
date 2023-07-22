<?php

use Web\App\Core\Model;
use Web\App\Components\Form;
use Web\App\Core\AppFunction;
use Web\App\Components\Component;

AppFunction::user_not_connected($router->url('admin.index'));

$slides = Model::getAllCarousel();

$redirect_url = $router->url('homepage.index');

?>

<p style="margin-left:12px;"><?= Component::back_button($redirect_url) ?><p>
<div class="ui divider"></div>

<div class="ui grid stackable padded">
    <?php foreach($slides as $slide): ?>
    <div class="four wide computer eight wide tablet sixteen wide mobile column">
        <div class="ui fluid card">
            <div class="image">
                <img src="../../webroot/img/bg-img/<?= $slide->image ?>">
            </div>
            <div class="content">
                <div class="header"><?= $slide->title ?></div>
                <div class="description">
                    <?= $slide->description ?>
                </div>
            </div>
            <div class="extra content">
                <span class="right floated">
                <?= Component::ui_modify_anchor($router->url('carousel.modify', ['id' => $slide->id])) ?>
                </span>
                <span>
                    <?= Component::removal($router->url('homepage.remove.carousel', ['id' => $slide->id]), 'cet annonce') ?>
                </span>
            </div>
        </div>
    </div>
    <?php endforeach ?>
    <div class="four wide computer eight wide tablet sixteen wide mobile column">
        <div class="ui card">
            <div class="image">
               <button class="ui button" style="cursor:pointer;" data-modal-target="add_carousel"><img src="../webroot/img/bg-img/plus.png"></button>
            </div>
        </div>
    </div>
</div>

<div class="ui modal" data-modal="add_carousel">
    <div class="header">
        Ajouter une nouvelle annonce
    </div>
    <div class=" content">
        <form class="ui fluid form" action="<?= $router->url('homepage.add.carousel') ?>" method="POST" enctype="multipart/form-data">
        <div class="could go">
               <div class="two fields">
                    <?= Form::text([
                        'label' =>  "Le titre de l'annonce",
                        'name'  =>  'title',
                        'placeholder'   =>  'Entrer le titre de l\'annonce'
                    ]) ?>
                    <?= Form::text([
                        'label' =>  "Le titre de l'annonce en anglais",
                        'name'  =>  'title_ang',
                        'placeholder'   =>  'Entrer le titre de l\'annonce en anglais'
                    ]) ?>
               </div>
           </div>
           <div class="could go">
               <div class="two fields">
               <?= Form::text([
                        'label' =>  "lien de l'annonce",
                        'name'  =>  'url',
                        'placeholder'   =>  'Entrer le lien de l\'annonce'
                    ]) ?>
                    <?= Form::text([
                        'label' =>  "Le titre de l'annonce en anglais",
                        'name'  =>  'url_ang',
                        'placeholder'   =>  'Entrer le lien de l\'annonce en anglais'
                    ]) ?>
               </div>
           </div>
           <div class="could go">
               <div class="two fields">
                    <?= Form::textarea([
                        'name'  =>  'description',
                        'placeholder'   =>  'Entrer la description de l\'annonce'
                    ]) ?>
                    <?= Form::textarea([
                        'name'  =>  'description_ang',
                        'placeholder'   =>  'Entrer la description de l\'annonce en anglais'
                    ]) ?>
               </div>
           </div>
            <?= Form::file([
                'name'  =>  'carousel_img'
            ]) ?>
            <?= Form::button('Ajouter') ?>
        </form>
    </div>
</div>