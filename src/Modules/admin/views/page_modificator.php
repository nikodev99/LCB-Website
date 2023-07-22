<?php

use Web\App\Components\Form;
use Web\App\Core\AppFunction;
use Web\App\Components\PageRender;
use Web\App\Core\Model;

AppFunction::user_not_connected($router->url('admin.index'));

$content = Model::getPage(['url' => $params['slug']], 'content');
$meta = Model::getMetadata(['url' => $params['slug'], 'url_ang' => $params['slug']]);
$page_content = !is_null($content) ? json_decode($content->content, true) : [];
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
                'required'  => true,
                'value' =>  !is_null($meta) ? $meta->title : null
            ], [], 4, 'mb-3') ?>
        </div>
        <div class="col-lg-6">
            <?= Form::input([
                'placeholder'   =>  'Ajouter un titre en anglais à la page que vous créez',
                'name'  =>  'title_ang',
                'form'  =>  'page-creator',
                'required'  => true,
                'value' =>  !is_null($meta) ? $meta->title_ang : null
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
                'form'  =>  'page-creator',
                'value' =>  !is_null($meta) ? $meta->description : null
            ], [], 4, 'mb-3') ?>
        </div>
        <div class="col-lg-6">
            <?= Form::textarea([
                'placeholder'   =>  'Ajouter une description en anglais à la page que vous créez',
                'name'  =>  'description_ang',
                'form'  =>  'page-creator',
                'value' =>  !is_null($meta) ? $meta->description_ang : null
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
            'data'  =>  true,
            'checked'   =>  in_array('banniere', $page_content)
        ]) ?>
    </div>
    <div class="decider">
        <?= Form::check([
            'label' =>  'Espace description "A propos"',
            'name'  =>  'page_content[]',
            'value'  => 'about',
            'form'  =>  'page-creator',
            'data'  =>  true,
            'checked'   =>  in_array('about', $page_content)
        ]) ?>
    </div>
    <div class="decider">
        <?= Form::check([
            'label' =>  'Espace "Exposition"',
            'name'  =>  'page_content[]',
            'value'  =>  'expose',
            'form'  =>  'page-creator',
            'data'  =>  true,
            'checked'   =>  in_array('expose', $page_content)
        ]) ?>
    </div>
    <div class="decider">
        <?= Form::check([
            'label' =>  'Espace "Accordion"',
            'name'  =>  'page_content[]',  
            'value' =>  'accordion',
            'form'  =>  'page-creator',
            'data'  =>  true,
            'checked'   =>  in_array('accordion', $page_content)
        ]) ?>
    </div>
    <div class="decider">
        <?= Form::check([
            'label' =>  'Espace "Exposition type 2"',
            'name'  =>  'page_content[]',  
            'value' =>  'left_expose',
            'form'  =>  'page-creator',
            'data'  =>  true,
            'checked'   =>  in_array('left_expose', $page_content)
        ]) ?>
    </div>
    <div class="decider">
        <?= Form::check([
            'label' =>  'Espace "Contact"',
            'name'  =>  'page_content[]',  
            'value' =>  'contact',
            'form'  =>  'page-creator',
            'data'  =>  true,
            'checked'   =>  in_array('contact', $page_content)
        ]) ?>
    </div>
    <div class="decider">
        <?= Form::check([
            'label' =>  'Espace "Tabs"',
            'name'  =>  'page_content[]',  
            'value' =>  'tabs',
            'form'  =>  'page-creator',
            'data'  =>  true,
            'checked'   =>  in_array('tabs', $page_content)
        ]) ?>
    </div>
    <div class="decider">
        <?= Form::check([
            'label' =>  'Espace "Actualités"',
            'name'  =>  'page_content[]',  
            'value' =>  'posts',
            'form'  =>  'page-creator',
            'data'  =>  true,
            'checked'   =>  in_array('posts', $page_content)
        ]) ?>       
    </div>
    <div class="decider">
        <?= Form::check([
            'label' =>  'Espace "Atualité détaillé"',
            'name'  =>  'page_content[]',
            'value' =>  'single-post',
            'form'  =>  'page-creator',
            'data'  =>  true,
            'checked'   =>  in_array('single-post', $page_content)
        ]) ?>
    </div>
    <div class="decider">
        <?= Form::check([
            'label' =>  'Espace "Text"',
            'name'  =>  'page_content[]',
            'value' =>  'text',
            'form'  =>  'page-creator',
            'data'  =>  true,
            'checked'   =>  in_array('text', $page_content)
        ]) ?>
    </div>
    <div class="decider">
        <?= Form::check([
            'label' =>  'Espace "Reason"',
            'name'  =>  'page_content[]',
            'value' =>  'reason',
            'form'  =>  'page-creator',
            'data'  =>  true,
            'checked'   =>  in_array('reason', $page_content)
        ]) ?>
    </div>
    <div class="decider">
        <?= Form::check([
            'label' =>  'Espace "Work"',
            'name'  =>  'page_content[]',
            'value' =>  'work',
            'form'  =>  'page-creator',
            'data'  =>  true,
            'checked'   =>  in_array('work', $page_content)
        ]) ?>
    </div>
    <div class="decider">
        <?= Form::check([
            'label' =>  'Espace "multiple-feature"',
            'name'  =>  'page_content[]',
            'value' =>  'multiple-feature',
            'form'  =>  'page-creator',
            'data'  =>  true,
            'checked'   =>  in_array('multiple-feature', $page_content)
        ]) ?>
    </div>
    <div class="decider">
        <?= Form::check([
            'label' =>  'Espace "Presentation"',
            'name'  =>  'page_content[]',
            'value' =>  'presentation',
            'form'  =>  'page-creator',
            'data'  =>  true,
            'checked'   =>  in_array('presentation', $page_content)
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

<!-- ##### Contact Area Start ##### -->
<?= $content->getContactArea("", $page) ?>
<!-- ##### Contact Area End ##### -->

<!-- ##### Tabs ##### -->
<?= $content->getTabs($page) ?>
<!-- ##### Tabs end #####-->

<!-- ##### News Area Start ##### -->
<?= $content->getNewsSection('#', '#', $page) ?>
<!-- ##### News Area End ##### -->

<!-- ##### Post Details Area Start ##### -->
<?= $content->getSinglePost('#', $page) ?>
<!-- ##### Post Details Area End ##### -->

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

<form action="<?= $router->url('admin.page.modify', ['slug' => $params['slug']]) ?>" id="page-creator" method="POST" class="m-5">
    <button name="submit" class="mt-3 btn btn-success">Modifier</button>
</form>
