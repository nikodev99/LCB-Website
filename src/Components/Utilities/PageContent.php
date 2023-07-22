<?php

namespace Web\App\Components\Utilities;

use Web\App\Components\Component;
use Web\App\Components\Form;
use Web\App\Core\AppFunction;
use Web\App\Core\Flash;
use Web\App\Core\models\PaginationQuery;
use Web\App\Core\Request;
use Web\App\Core\session\Session;

class PageContent
{
    const DISPLAY = 'style="display:none"';
    const MODIFY_LABEL = 'Editer ';

    public static function getBreadcrumb(array $data, bool $modify, array $display, ?string $data_pointer, array $third_url = []): string
    {
        $name = 'banniere';
        $url_2 = null; $header_2 = null; $url_key = null;
        $header = array_key_exists('header', $data) ? $data['header'] : null;
        $background = array_key_exists('background', $data) ? $data['background'] : AppFunction::getImgRoute('13.jpg');
        $home = AppFunction::langVerify() ? 'Home' : 'Acceuil';
        $url = AppFunction::langVerify() ? '/en/home' : '/fr/home';

        if (!empty($third_url))
            $url_key = (new Session())->get('url_key');
            $p = !is_null($url_key) ? '?p='.$url_key : null;
            foreach($third_url as $key => $value) {
                $url_2 = $key !== '#' ? AppFunction::pageURL($key.$p) : Request::getRequest();
                $header_2 = $value;
            }
            
        $li = !empty($third_url) ? '<li class="breadcrumb-item"><a href="'.$url_2.'">'.$header_2.'</a></li>' : null;

        $displayed = !in_array($name, $display) ? 'display:none' : null;
        $modify_button = $modify ? Component::modify_button($data_pointer, self::getSectionName($name)) : null;

        return <<<HTML
        <section class="breadcrumb-area bg-img bg-overlay jarallax" style="background-image: url({$background});{$displayed}" data-checked="{$name}">
            {$modify_button}
            <div class="container h-100">
                <div class="row h-100 align-items-center">
                    <div class="col-12">
                        <div class="breadcrumb-content">
                            <h2>{$header}</h2>
                            <nav aria-label="breadcrumb">
                                <ol class="breadcrumb">
                                    <li class="breadcrumb-item"><a href="{$url}">{$home}</a></li>
                                    {$li}
                                    <li class="breadcrumb-item active" aria-current="page">{$header}</li>
                                </ol>
                            </nav>
                        </div>
                    </div>
                </div>
            </div>
        </section>
HTML;
    }

    public static function aboutArea(array $about_content, bool $modify, array $display, ?string $data_pointer): string
    {
        $name = 'about';
        $modify_button = null;
        $header = array_key_exists('header', $about_content) ? $about_content['header'] : 'LCB Bank';
        $introduction = array_key_exists('introduction', $about_content) ? $about_content['introduction'] : null;
        $excerpt = array_key_exists('excerpt', $about_content) ? $about_content['excerpt'] : null;
        $button = AppFunction::langVerify() ? 'Read More' : 'Lire plus';
        $lien = array_key_exists('url', $about_content) && !empty($about_content['url']) ? $about_content['url'] : '#';
        $image = array_key_exists('image', $about_content) ? $about_content['image'] : AppFunction::getImgRoute('14.jpg');
        $displayed = !in_array($name, $display) ? self::DISPLAY : null;
        if ($modify) $modify_button = Component::modify_anchor($data_pointer, self::getSectionName($name));
        return <<<HTML
        <section class="about-area section-padding-100-0" {$displayed} data-checked="{$name}">
            {$modify_button}
            <div class="container">
                <div class="row align-items-center">
                    <div class="col-12 col-md-6">
                        <div class="about-content mb-100">
                            <div class="section-heading">
                                <div class="line"></div>
                                <h2>{$header}</h2>
                            </div>
                            <h6 class="mb-4">{$introduction}</h6>
                            <p class="mb-0">{$excerpt}</p>
                            <a href="{$lien}" class="btn credit-btn mt-30">{$button}</a>
                        </div>
                    </div>
                    <div class="col-12 col-md-6">
                        <div class="about-thumbnail mb-100">
                            <img src="{$image}" alt="">
                        </div>
                    </div>
                </div>
            </div>
        </section>
HTML;
    }

    public static function getReasons(array $reasons, bool $modify, array $display, ?string $data_pointer): string
    {
        $name = "reason";
        $displayed = !in_array($name, $display) ? self::DISPLAY : null;
        $modify_button = $modify ? Component::modify_anchor($data_pointer, self::getSectionName($name)) : null;

        $image = array_key_exists('image', $reasons) ? $reasons['image'] : AppFunction::getImgRoute('14.jpg');
        $title = array_key_exists('title', $reasons) ? $reasons['title'] : null;
        $description = array_key_exists('description', $reasons) ? $reasons['description'] : null;
        $items = array_key_exists('items', $reasons) && is_array($reasons['items']) ? self::reasons($reasons['items']) : null; 

        return <<<HTML
        <section class="about-area reason-area section-padding-100" {$displayed} data-checked="{$name}">
            {$modify_button}
            <div class="container">
                <div class="row align-items-center">
                    <div class="col-lg-6 col-md-6">
                        <div class="about-thumbnail wow fadeInLeft" data-wow-duration="1s" data-wow-delay="100ms">
                            <img src="{$image}" alt="">
                        </div>
                    </div>
                    <div class="col-lg-6 col-md-6">
                        <div class="about-content mb50 wow fadeInUp" data-wow-duration="1.2s" data-wow-delay="200ms">
                            <div class="section-heading" data-wow-duration="1.5s" data-wow-delay="300ms">
                                <div class="line wow lightSpeedIn"></div>
                                <h2>{$title}</h2>
                            </div>
                            <h6 class="mb-4 wow fadeInUp" data-wow-duration="1s" data-wow-delay="500ms">{$description}</h6>
                            <div class="mb-0 about-list">
                                {$items}
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </section>
HTML;
    }

    public static function multipleFeature(array $features, bool $modify, array $display, ?string $data_pointer): string
    {
        $name = "multiple-feature";
        $multiple_features = [];
        $delay = 200;
        $displayed = !in_array($name, $display) ? self::DISPLAY : null;
        $modify_button = $modify ? Component::modify_anchor($data_pointer, self::getSectionName($name)) : null;
        $title = array_key_exists('title', $features) ? $features['title'] : null;
        $description = array_key_exists('description', $features) ? $features['description'] : null;
        if (array_key_exists('features', $features)) {
            foreach($features['features'] as $feature) {
                $delay += 100;
                $multiple_features[] = self::single_feature($feature, $delay);
            }
        }

        $featureContent = implode(PHP_EOL, $multiple_features);
        return <<<HTML
        <section class="about-area feature-1-area section-padding-100" {$displayed} data-checked="{$name}">
            {$modify_button}
            <div class="container">
                <div class="row">
                    <div class="col-12">
                        <div class="section-heading feature-1-header text-center wow fadeInUp" data-wow-delay="100ms">
                            <div class="line"></div>
                            <h2>{$title}</h2>
                            <p>{$description}</p>
                        </div>
                    </div>
                </div>
                <div class="row">
                    {$featureContent}
                </div>
            </div>
        </section>
HTML;
    }

