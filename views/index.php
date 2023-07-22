<?php

use Web\App\Core\Request;
use Web\App\Core\AppFunction;
use Web\App\Components\PageRender;

$header = new PageRender();
$requested_uri = Request::getRequest();
$request = explode("/", $requested_uri);
$fr = in_array('en', $request) ? "French" : "Français";
$en = in_array('en', $request) ? "English" : "Anglais";
$lang_title_fr = in_array('en', $request) ? "View in french" : "Site en Français";
$lang_title_en = in_array('en', $request) ? "View in English" : "Site en anglais";
$url_fr = $fr_lang_url ?? '#1';
$url_en = $en_lang_url ?? '#2';
$site_lang = AppFunction::requestLang($requested_uri);
$home_url = AppFunction::multipleURL([$router->url("home@index"), $router->url("homepage@index")], $site_lang);

?>
<!DOCTYPE html>
<html lang="<?= $site_lang ? 'en' : 'fr' ?>" data-lt-installed="false">

<head>
    <meta charset="UTF-8">
    <!-- Title -->
    <title><?= $page_title ?? "LCB Bank, BMCE Group" ?></title>
    <!-- <meta http-equiv="content-type" content="text/html; charset=UTF-8"> -->
    <meta name="description" content="<?= $meta_description ?? "Welcome on the LCB Website" ?>">
    <meta name="keywords" content="<?= $keywords ?? 'LCB Bank' ?>">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <!-- The above 4 meta tags *must* come first in the head; any other head content must come *after* these tags -->

    <!-- Stylesheet -->
    <link rel="shortcut icon" href="favicon.ico" type="image/x-icon">
    <link rel="stylesheet" href="../../webroot/css/bootstrap.min.css">
    <link rel="stylesheet" href="../../webroot/css/classy-nav.css">
    <link rel="stylesheet" href="../../webroot/css/owl.carousel.min.css">
    <link rel="stylesheet" href="../../webroot/css/animate.css">
    <link rel="stylesheet" href="../../webroot/css/font-awesome.min.css">
    <link rel="stylesheet" href="../../webroot/css/credit-icon.css">
    <link rel="stylesheet" href="../../webroot/css/main.css">

    <!-- fonts -->
    <!-- <link href="https://fonts.googleapis.com/css2?family=Playfair+Display:wght@400;700&family=Source+Sans+Pro:wght@400;700&display=swap" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Raleway:ital,wght@0,500;0,600;1,400&display=swap" rel="stylesheet">  -->
</head>

<body>
<!-- ##### Header Area Start ##### -->
<header class="header-area">
    <!-- Top Header Area -->
    <div class="top-header-area">
        <div class="container h-100">
            <div class="row h-100 align-items-center">
                <div class="col-12 d-flex justify-content-between">
                    <!-- Logo Area -->
                    <div class="logo">
                        <a href="<?= AppFunction::langVerify() ? $router->url('homepage@index') : $router->url('home.index') ?>" class="active">LCB Bank</a>
                        <a href="https://www.lcb-cash.com/">LCB Cash</a>
                        <a href="#">LCB NET</a>
                        <a href="#">LCB Academy</a>
                        <a href="#">LCB <?= AppFunction::langVerify() ? 'Foundation' : 'Fondation' ?></a>
                        <a href="#">LCB Capital</a>
                    </div>

                    <!-- Top Contact Info -->
                    <div class="top-contact-info d-flex align-items-center">
                        <?= $header->getLang([
                            $url_fr =>  [
                                "title" =>  $lang_title_fr,
                                "image" =>  "../../webroot/img/core-img/fr.svg",
                                "name"  =>  $fr
                            ],
                            $url_en =>  [
                                "title" =>  $lang_title_en,
                                "image" =>  "../../webroot/img/core-img/en.svg",
                                "name"  =>  $en
                            ]
                        ]) ?>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Navbar Area -->
    <div class="credit-main-menu" id="sticker">
        <div class="classy-nav-container breakpoint-off">
            <div class="container">
                <!-- Menu -->
                <nav class="classy-navbar justify-content-between" id="creditNav">

                    <!-- Navbar Toggler -->
                    <div class="classy-navbar-toggler">
                        <span class="navbarToggler"><span></span><span></span><span></span></span>
                    </div>

                    <!-- Menu -->
                    <div class="classy-menu">

                        <!-- Close Button -->
                        <div class="classycloseIcon">
                            <div class="cross-wrap"><span class="top"></span><span class="bottom"></span></div>
                        </div>

                        <!-- Nav Start -->
                        <div class="classynav">
                            <?= $header->setMenu() ?>
                        </div>
                        <!-- Nav End -->
                    </div>

                    <!-- Contact -->
                    <div class="contact">
                        <a href="<?= $home_url ?>"><img src="../../webroot/img/icons/logo.png" alt=""></a>
                    </div>
                </nav>
            </div>
        </div>
    </div>
