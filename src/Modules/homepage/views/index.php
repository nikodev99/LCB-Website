<?php

use Web\App\Components\Component;
use Web\App\Components\PageRender;
use Web\App\Core\AppFunction;

$page = new PageRender();
$case_of_modification = AppFunction::user_connected();
$meta_description = "Ceci est la page d'acceuil";
$page_title = "LCB Website, Homepage - Page d'acceuil";
$keywords = "LCB Bank";
$fr_lang_url = $router->url("home@index");
$en_lang_url = $router->url("homepage@index");


?>
<!-- Preloader -->
<div class="preloader d-flex align-items-center justify-content-center">
    <div class="lds-ellipsis">
        <div></div>
        <div></div>
        <div></div>
        <div></div>
    </div>
</div>

<!-- ##### Hero Area Start ##### -->
<section class="hero-area">
    <?= $case_of_modification ? Component::modify_anchor($router->url('homepage.modify.carousel')) : null ?>
    <div class="hero-slideshow owl-carousel">
        <?= $page->getMainCarousel() ?>
    </div>
</section>
<!-- ##### Hero Area End ##### -->


<!-- ##### Features Area Start ###### -->
<section class="features-area section-padding-100-0">
<?= $case_of_modification ? Component::modify_anchor($router->url('homepage.modify.feature')) : null ?>
    <div class="container">
        <div class="row">
            <?= $page->getFeaturesArea() ?>
        </div>
    </div>
</section>
<!-- ##### Features Area End ###### -->

<!-- ##### Call To Action Start ###### -->
<section class="cta-area d-flex flex-wrap">
    <?= $case_of_modification ? Component::modify_anchor($router->url('homepage.modify.cta')) : null ?>
    <?= $page->getCTAArea() ?>
</section>
<!-- ##### Call To Action End ###### -->

<!-- ##### Call To Action Start ###### -->
<section class="cta-2-area wow fadeInUp" data-wow-delay="100ms">
    <?= $case_of_modification ? Component::modify_button() : null ?>    
    <div class="container">
        <div class="row">
           <?= $page->gettingInTouch() ?>
        </div>
    </div>
</section>
<!-- ##### Call To Action End ###### -->

<!-- ##### Services Area Start ###### -->
<section class="services-area section-padding-100-0">
    <?= $case_of_modification ? Component::modify_anchor($router->url('homepage.service')) : null ?>
    <div class="container">
        <?= $page->getServicesArea() ?>
    </div>
</section>
<!-- ##### Services Area End ###### -->

<!-- ##### Miscellaneous Area Start ###### -->
<section class="miscellaneous-area section-padding-100-0">
    <div class="container">
    <?= $page->getNewsArea() ?>
    </div>
</section>
<!-- ##### Miscellaneous Area End ###### -->

<!-- ##### Newsletter Area Start ###### -->
<?= $page->getNewsletterArea(AppFunction::multipleURL([
    $router->url('homepage.add.fr.newsletter'),
    $router->url('homepage.add.en.newsletter')
], AppFunction::langVerify()), $router->url('homepage.modify.newsletter')) ?>
<!-- ##### Newsletter Area End ###### -->