    public static function works_area(array $works, array $titles, bool $modify, array $display, ?string $data_pointer): string
    {
        $name = "work";
        $displayed = !in_array($name, $display) ? self::DISPLAY : null;
        $modify_button = $modify ? Component::modify_anchor($data_pointer, self::getSectionName($name)) : null;
        $description = $titles['description'] !== null ? '<p class="desc wow fadeInUp" data-wow-duration="1s" data-wow-delay="700ms">'.$titles['description'].'</p>' : null;
        $work_content = !empty($works) ? '<div class="row">'.self::works($works).'</div>' : null;
        return <<<HTML
        <section class="about-area work-area" {$displayed} data-checked="{$name}">
            {$modify_button}
            <div class="container">
                <div class="row">
                    <div class="col-12">
                        <div class="section-heading text-center wow fadeInUp" data-wow-delay="100ms">
                            <div class="wow lightSpeedIn line" data-wow-duration="1s" data-wow-delay="300ms"></div>
                            <h2 class="wow fadeInUp" data-wow-duration="1s" data-wow-delay="500ms">{$titles['title']}</h2>
                            {$description}
                        </div>
                    </div>
                </div>
                {$work_content}
            </div>
        </section>
HTML;
    }

    public static function callToAction(array $callToActionContent, bool $modify, array $display, ?string $data_pointer): string
    {
        $name = 'expose';
        $modify_button = null;
        $singleFacts = [];
        $title = array_key_exists('title', $callToActionContent) ? $callToActionContent['title'] : 'LCB Bank';
        //dd($title);
        $description = array_key_exists('description', $callToActionContent) ? $callToActionContent['description'] : null;
        $image = array_key_exists('image', $callToActionContent) ? $callToActionContent['image'] : AppFunction::getImgRoute('19.jpg');
        if (array_key_exists('single-facts', $callToActionContent)) {
            if (is_array($callToActionContent['single-facts'])) {
                foreach ($callToActionContent['single-facts'] as $singleFactsContent) {
                    if (is_array($singleFactsContent))
                        $singleFacts[] = self::singleFact($singleFactsContent);
                }
            }
        }
        $singleFact = !empty($singleFacts) ? implode(PHP_EOL, $singleFacts) : null;
        $displayed = !in_array($name, $display) ? self::DISPLAY : null;
        if ($modify) $modify_button = Component::modify_anchor($data_pointer, self::getSectionName($name));
        return <<<HTML
        <section class="cta-area" {$displayed} data-checked="{$name}">
            {$modify_button}
            <div class="cta-content" displayed>
                <div class="section-heading white">
                    <div class="line"></div>
                    <h2>{$title}</h2>
                </div>
                <h6 class="mb-0">{$description}</h6>

                <div class="d-flex flex-wrap align-items-center justify-content-between">
                    {$singleFact}
                </div>
            </div>
            <div class="cta-thumbnail bg-img jarallax" style="background-image: url({$image});"></div>
        </section>
HTML;
    }

    public static function accordion_area(array $accordionContent, bool $modify, array $display, ?string $data_pointer): string
    {
        $name = "accordion_area";
        $image = AppFunction::getImgRoute('bg-img5f7323ea15f7c9qht160138135481168874.jpg');
        $displayed = !in_array($name, $display) === true ? self::DISPLAY : null;
        $modify_button = $modify ? Component::modify_anchor($data_pointer, self::getSectionName($name)): null;
        return <<<HTML
        <section class="credit-faq-area accordion-area section-padding-0-100" {$displayed} data-checked="{$name}">
            {$modify_button}
            <div class="row align-center">
                <div class="col-lg-6">
                    <div class="accordion-area-img">
                        <img class="img-fluid" src="{$image}" alt="">
                    </div>
                </div>
                <div class="col-lg-6">
                    <div class="accordion-area-content faq-ask">
                        <h3 class="wow fadeInRight" data-wow-duration="1s" data-waw-delay="100ms">Comptes particulier</h3>
                        <div id="accordion">
                            <div class="card wow fadeInUp" data-wow-duration="1.2s" data-wow-delay="200ms">
                                <div class="card-header" id="headingOne">
                                    <h5 class="mb-0">
                                        <button class="btn btn-link" data-toggle="collapse" data-target="#collapseOne" aria-expanded="true" aria-controls="collapseOne">
                                            Compte chèque ou courant
                                        </button>
                                    </h5>
                                </div>
                                <div id="collapseOne" class="collapse show" aria-labelledby="headingOne" data-parent="#accordion">
                                    <div class="card-body">
                                    Le compte chèque est un compte à vue ouvert à une personne physique qui reçoit des versements et des virements. Ce compte est débité des chèques et ordres émis par son titulaire.
                                    </div>
                                </div>
                            </div>
                            <div class="card wow fadeInUp" data-wow-duration="1.2s" data-wow-delay="400ms">
                                <div class="card-header" id="headingTwo">
                                    <h5 class="mb-0">
                                        <button class="btn btn-link collapsed" data-toggle="collapse" data-target="#collapseTwo" aria-expanded="false" aria-controls="collapseTwo">
                                            Compte épargne
                                        </button>
                                    </h5>
                                </div>
                                <div id="collapseTwo" class="collapse" aria-labelledby="headingTwo" data-parent="#accordion">
                                    <div class="card-body">
                                    Le Compte épargne est un compte de dépôt à vue rémunéré qui permet de constituer progressivement un capital.<br>Les opérations de versement et retrait peuvent être effectuées par le client titulaire du Compte épargne ou par tout membre de sa famille auquel il a donné procuration, auprès de toutes les agences LCB.
                                    </div>
                                </div>
                            </div>
                            <div class="card wow fadeInUp" data-wow-duration="1.2s" data-wow-delay="600ms">
                                <div class="card-header" id="headingThree">
                                    <h5 class="mb-0">
                                        <button class="btn btn-link collapsed" data-toggle="collapse" data-target="#collapseThree" aria-expanded="false" aria-controls="collapseThree">
                                            Compte LCB Pay
                                        </button>
                                    </h5>
                                </div>
                                <div id="collapseThree" class="collapse" aria-labelledby="headingThree" data-parent="#accordion">
                                    <div class="card-body">
                                    Le compte LCB PAY, est un compte virtuel qui permet de gérer son argent depuis son smartphone. Que vous soyez client de LCB Bank ou non vous pouvez avoir accès à ce service.
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>     
        </section>
HTML;
    }