</header>
<!-- ##### Header Area End ##### -->

<div>
<?= $content ?? null ?>
</div>

<!-- ##### Footer Area Start ##### -->
<footer class="footer-area section-padding-100-0">
    <div class="container">
        <div class="row">
            <?= $header->getFooter() ?>
        </div>
    </div>

    <!-- ##### Community area start -->
    <div class="community-area section-padding-0-25" id="contact">
        <div class="container">
            <div class="row">
                <div class="col-12">
                    <div class="section-heading text-center">
                        <h2 style="color: #fff;">Suivez nous</h2>
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="col-6 col-lg">
                    <div class="single-community big-social">
                        <a href="#" class="facebook"><i class="fa fa-facebook"></i></a>
                    </div>
                </div>
                <div class="col-6 col-lg">
                    <div class="single-community mid-social">
                        <a href="#" class="linkedin"><i class="fa fa-linkedin"></i></a>
                    </div>
                    <!-- <div class="single-community">
                        <a href="#" class="youtube"><i class="fa fa-youtube-play"></i></a>
                    </div> -->
                </div>
                <div class="col-6 col-lg">
                    <div class="single-community">
                        <a href="#" class="twitter"><i class="fa fa-twitter"></i></a>
                    </div>
                    <div class="single-community mid-social">
                        <a href="#" class="whatsapp"><i class="fa fa-whatsapp"></i></a>
                    </div>
                </div>
                <div class="col-6 col-lg">
                    <div class="single-community big-social">
                        <a href="#" class="instagram"><i class="fa fa-instagram"></i></a>
                    </div>
                </div>
                <div class="col-6 col-lg">
                    <div class="single-community mid-social">
                        <a href="#" class="snapchat"><i class="fa fa-snapchat"></i></a>
                    </div>
                    <!-- <div class="single-community">
                        <a href="#" class="google-plus"><i class="fa fa-google-plus"></i></a>
                    </div> -->
                </div>
            </div>
        </div>
    </div>
<!-- ##### Community area end -->

    <!-- Copywrite Area -->
    <div class="copywrite-area">
        <div class="container">
            <div class="row">
                <div class="col-12">
                    <div class="copywrite-content d-flex flex-wrap justify-content-between align-items-center">
                        <!-- Footer Logo -->
                        <a href="<?= AppFunction::multipleURL([$router->url("home@index"), $router->url("homepage@index")], AppFunction::langVerify()) ?>" class="footer-logo">
                            <img src="../../webroot/img/icons/logo.png" alt="" width="">
                        </a>

                        <!-- Copywrite Text -->
                        <p class="copywrite-text"><a href="#">Copyright &copy;<script>document.write(new Date().getFullYear());</script> All rights reserved</p>

                        <!-- Recommendation -->
                        <p class="copywrite-text"><a href="#">Recomendations</p>
                        <p class="copywrite-text"><a href="#">Reclamation</p>
                        <p class="copywrite-text"><a href="#">GNU</p>

                        <!--  -->
                    </div>
                </div>
            </div>
        </div>
    </div>
</footer>

<!-- ##### Footer Area Start ##### -->
<spotlight-bar target="* a" id="custom"></spotlight-bar>
<!-- ##### All Javascript Script ##### -->
<!-- jQuery-2.2.4 js -->
<script src="../../webroot/js/jquery-2.2.4.min.js"></script>
<!-- Popper js -->
<script src="../../webroot/js/bootstrap/popper.min.js"></script>
<!-- Bootstrap js -->
<script src="../../webroot/js/bootstrap/bootstrap.min.js"></script>
<!-- All Plugins js -->
<script src="../../webroot/js/plugins/plugins.js"></script>
<script src="../../webroot/js/plugins/carousel.js"></script>
<!-- google maps -->
<script src="https://maps.googleapis.com/maps/api/js?key=AIzaSyAUE9CDgr2mzf0QIL8rfg8kmrkFVqyR3Vk&callback=initMap" async></script>
<!-- Active js -->
<script src="../../webroot/js/spotlight.js"></script>
<script src="../../webroot/js/base.js"></script>
<script src="../../webroot/js/main.js"></script>
</body>

</html>
