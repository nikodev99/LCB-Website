<?php

use Web\App\Core\Model;
use Web\App\Components\Form;
use Web\App\Core\AppFunction;
use Web\App\Components\Component;

AppFunction::user_not_connected($router->url('admin.index'));

$redirect_url = $router->url('homepage.index');

$features = Model::getAllFeature();

?>

<p style="margin-left:12px;"><?= Component::back_button($router->url('homepage.index')) ?><p>
<div class="ui divider"></div>
<div class="ui grid stackable padded">
    <?php foreach($features as $feature): 
        if (!empty($feature->title)):
        ?>
    <div class="four wide computer eight wide tablet sixteen wide mobile column">
        <div class="ui fluid card">
            <?php if (!empty($feature->image)): ?> 
            <div class="image">
                <img src="../../webroot/img/bg-img/<?= $feature->image ?>">
            </div>
            <?php endif ?> 
            <div class="content">
                <div class="header"><?= !empty($feature->title) ? $feature->title : $feature->header ?></div>
                <?php if (!empty($feature->description)): ?> 
                <div class="description">
                    <?= $feature->description ?>
                </div>
                <?php endif ?> 
            </div>
            <div class="extra content">
                <span class="right floated">
                <?= Component::ui_modify_anchor($router->url('feature.modify', ['id' => $feature->id])) ?>
                </span>
                <span>
                    <?= Component::removal($router->url('homepage.remove.feature', ['id' => $feature->id]), 'cet annonce') ?>
                </span>
            </div>
        </div>
    </div>
    <?php endif; endforeach ?>
    <div class="four wide computer eight wide tablet sixteen wide mobile column">
        <div class="ui card">
            <div class="image">
               <button class="ui button" style="cursor:pointer" data-modal-target="add_feature"><img src="../webroot/img/bg-img/plus.png"></button>
            </div>
        </div>
    </div>
</div>

<div class="ui modal" data-modal="add_feature">
    <div class="header">
        Ajouter une nouvelle annonce
    </div>
    <div class=" content">
        <form class="ui fluid form" action="<?= $router->url('homepage.add.feature') ?>" method="POST" enctype="multipart/form-data">
        <div class="could go">
               <div class="two fields">
                    <?= Form::text([
                        'label' =>  "Titre",
                        'name'  =>  'title',
                        'placeholder'   =>  'Entrer le titre'
                    ]) ?>
                    <?= Form::text([
                        'label' =>  "Titre en anglais",
                        'name'  =>  'title_ang',
                        'placeholder'   =>  'Entrer le titre en anglais'
                    ]) ?>
               </div>
           </div>
           <div class="could go">
               <div class="two fields">
               <?= Form::text([
                        'label' =>  "Lien",
                        'name'  =>  'url',
                        'placeholder'   =>  'Entrer le lien'
                    ]) ?>
                    <?= Form::text([
                        'label' =>  "Lien en anglais",
                        'name'  =>  'url_ang',
                        'placeholder'   =>  'Entrer le lien en anglais'
                    ]) ?>
               </div>
           </div>
           <div class="could go">
               <div class="two fields">
                    <?= Form::textarea([
                        'name'  =>  'description',
                        'placeholder'   =>  'Entrer la description'
                    ]) ?>
                    <?= Form::textarea([
                        'name'  =>  'description_ang',
                        'placeholder'   =>  'Entrer la description en anglais'
                    ]) ?>
               </div>
           </div>
            <?= Form::file([
                'name'  =>  'feature_img'
            ]) ?>
            <?= Form::button('Ajouter') ?>
        </form>
    </div>
</div>