    public static function accordion(array $accordionContent, bool $modify, array $display, ?string $data_pointer): string
    {
        $name = 'accordion';
        $modify_button = null;
        $contents = [];
        if (array_key_exists('contents', $accordionContent)) {
            if (is_array($accordionContent['contents'])) {
                foreach ($accordionContent['contents'] as $singleAccordionContent) {
                    $contents[] = self::singleAccordion($singleAccordionContent);
                }
            }
        }
        $content = implode(PHP_EOL, $contents);
        $displayed = !in_array($name, $display) === true ? self::DISPLAY : null;
        if ($modify) $modify_button = Component::modify_anchor($data_pointer, self::getSectionName($name));
        $message = AppFunction::langVerify() ? 'No offer is available at the moment' : 'Aucune offre n\'est disponible pour le moment';
        return empty($accordionContent) ? ' <section class="credit-faq-area section-padding-100-0" ' . $displayed . '>' . $modify_button . '<div class="div"><br>' . $message . '</div> </section>' :
            <<<HTML
        <section class="credit-faq-area section-padding-100-0" {$displayed} data-checked="{$name}">
            {$modify_button}
            <div class="container">
                <div class="row">
                    <div class="col-12 col-lg-12">
                        <div class="accordions mb-100" id="accordion" role="tablist" aria-multiselectable="true">
                            {$content}
                        </div>
                    </div>
                </div>
            </div>
        </section>
HTML;
    }

    public static function feature_left(array $featureContent, bool $modify, array $display, ?string $data_pointer): string
    {
        $name = '';
        $modify_button = null;
        $image = array_key_exists('image', $featureContent) ? $featureContent['image'] : AppFunction::getImgRoute('20.jpg');
        $displayed = !in_array($name, $display) ? self::DISPLAY : null;
        $body = self::feature_content($featureContent, $modify, true);
        $thumbnail = self::thumbnail($image);
        if ($modify) $modify_button = Component::modify_button($data_pointer, self::getSectionName($name));
        return <<<HTML
        <section class="special-feature-area" {$displayed} data-checked="{$name}">
            {$modify_button}
            {$body}
            {$thumbnail}
        </section>
HTML;
    }

    public static function feature_right(array $featureContent, bool $modify, array $display, ?string $data_pointer): string
    {
        $name = 'left_expose';
        $modify_button = null;
        $image = array_key_exists('image', $featureContent) ? $featureContent['image'] : AppFunction::getImgRoute('21.jpg');
        $displayed = !in_array($name, $display) ? self::DISPLAY : null;
        $body = self::feature_content($featureContent, $modify);
        $thumbnail = self::thumbnail($image);
        if ($modify) $modify_button = Component::modify_anchor($data_pointer, self::getSectionName($name));
        return <<<HTML
        <section class="special-feature-area style-2" {$displayed} data-checked="{$name}">
            {$modify_button}
            {$thumbnail}
            {$body}
        </section>
HTML;
    }

    public static function contact_area(array $contactInfo, string $url, bool $modify, array $display, ?string $data_pointer): string
    {
        $name = 'contact';
        $modify_button = null;
        $description = array_key_exists('description', $contactInfo) ? $contactInfo['description'] : null;
        $contact_form = self::contact_form($url, new Flash());
        $header = AppFunction::langVerify() ? 'Contact us' : 'Nous contacter';
        $contact = AppFunction::langVerify() ? 'Get in touch' : 'Rentrer en contact';
        $address = array_key_exists('address', $contactInfo) ? $contactInfo['address'] : null;
        $tel_number = array_key_exists('tel', $contactInfo) ? $contactInfo['tel'] : null;
        $availability = '#80c340';
        $hour_of_availability = AppFunction::stamp(time(), 'G');
        $day = AppFunction::stamp(time(), 'N');
        if ((int)$hour_of_availability > 16 | (int)$hour_of_availability <= 8 | (int) $day === 6 | (int)$day === 7) {
            $availability = 'red';
            $hour_of_availability = AppFunction::stamp(time(), 'H:i:s') . ' (Fermé)';
        } else {
            $hour_of_availability = AppFunction::stamp(time(), 'H:i:s') . ' (Ouvert)';
        }
        $email_address = array_key_exists('email', $contactInfo) ? $contactInfo['email'] : null;
        $reply = AppFunction::langVerify() ? 'We reply in 24 HRS' : 'Nous repondons dans 24H';
        $displayed = !in_array($name, $display) ? self::DISPLAY : null;
        if ($modify) $modify_button = Component::modify_button($data_pointer, self::getSectionName($name));
        return <<<HTML
        <section class="contact-area section-padding-100-0" {$displayed} data-checked="{$name}">
        {$modify_button}
        <div class="container">
            <div class="row">
                <div class="col-12 col-lg-6">
                    <div class="single-contact-area mb-100">
                        <div class="elements-title mb-30">
                            <div class="line"></div>
                            <h2>{$header}</h2>
                        </div>
                        <p>{$description}</p>
                    </div>
                </div>
                <div class="col-12 col-lg-6">
                    <div class="single-contact-area mb-100">
                        <div class="contact--area contact-page">
                            <div class="contact-content">
                                <h5>{$contact}</h5>
                                <div class="single-contact-content d-flex align-items-center">
                                    <div class="icon">
                                        <img src="../../webroot/img/core-img/location.png" alt="">
                                    </div>
                                    <div class="text">
                                        <p>{$address}</p>
                                    </div>
                                </div>
                                <div class="single-contact-content d-flex align-items-center">
                                    <div class="icon">
                                        <img src="../../webroot/img/core-img/call.png" alt="">
                                    </div>
                                    <div class="text">
                                        <p>{$tel_number}</p>
                                        <span style="color:{$availability}">{$hour_of_availability}</span>
                                    </div>
                                </div>
                                <div class="single-contact-content d-flex align-items-center">
                                    <div class="icon">
                                        <img src="../../webroot/img/core-img/message2.png" alt="">
                                    </div>
                                    <div class="text">
                                        <p>{$email_address}</p>
                                        <span>{$reply}</span>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        {$contact_form}
        </section>
HTML;
    }

    public static function tabs(array $tabContents, bool $modify, array $display, ?string $data_pointer): string
    {
        $name = 'tabs';
        $modify_button = null;
        $displayed = !in_array($name, $display) ? self::DISPLAY : null;
        $tabUrl = [];
        $tabContent = [];
        foreach ($tabContents as $tabs) {
            foreach ($tabs as $key => $value) {
                $tabUrl[] = $key;
                $tabContent[] = $value;
            }
        }
        $tabCorrespondance = self::tabCorrespondance($tabUrl, $tabContent);
        if ($modify) $modify_button = Component::modify_anchor($data_pointer, self::getSectionName($name));
        return <<<HTML
        <section class="credit-faq-area section-padding-100-0" {$displayed} data-checked="{$name}">
            {$modify_button}
            <div class="container">
                <div class="row">
                    <div class="col-12 col-lg-12">
                        {$tabCorrespondance}
                    </div>
                </div>
            </div>
        </section>
HTML;
    }

