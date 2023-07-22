<?php

use Web\App\Components\Form;
use Web\App\Core\AppFunction;
use Web\App\Components\PageRender;

AppFunction::user_not_connected($router->url('admin.index'));

$content = new PageRender();

$page = [];

?>
<nav class="menu-decider">
    <div class="row">
        <div class="col-lg-6">
            <?= Form::input([
                'placeholder'   =>  'Ajouter un titre a la nouvelle page que vous créez',
                'name'  =>  'title',
                'form'  =>  'page-creator',
                'required'  => true
            ], [], 4, 'mb-3') ?>
        </div>
        <div class="col-lg-6">
            <?= Form::input([
                'placeholder'   =>  'Ajouter un titre en anglais à la page que vous créez',
                'name'  =>  'title_ang',
                'form'  =>  'page-creator',
                'required'  => true
            ], [], 4, 'mb-3') ?>
        </div>
    </div>
</nav>
<nav class="menu-decider">
    <div class="row">
        <div class="col-lg-6">
            <?= Form::textarea([
                'placeholder'   =>  'Ajouter une description à la page que vous créez',
                'name'  =>  'description',
                'form'  =>  'page-creator'
            ], [], 4, 'mb-3') ?>
        </div>
        <div class="col-lg-6">
            <?= Form::textarea([
                'placeholder'   =>  'Ajouter une description en anglais à la page que vous créez',
                'name'  =>  'description_ang',
                'form'  =>  'page-creator'
            ], [], 4, 'mb-3') ?>
        </div>
    </div>
</nav>
<nav class="menu-decider">
    <div class="decider">
        <?= Form::check([
            'label' =>  'Espace "Banniere"',
            'name'  =>  'page_content[]',
            'value'  =>  'banniere',
            'form'  =>  'page-creator',
            'data'  =>  true
        ]) ?>
    </div>
    <div class="decider">
        <?= Form::check([
            'label' =>  'Espace description "A propos"',
            'name'  =>  'page_content[]',
            'value'  => 'about',
            'form'  =>  'page-creator',
            'data'  =>  true
        ]) ?>
    </div>
    <div class="decider">
        <?= Form::check([
            'label' =>  'Espace "Exposition"',
            'name'  =>  'page_content[]',
            'value'  =>  'expose',
            'form'  =>  'page-creator',
            'data'  =>  true
        ]) ?>
    </div>
    <div class="decider">
        <?= Form::check([
            'label' =>  'Espace "Accordion"',
            'name'  =>  'page_content[]',  
            'value' =>  'accordion',
            'form'  =>  'page-creator',
            'data'  =>  true
        ]) ?>
    </div>
    <div class="decider">
        <?= Form::check([
            'label' =>  'Espace "Exposition type 2"',
            'name'  =>  'page_content[]',  
            'value' =>  'left_expose',
            'form'  =>  'page-creator',
            'data'  =>  true
        ]) ?>
    </div>
    <div class="decider">
        <?= Form::check([
            'label' =>  'Espace "Tabs"',
            'name'  =>  'page_content[]',  
            'value' =>  'tabs',
            'form'  =>  'page-creator',
            'data'  =>  true
        ]) ?>
    </div>
    <div class="decider">
        <?= Form::check([
            'label' =>  'Espace "Text"',
            'name'  =>  'page_content[]',
            'value' =>  'text',
            'form'  =>  'page-creator',
            'data'  =>  true
        ]) ?>
    </div>
    <div class="decider">
        <?= Form::check([
            'label' =>  'Espace "Reason"',
            'name'  =>  'page_content[]',
            'value' =>  'reason',
            'form'  =>  'page-creator',
            'data'  =>  true
        ]) ?>
    </div>
    <div class="decider">
        <?= Form::check([
            'label' =>  'Espace "Work"',
            'name'  =>  'page_content[]',
            'value' =>  'work',
            'form'  =>  'page-creator',
            'data'  =>  true
        ]) ?>
    </div>
    <div class="decider">
        <?= Form::check([
            'label' =>  'Espace "multiple-feature"',
            'name'  =>  'page_content[]',
            'value' =>  'multiple-feature',
            'form'  =>  'page-creator',
            'data'  =>  true
        ]) ?>
    </div>
    <div class="decider">
        <?= Form::check([
            'label' =>  'Espace "Presentation"',
            'name'  =>  'page_content[]',
            'value' =>  'presentation',
            'form'  =>  'page-creator',
            'data'  =>  true
        ]) ?>
    </div>
</nav>

<!-- ##### Breadcrumb Area Start ##### -->
<?= $content->getBreadcrumb($page) ?>
<!-- ##### Breadcrumb Area End ##### -->

<!-- ##### About Area Start ###### -->
<?= $content->getAboutArea($page) ?>
<!-- ##### About Area End ###### -->

<!-- ##### Call To Action Start ###### -->
<?= $content->getCallToAction($page) ?>
<!-- ##### Call To Action End ###### -->

<!-- ##### FAQ Area Start ###### -->
<?= $content->getAccordion($page) ?>
<!-- ##### FAQ Area End ###### -->

<!-- ##### Special Feature Area Start ###### -->
<?= $content->getLeftFeatures($page) ?>
<!-- ##### Special Feature Area End ###### -->

<!-- ##### Special Feature Area Start ###### -->
<?= $content->getRightFeatures($page) ?>
<!-- ##### Special Feature Area End ###### -->

<!-- ##### Tabs ##### -->
<?= $content->getTabs($page) ?>
<!-- ##### Tabs end #####-->

<!-- ##### Post Details Area Start ##### -->
<?= $content->getTextArea($page) ?>
<!-- ##### Post Details Area End ##### -->

<!-- ##### About Area Start ###### -->
<?= $content->getReasonArea($page) ?>
<!-- ##### About Area End ###### -->

<!-- ##### Multiple feature start ###### -->
<?= $content->getMultipleFeature($page) ?>
<!-- ##### Multiple feature end ###### -->

<!-- ##### Special Feature Area Start ###### -->
<?= $content->getWorkArea($page) ?>
<!-- ##### Special Feature Area End ###### -->

<!-- ##### Left Right section ###### -->
<?= $content->getPresentation($page) ?>
<!-- ##### Left Right section End ###### -->

<form action="<?= $router->url('admin.page.creation', ['slug' => $params['slug']]) ?>" id="page-creator" method="POST" class="m-5">
    <button name="submit" class="mt-3 btn btn-success">Enregistrer</button>
</form>
