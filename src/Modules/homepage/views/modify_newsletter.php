<?php

use Web\App\Core\Model;
use Web\App\Components\Form;
use Web\App\Core\AppFunction;
use Web\App\Components\Component;

AppFunction::user_not_connected($router->url('admin.index'));

$redirect_url = $router->url('homepage.index');

$newsletter = Model::getNewsletterInfos(['id' => 1]);

?>

<div class="ui grid stackable padded">
    <div class="column">
    <?= Component::back_button($redirect_url) ?>
    <div class="ui divider"></div>
        <form action="<?= $router->url('homepage.newsletter.modify') ?>" method="post" enctype="multipart/form-data" class="ui fluid form">
            <h1 class="ui dividing header">Modification</h1>
            <div class="could go">
                <div class="two fields">
                    <?= Form::text([
                        'label' =>  "Le titre",
                        'name'  =>  'title',
                        'value' =>  !is_null($newsletter) ? $newsletter->title : null
                    ]) ?>
                    <?= Form::text([
                        'label' =>  "Le titre en anglais",
                        'name'  =>  'title_ang',
                        'value' =>  !is_null($newsletter) ? $newsletter->title_ang : null
                    ]) ?>
                </div>
            </div>
            <div class="could go">
                <div class="two fields">
                    <?= Form::textarea([
                        'label'   =>  'Description',
                        'name'  =>  'description',
                        'value' =>  !is_null($newsletter) ? $newsletter->description : null
                    ]) ?>
                    <?= Form::textarea([
                        'label'   =>  'Description en anglais',
                        'name'  =>  'description_ang',
                        'value' =>  !is_null($newsletter) ? $newsletter->description_ang : null
                    ]) ?>
                </div>
            </div>
            <img class="ui medium bordered image" src="../../webroot/img/bg-img/<?= !is_null($newsletter) ? $newsletter->image : null ?>"> Choississez une nouvelle image pour mofier celle-ci :
            <?= Form::file([
                'name'  =>  'newsletter_img'
            ]) ?>
            <?= Form::button('Modifier') ?>
        </form>
    </div>
</div>