    public static function getNewsArea(string $url, array $news, array $categories, string $search_url, bool $modify, array $display, ?string $data_pointer, PaginationQuery $pages): string
    {
        $name = 'posts';
        $modify_button =  null;
        $all_news = [];
        $search = self::search_area($search_url);
        $categorie = self::category_area($categories);
        foreach ($news as $singleNews) {
            $visibility = array_key_exists('online',  $singleNews) && $singleNews['online'] === true;
            $all_news[] = self::singleNews($singleNews, $visibility);
        }
        $allNewsList = implode(PHP_EOL, $all_news);
        $displayed = !in_array($name, $display) ? self::DISPLAY : null;
        if ($modify) $modify_button = Component::modify_anchor($data_pointer, self::getSectionName($name));
        $pagination = self::pagination($pages, $url);
        return <<<HTML
        <section class="news-area section-padding-100" {$displayed} data-checked="{$name}">
            {$modify_button}
            <div class="container">
                <div class="row">
                    <div class="col-12 col-lg-8">
                        {$allNewsList}
                        {$pagination}
                    </div>

                    <div class="col-12 col-sm-9 col-md-6 col-lg-4">
                        <div class="sidebar-area">
                            {$search}
                            {$categorie}
                        </div>
                    </div>
                </div>
            </div>
        </section>
HTML;
    }

    public static function singlePost(array $postContent, array $categories, string $search_url, bool $modify, array $display, ?string $data_pointer): string
    {
        $name = 'single-post';
        $modify_button = null;
        $search = self::search_area($search_url);
        $category = self::category_area($categories);
        $title = array_key_exists('title', $postContent) ? $postContent['title'] : null;
        $image = array_key_exists('image', $postContent) ? '<img src="' . $postContent['image'] . '" alt="">' : null;
        $excerpt = array_key_exists('excerpt', $postContent) ? $postContent['excerpt'] : null;
        $header = AppFunction::langVerify() ? 'Latest News' : 'Nouvelles actualité';
        $content = array_key_exists('content', $postContent) ? $postContent['content'] : null;
        $displayed = !in_array($name, $display) ? self::DISPLAY : null;
        if ($modify) $modify_button = Component::modify_anchor($data_pointer, self::getSectionName($name));
        return <<<HTML
        <section class="post-news-area section-padding-100-0" {$displayed} data-checked="{$name}">
            {$modify_button}
            <div class="container">
                <div class="row">
                    <div class="col-12 col-lg-8">
                        <div class="post-details-content mb-100">
                            {$image}
                            {$excerpt}
                            {$content}
                        </div>
                    </div>

                    <div class="col-12 col-sm-9 col-md-6 col-lg-4">
                        <div class="sidebar-area mb-100">
                            {$search}
                            {$category}
                        </div>
                    </div>
                </div>
            </div>
        </section>
HTML;
    }

    public static function text_area(array $contents, bool $modify, array $display, ?string $data_pointer): string
    {
        $name = 'text';
        $modify_button = null;
        $title = array_key_exists('title', $contents) ? $contents['title'] : null;
        $description = array_key_exists('description', $contents) ? $contents['description'] : null;
        $content = array_key_exists('content', $contents) ? $contents['content'] : null;
        $displayed = !in_array($name, $display) ? self::DISPLAY : null;
        if ($modify) $modify_button = Component::modify_anchor($data_pointer, self::getSectionName($name));
        return <<<HTML
        <section class="text-area section-padding-100" {$displayed} data-checked="{$name}">
            {$modify_button}
            <div class="container">
                <div class="row">
                    <div class="col-12 col-lg-12">
                        <div class="single-contact-area">
                            <div class="elements-title mb-20">
                                <div class="line"></div>
                                <h2>{$title}</h2>
                            </div>
                            <p>{$description}</p>
                        </div>
                    </div>
                    <div class="col-12 col-lg-12">
                        {$content}
                    </div>
                </div>
            </div>
        </section>
HTML;
    }

    public static function carreer_area(array $display, string $url, array $errors = []): string
    {
        $name = 'career';
        $displayed = !in_array($name, $display) ? self::DISPLAY : null;
        $title = AppFunction::langVerify() ? 'Spontaneous application' : 'Candidature spontannée';
        $description = AppFunction::langVerify() ? 'You are looking for a job or an internship, we encourage you to apply to LCB Bank by filling out the form below.'
            : 'Vous êtes à la recherche d’un emploi ou d’un stage, nous vous encourageons à postuler à LCB Bank en remplissant le formulaire ci dessous.';
        $last_name = self::input([AppFunction::langVerify() ? 'Your last name*' : 'Votre nom*', 'text', 'last_name', '', AppFunction::langVerify() ? 'Enter your last name' : 'Entrer votre nom'], $errors);
        $first_name = self::input([AppFunction::langVerify() ? 'Your first name*' : 'Votre prénom*', 'text', 'first_name', '', AppFunction::langVerify() ? 'Enter your first name' : 'Entrer votre prénom'], $errors);
        $man = self::input([AppFunction::langVerify() ? 'Male' : 'Vous êtes un homme ?', 'radio', 'sexe', 'Homme', ''], $errors);
        $woman = self::input([AppFunction::langVerify() ? 'Female' : 'Vous êtes une femme ?', 'radio', 'sexe', 'Femme', ''], $errors);
        $object = self::input([AppFunction::langVerify() ? 'Object' : 'Objet', 'text', 'object', 'Candidature spontannée', ''], $errors);
        $cv = self::input([AppFunction::langVerify() ? 'Your resume' : 'Votre CV*', 'file', 'cv', '', ''], $errors);
        $letter = self::input([AppFunction::langVerify() ? 'Cover letter' : 'Lettre de motivation*', 'file', 'letter', '', ''], $errors);
        $message = self::textarea(['', '', 'message', '', AppFunction::langVerify() ? 'Your message' : 'Votre message'], $errors);

        $flash = new Flash();
        $error_message = self::flash_message($flash);
        $flash->unset_flash();
        return <<<HTML
        <section class="text-area section-padding-100" {$displayed} data-checked="{$name}">
            <div class="container">
                <div class="col-12 col-lg-12">
                    <div class="single-contact-area">
                        <div class="elements-title mb-20">
                            <div class="line"></div>
                            <h2>{$title}</h2>
                        </div>
                        <p>{$description}</p>
                    </div>
                </div>
                <div class="col-12 col-lg-12">
                    {$error_message}
                    <form action="{$url}" method="post">
                        {$last_name}
                        {$first_name}
                        {$man}
                        {$woman}
                        {$object}
                        {$cv}
                        {$letter}
                        {$message}
                        <button class="btn credit-btn mt-5">ENVOYER</button>
                    </form>
                </div>
            </div>
        </section>
HTML;
    }

    public static function presentations(array $contents, bool $modify, array $display, ?string $data_pointer): string
    {
        $left_contents = null;
        $right_content = null;
        $presentations = [];
        $options = [];
        $delay = 100;
        $name = 'presentation';
        $modify_button = null;
        foreach($contents as $key => $value) {
            if (is_array($value)) {
                $image = array_key_exists('image', $value) && !is_null($value['image']) ? '<img src="'.AppFunction::getImgRoute($value['image']).'">' : AppFunction::getImgRoute('businessman.svg');
                $content = array_key_exists('content', $value) ? $value['content'] : null;
                $title = array_key_exists('title', $value) ? $value['title'] : null;
                $left_contents = $content;
                $right_content = $image;
                $options['left'] = null;
                $options['right'] = 'right-item--media';
                $options['style'] = 'style="background:#f1f3f9"';
                $options['title_left'] = $title;
                $options['title_right'] = null;
                $divide = $key / 2;
                if (is_float($divide)) {
                    $left_contents = $image;
                    $right_content = $content;
                    $options['left'] = 'left-item--media';
                    $options['right'] = null;
                    $options['style'] = null;
                    $options['title_left'] = null;
                    $options['title_right'] = $title;
                }
                $delay += 100;
                $presentations[] = self::singlePresentation($name, $left_contents, $right_content, $modify, $display, $options, $delay);
            }
        }
        if ($modify) $modify_button = Component::modify_anchor($data_pointer, self::getSectionName($name));
        return '<section>'.$modify_button.implode(PHP_EOL, $presentations).'</section>';
    }

    public static function map_loaded(array $display): string
    {
        $name = "map";
        $displayed = !in_array($name, $display) ? self::DISPLAY : null;

        return <<<HTML
        <section class="text-area value-area section-padding-100-0" {$displayed} data-checked="{$name}">
            <div class="container">
                <div class="row">
                    <div class="col-lg-12" id="map" style="height:400px">
                        <script>
                            let mapId = document.getElementById('map')
                            if (mapId !== null) {
                                let map;

                                function initMap() {
                                    map = new google.maps.Map(document.getElementById("map"), {
                                        center: { lat: -0.9280, lng: 15.8277 },
                                        zoom: 6,
                                    });

                                    addMarker({
                                        agency: "Agence Aeroport",
                                        coords: {lat: -4.2651076, lng: 15.2534491},
                                        content: "<h1>Agence Aeroport</h1>"
                                    })
                                    addMarker({
                                        agence: "Agence Cabral",
                                        coords: {lat: -4.2773702, lng: 15.2798577}
                                    })
                                    addMarker({
                                        agence: "Agence Elite",
                                        coords: {lat: -4.2758409, lng: 15.2833683}
                                    })
                                    addMarker({
                                        agence: "Agence La Gare (ORCA)",
                                        coords: {lat: -4.2706778, lng: 15.2873468}
                                    })
                                    addMarker({
                                        agence: "Agence Makelekele",
                                        coords: {lat: -4.2684889, lng: 15.2139355}
                                    })
                                    addMarker({
                                        agence: "Agence Moukondo",
                                        coords: {lat: -4.2282608, lng: 15.2656879}
                                    })
                                    addMarker({
                                        agence: "Agence Ouenzé",
                                        coords: {lat: -4.2477650, lng: 15.2814977}
                                    })
                                    addMarker({
                                        agence: "Agence Poto-Poto",
                                        coords: {lat: -4.2666890, lng: 15.2841161}
                                    })
                                    addMarker({
                                        agence: "Agence Talagai",
                                        coords: {lat: -4.2240742, lng: 15.2895990}
                                    })
                                    addMarker({
                                        agence: "Agence Central de Pointe-Noire",
                                        coords: {lat: -4.8004730, lng: 11.8425700}
                                    })
                                    addMarker({
                                        agence: "Agence Lumumba",
                                        coords: {lat: -4.7904739, lng: 11.8618005}
                                    })
                                    addMarker({
                                        agence: "Agence Mawata",
                                        coords: {lat: -4.7859530, lng: 11.8745326}
                                    })
                                    addMarker({
                                        agence: "Agence Mpita",
                                        coords: {lat: -4.80223060, lng: 11.8536742}
                                    })
                                    addMarker({
                                        agence: "Agence Port",
                                        coords: {lat: -4.7941634, lng: 11.8380555}
                                    })
                                    addMarker({
                                        agence: "Agence Mvoumvou",
                                        coords: {lat: -4.7771560, lng: 11.8647410}
                                    })
                                    addMarker({
                                        agence: "Agence Dolisie",
                                        coords: {lat: -4.1961460, lng: 12.6743046}
                                    })
                                    addMarker({
                                        agence: "Agence Ouesso",
                                        coords: {lat: 1.6145961, lng: 16.0554953}
                                    })
                                    addMarker({
                                        agence: "Agence Nkayi",
                                        coords: {lat: -4.1891528, lng: 13.2847599}
                                    })
                                    addMarker({
                                        agence: "Agence Oyo",
                                        coords: {lat: -1.1616352, lng: 15.9718132}
                                    })
                                    addMarker({
                                        agence: "Agence Ngombé",
                                        coords: {lat: 0.3553120, lng: 17.1345604}
                                    })

                                    function addMarker(props) {
                                        let icon = {
                                            url: '/favicon.ico',
                                            scaledSize: new google.maps.Size(30, 30),
                                        }

                                        let marker = new google.maps.Marker({
                                            position: props.coords,
                                            map: map,
                                            icon: icon
                                        })

                                        if (props.content) {
                                            let info = new google.maps.InfoWindow({
                                                content: props.content
                                            })

                                            marker.addListener('click', () => {
                                                info.open(map, marker)
                                            })
                                        }
                                    }

                                }
                            }
                        </script>
                    </div>
                </div>
            </div>
        </section>
HTML;
    }

    public static function our_values(array $display): string
    {
        $name = 'values';
        $displayed = !in_array($name, $display) ? self::DISPLAY : null;
        return <<<HTML
        <section class="text-area value-area section-padding-0-0" {$displayed} data-checked="{$name}">
            <div class="container">
                <div class="row">
                    <div class="col-lg-4">
                        <div class="single-value">
                            <div class="title">
                            <div class="title-content">
                                <h2>Bienveillance</h2>
                            </div> 
                            </div>
                            <div class="content">
                                <ul>
                                    <li>J’utilise le SBAM (sourire, Bonjour, Au revoir, Merci, j’ai une posture dynamique)</li>
                                    <li>J’évite de parler fort</li>
                                    <li>J’écoute le client activement (je lui montre mon intérêt sans apriori, sans téléphone, je reformule pour être sûr que j’ai bien compris la demande)</li>
                                    <li>Je fais preuve d’empathie</li>
                                    <li>J’arrête de dire du mal des autres</li>
                                </ul>
                            </div>
                        </div>
                    </div>
                    <div class="col-lg-4">
                        <div class="single-value">
                            <div class="title">
                            <div class="title-content">
                                <h2>Intégrité</h2>
                            </div> 
                            </div>
                            <div class="content">
                                <ul>
                                    <li>Je suis transparent(e) sur les informations délivrées aux clients et je ne divulgue pas les informations internes à extérieur</li>
                                    <li>Je veille à la confidentialité des informations (client, poste, compte, etc…</li>
                                    <li>Je respecte les horaires de travail </li>
                                    <li>Je respecte mes engagements vis-à-vis des clients et collègues</li>
                                    <li>Je reconnais le travail ou le mérite des autres</li>
                                </ul>
                            </div>
                        </div>
                    </div>
                    <div class="col-lg-4">
                        <div class="single-value">
                            <div class="title">
                            <div class="title-content">
                                <h2>Innovation</h2>
                            </div> 
                            </div>
                            <div class="content">
                                <ul>
                                    <li>Allez en contacte des prospects</li>
                                    <li>Informer les clients par SMS (sur la disponibilité des cartes…)</li>
                                    <li>Déclaration une journée porte ouverte avec port obligatoire de la tenue des valeurs</li>
                                    <li>Institutionnaliser et promouvoir le slogan de la LCB Bank</li>
                                </ul>
                            </div>
                        </div>
                    </div>
                    <div class="col-lg-6">
                        <div class="single-value">
                            <div class="title">
                            <div class="title-content">
                                <h2>Respect</h2>
                            </div> 
                            </div>
                            <div class="content">
                                <ul>
                                    <li>Je mets mon téléphone personnel en mode silencieux et je réponds à mes appels en dehors de la présence du client</li>
                                    <li>Je réponds avec courtoisie et je traite les autres avec égard</li>
                                    <li>Je respecte les différents échelons de la ligne hiérarchique et le périmètre qui relève de l’autre </li>
                                    <li>Je respecte les missions des stagiaires, vigiles, femmes de ménage</li>
                                    <li>Je m’habile de manière professionnelle et décente, je ne me maquille pas et ne mange pas devant les clients</li>
                                </ul>
                            </div>
                        </div>
                        
                    </div>
                    <div class="col-lg-6">
                        <div class="single-value">
                            <div class="title">
                            <div class="title-content">
                                <h2>Performance</h2>
                            </div> 
                            </div>
                            <div class="content">
                                <ul>
                                    <li>J’évite d’aller de bureau en bureau de manière prolongée pour des échanges non professionnels </li>
                                    <li>Je respecte les délais et je fais tout pour atteindre les objectifs fixés</li>
                                    <li>Je renseigne et oriente tout client faisant appel à moi et je fais preuve de diligence et d’efficacité dans le traitement des demandes des clients, je respecte et fais respecter l’ordre d’arriver des clients</li>
                                    <li>J’ouvre ma boite Mail dès mon arrivée le matin et ménage du temps pour les traiter et en cas d’absence j’active l’agent absent dans OUTLOOK</li>
                                    <li>Je range tous les documents confidentiels avant de recevoir un client et de quitter le bureau</li>
                                </ul>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </section>
HTML;
    }

    private static function singlePresentation(?string $name, string $left_contents, string $right_contents, bool $modify, array $display, array $options = [], int $delay = 100): string
    {
        $displayed = !in_array($name, $display) ? self::DISPLAY : null;
        $left_class = array_key_exists('left', $options) ? $options['left'] : null;
        $right_class = array_key_exists('right', $options) ? $options['right'] : null;
        $style = array_key_exists('style', $options) ? $options['style'] : null;
        $title_left = array_key_exists('title_left', $options) ? '<h2>'.$options['title_left'].'</h2>' : null;
        $title_right = array_key_exists('title_right', $options) ? '<h2>'.$options['title_right'].'</h2>' : null;
        return <<<HTML
        <section class="left-right-section wow fadeInUp" {$style} data-wow-delay="{$delay}ms"  {$displayed}>
            <div class="container">
                <div class="row">
                    <div class="col-lg-6">
                        <div class="left-item {$left_class}">
                            {$title_left}
                            {$left_contents}
                        </div>
                    </div>
                    <div class="col-lg-6">
                        <div class="right-item {$right_class}">
                            {$title_right}
                            {$right_contents}
                        </div>
                    </div>
                </div>
            </div>
        </section>
HTML;
    }

    private static function single_feature(array $features = [], int $delay) {
        $image = array_key_exists('image', $features) ? $features['image'] : AppFunction::getImgRoute('img_3.jpg');
        $title = array_key_exists('title', $features) ? $features['title'] : null;
        $description = array_key_exists('description', $features) ? $features['description'] : null;
        $redirect_url = array_key_exists('redirect_url', $features) ? AppFunction::pageURL($features['redirect_url']) : null;
        return <<<HTML
        <div class="col-lg-4 wow fadeInUp" data-wow-delay="{$delay}ms">
            <div class="feature-1">
                <img src="{$image}" class="img-fluid" alt="">
                <div class="feature-1-content">
                    <h2>{$title}</h2>
                    <p>{$description}</p>
                    <p><a href="{$redirect_url}" class="more">En savoir plus</a></p>
                </div>
            </div>
        </div>
HTML;
    }

    private static function form(): Form
    {
        return new Form();
    }

    private static function input(array $inputContent, array $errors): string
    {
        $input = self::form()->input(self::inputContent($inputContent), $errors);
        return <<<HTML
        <div class="col-lg-12 col-md-12">
            {$input}
        </div>
HTML;
    }

    private static function textarea(array $inputContent): string
    {
        $class = 'Bootstrap';
        $input = self::form()->textarea(self::inputContent($inputContent), [], 8, $class);
        return <<<HTML
        <div class="col-lg-12 col-md-12">
            {$input}
        </div>
HTML;
    }

    private static function inputContent(array $element): array
    {
        return [
            'label' =>  $element[0],
            'type'  =>  $element[1],
            'name'  =>  $element[2],
            'value' =>  $element[3],
            'placeholder'   =>  $element[4]
        ];
    }

    private static function pagination(PaginationQuery $pages, string $url): string
    {
        $prev = $pages->prev($url);
        $next = $pages->next($url);
        return <<<HTML
        <div>
            {$prev}
            {$next}
        </div>
HTML;
    }

    private static function singleNews(array $singleNewsContent, bool $display = false): string
    {
        $up_to_date = null;
        $update = null;
        $category = [];
        $image = array_key_exists('image', $singleNewsContent) ? $singleNewsContent['image'] : null;
        $date = array_key_exists('date', $singleNewsContent) ? $singleNewsContent['date'] : null;
        $updated = array_key_exists('updated', $singleNewsContent) ? $singleNewsContent['updated'] : null;
        $slug = array_key_exists('slug', $singleNewsContent) ? AppFunction::pageURL($singleNewsContent['slug']) : null;
        if ($updated != null) {
            $update = '<span class="">' . $updated . '</span>';
            $up_to_date = AppFunction::langVerify() ? '<span><em>updated on </em></span>' : '<span><em>mise à jour le </em></span>';
        }
        $posted = AppFunction::langVerify() ? '<em>posted on</em>' : '<em>posté le</em>';
        $title = array_key_exists('title', $singleNewsContent) ? $singleNewsContent['title'] : null;
        $excerpt = array_key_exists('excerpt', $singleNewsContent) ? $singleNewsContent['excerpt'] : null;
        $categories = array_key_exists('categories', $singleNewsContent) && !empty($singleNewsContent['categories']) ? $singleNewsContent['categories'] : null;

        if (!is_null($categories) && is_array($categories)) {
            foreach ($categories as $categorie) {
                $url = AppFunction::pageURL($categorie);
                $category[] = '<a href="' . $url . '"><span>' . $categorie . '</span></a>';
            }
            $categories = implode(' ', $category);
        } else {
            $categories = $singleNewsContent['categories'] ?? null;
        }

        if (isset($_GET['p'])) (new Session())->set('url_key', $_GET['p']);

        $visibility = $display ? 'display: block' : 'display: none';

        return <<<HTML
        <div class="single-blog-area mb-70" style="{$visibility}">
            <div class="blog-thumbnail">
                <a href="{$slug}"><img src="{$image}" alt=""></a>
            </div>
            <div class="blog-content">
                <span>{$posted}</span> <span>{$date}</span> {$up_to_date} {$update}
                <div>{$categories}</div>
                <a href="{$slug}" title="{$title}" class="post-title">{$title}</a>
                <p>{$excerpt}</p>
            </div>
        </div>
HTML;
    }

    private static function search_area(string $url = '#'): string
    {
        $session = new Session();
        $placeholder = null;
        if (AppFunction::langVerify()){
            $placeholder = "Search";
            $session->set('lang', true);
        }else {
            $session->remove('lang');
            $placeholder = 'Recherche';
        }
        $flash = new Flash();
        $error_message = self::flash_message($flash);
        $flash->unset_flash();
        return <<<HTML
        <div class="single-widget-area search-widget">
            <form action="{$url}" method="GET" class="mb-5">
                <input type="search" name="q" placeholder="{$placeholder}">
                <button type="submit">{$placeholder}</button>
            </form>
            {$error_message}
        </div>
HTML;
    }

    private static function singleCategory(string $url, string $category): string
    {
        return '<li><a href="' . $url . '">' . $category . '</a></li>';
    }

    private static function category_area(array $categories = []): ?string
    {
        $categorie = null;
        $cats = [];
        if (!empty($categories)) {
            foreach ($categories as $url => $category) {
                $cats[] = self::singleCategory($url, $category);
            }
            $categorie = implode(PHP_EOL, $cats);
            return <<<HTML
            <div class="single-widget-area cata-widget">
                <div class="widget-heading">
                    <div class="line"></div>
                    <h4>Categories</h4>
                </div>

                <ul>
                    {$categorie}
                </ul>
            </div>
HTML;
        } else {
            return null;
        }
    }

    //PEUT ÊTRE UTILISER PLUS TARD
    /*          ----- LA CONSTITUTION D'UN PARAGRAPHE ------
    private static function paragraph(array $paragraphContent = []): string
    {
        $image = array_key_exists('image', $paragraphContent) ? '<img src="'.$paragraphContent['image'].'" alt="">' : null;
        $content = array_key_exists('content', $paragraphContent) ? $paragraphContent['content'] : null;
        $title = array_key_exists('title', $paragraphContent) ? '<h3>'.$paragraphContent['title'].'</h3' : null;
        return <<<HTML
        <div>
            {$title}
            {$image}
            <p>{$content}</p>
        </div>
HTML;
    }*/

    private static function singleFact(array $singleFactsContent): ?string
    {
        $icon = array_key_exists('icon', $singleFactsContent) ? $singleFactsContent['icon'] : 'fa-diamond';
        $counter = array_key_exists('counter', $singleFactsContent) ? $singleFactsContent['counter'] : 12;
        $label = array_key_exists('label', $singleFactsContent) ? $singleFactsContent['label'] : 'Awards';
        $single_facts =  <<<HTML
        <div class="single-cool-fact white d-flex align-items-center mt-50">
            <div class="scf-icon mr-15">
                <i class="{$icon}"></i>
            </div>
            <div class="scf-text">
                <h2><span class="counter">{$counter}</span></h2>
                <p>{$label}</p>
            </div>
        </div>
HTML;
        return (!empty($icon) && !empty($counter) && !empty($label)) ? $single_facts : null;
    }

    private static function singleAccordion(array $singleAccordionContent): string
    {
        $title = array_key_exists('title', $singleAccordionContent) ? $singleAccordionContent['title'] : null;
        $content = array_key_exists('content', $singleAccordionContent) ? $singleAccordionContent['content'] : null;
        $control = array_key_exists('control', $singleAccordionContent) ? $singleAccordionContent['control'] : null;
        return <<<HTML
        <div class="panel single-accordion">
            <h6>
                <a role="button" aria-expanded="true" aria-controls="{$control}" class="collapsed" data-parent="#accordion" data-toggle="collapse" href="#{$control}">
                    {$title}
                    <span class="accor-open"><i class="fa fa-plus" aria-hidden="true"></i></span>
                    <span class="accor-close"><i class="fa fa-minus" aria-hidden="true"></i></span>
                </a>
            </h6>
            <div id="{$control}" class="accordion-content collapse">
                <div class="accordion_section">{$content}</div>
            </div>
        </div>
HTML;
    }

    private static function thumbnail(string $img_src): string
    {
        return '<div class="special-feature-thumbnail bg-img jarallax" style="background-image: url(' . $img_src . ');"></div>';
    }

    private static function feature_content(array $featureContent, bool $right_url, bool $style = false): string
    {
        $title = array_key_exists('title', $featureContent) ? $featureContent['title'] : 'LCB Bank';
        $introduction = array_key_exists('introduction', $featureContent) ? $featureContent['introduction'] : 'LCB Bank';
        $button = AppFunction::langVerify() ? 'More' : 'En Savoir Plus';
        $lien = array_key_exists('url', $featureContent) && !empty($featureContent['url']) ? $featureContent['url'] : '#';
        $style_2 = $style === true ? 'btn-2' : '';
        return <<<HTML
        <div class="special-feature-content section-padding-100">
            <div class="feature-text">
                <div class="section-heading white mb-50">
                    <div class="line"></div>
                    <h2>{$title}</h2>
                </div>
                <h6>{$introduction}</h6>
                <a href="{$lien}" class="btn credit-btn {$style_2} box-shadow">{$button}</a>
            </div>
        </div>
HTML;
    }

    private static function contact_form(string $url, ?Flash $flash = null): string
    {
        $map = self::map();
        $header = AppFunction::langVerify() ? 'Send a message' : 'Envoyer un message';
        $name = AppFunction::langVerify() ? 'Your Name' : 'Votre Nom';
        $email = AppFunction::langVerify() ? 'Your Email' : 'Votre Email';
        $subject = AppFunction::langVerify() ? 'Your Subject' : 'Le sujet de votre message';
        $message = AppFunction::langVerify() ? 'Your Message' : 'Votre Message';
        $button = AppFunction::langVerify() ? 'Send' : 'Envoyer';
        if (AppFunction::langVerify()) $header = 'Send Message';
        $error_message = self::flash_message($flash);
        $flash->unset_flash();
        return <<<HTML
        <div class="map-area" id="form">
            {$map}
            <div class="contact---area">
                <div class="container">
                    <div class="row">
                        <div class="col-12 col-lg-8">
                            <div class="contact-form-area contact-page">
                                {$error_message}
                                <h4 class="mb-50">{$header}</h4>
                                <form action="{$url}" method="post">
                                    <div class="row">
                                        <div class="col-lg-6">
                                            <div class="form-group">
                                                <input type="text" class="form-control" name="nom" placeholder="{$name}*">
                                            </div>
                                        </div>
                                        <div class="col-lg-6">
                                            <div class="form-group">
                                                <input type="email" class="form-control" name="email" placeholder="{$email}*">
                                            </div>
                                        </div>
                                        <div class="col-12">
                                            <div class="form-group">
                                                <input type="text" class="form-control" name="sujet" placeholder="{$subject}*">
                                            </div>
                                        </div>
                                        <div class="col-12">
                                            <div class="form-group">
                                                <textarea name="message" class="form-control" name="message" cols="30" rows="10" placeholder="{$message}*"></textarea>
                                            </div>
                                        </div>
                                        <div class="col-12">
                                            <button class="btn credit-btn mt-30" type="submit">{$button}</button>
                                        </div>
                                    </div>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
HTML;
    }

    private static function tabCorrespondance(array $tabUrl, array $tabContent): string
    {
        $controls = [];
        $contents = [];
        if (count($tabUrl) === count($tabContent)) {
            for ($i = 0; $i < count($tabContent); $i++) {
                $controls[] = self::tabControl($tabUrl[$i], $i, $i === 0);
                $contents[] = self::tabContent($tabUrl[$i], $tabContent[$i], $i, $i === 0);
            }
        }
        $control = implode(PHP_EOL, $controls);
        $content = implode(PHP_EOL, $contents);
        return <<<HTML
        <div class="credit-tabs-content">
            <ul class="nav nav-tabs" id="myTab" role="tablist">
                {$control}
            </ul>
            <div class="container">
                <div class="tab-content mb-100" id="myTabContent">
                    {$content}
                </div>
            </div>
        </div>
HTML;
    }

    private static function tabControl(string $control, int $id, bool $aria = false): string
    {
        $selected = 'aria-selected="false"';
        $control_href = str_replace(' ', '-', strtolower($control));
        $class = 'nav-link';
        if ($aria === true) {
            $selected = 'aria-selected="true"';
            $class = 'nav-link active';
        }
        return <<<HTML
            <li class="nav-item">
                <a class="{$class}" id="tab--{$id}" data-toggle="tab" href="#{$control_href}{$id}" role="tab" aria-controls="{$control_href}{$id}" {$selected}>{$control}</a>
            </li>
HTML;
    }
    private static function tabContent(string $control, string $content, int $id, bool $show = false): string
    {
        $class = $show === true ? 'tab-pane fade show active' : 'tab-pane fade';
        $control_id = str_replace(' ', '-', strtolower($control));
        return <<<HTML
        <div class="{$class}" id="{$control_id}{$id}" role="tabpanel" aria-labelledby="tab--{$id}">
            <div class="credit-tab-content">
                <div class="credit-tab-text">
                    <p>{$content}</p>
                </div>
            </div>
        </div>
HTML;
    }

    private static function reasons(array $elements): string
    {
        $delay = 500;
        $reasons = [];
        foreach($elements as $k => $element) {
            $k = 0 ? $delay = 600 : $delay += 100;
            $reasons[] = self::reason($element, $delay);
        }
        $items = implode(PHP_EOL, $reasons);
        return <<<HTML
        <ul>
            {$items}
        </ul>
HTML;
    }

    private static function reason(string $element, int $delay): string
    {
        return '<li class="wow fadeInRight" data-wow-duration="1s" data-wow-delay="'.$delay.'ms">'.$element.'</li>';
    }

    public static function works(array $works): string
    {
        $delay = 900;
        $step = 0;
        $work_content = [];
        foreach($works as $k => $work) {
            if (is_array($work)) {
                $k = 0 ? $delay = 900 : $delay += 200;
                $step++;
                $work_content[] = self::single_work($work, $delay, $step, 12/count($works));
            }
        }
        return implode(PHP_EOL, $work_content);
    }

    public static function single_work(array $contents, int $delay, int $step, int $col): string
    {
        $title = array_key_exists('title', $contents) ? $contents['title'] : null;
        $description = array_key_exists('description', $contents) ? $contents['description'] : null;
        $illustration = array_key_exists('icon', $contents) && !empty($contents['icon']) ? '<i class="fa fa-'.$contents['icon'].'"></i>' : $step;
        return <<<HTML
        <div class="col-lg-{$col} col-md-4">
            <div class="single-work wow fadeInUp" data-wow-duration="1s" data-wow-delay="{$delay}ms">
                <span>{$illustration}</span>
                <h3>{$title}</h3>
                <p>{$description}</p>
            </div>
        </div>
HTML;
    }

    private static function flashed(): array
    {
        $session = new Flash();
        return $session->flash();
    }

    private static function flash_message(Flash $flash): ?string
    {
        if (!empty(self::flashed())) {
            foreach (self::flashed() as $type => $msg) {
                $alert = $type;
                if ($type === 'error') $alert = 'danger';
                return '<div class="alert alert-' . $alert . '" style="margin: 0 20px">' . $flash->get($type) . '</div>';
            }
        } else {
            return null;
        }
    }

    private static function map(): string
    {
        return '<iframe src="https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d994.6759980673154!2d15.278971950980512!3d-4.277633227916633!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x1a6a330f3379646b%3A0xfcb02dfe43bc7eec!2sLCB%20BANK%20headquarter!5e0!3m2!1sen!2scg!4v1601378028334!5m2!1sen!2scg" width="600" height="450" frameborder="0" style="border:0;" allowfullscreen="" aria-hidden="false" tabindex="0"></iframe>';
    }

    private static function getSectionName(string $name): string
    {
        return self::MODIFY_LABEL . $name;
    }
}
