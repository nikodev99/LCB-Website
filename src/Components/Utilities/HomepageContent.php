<?php


namespace Web\App\Components\Utilities;

use Web\App\Components\Component;
use Web\App\Core\AppFunction;

class HomepageContent
{

    public static function preloader(): string
    {
        return <<<HTML
        <div class="preloader d-flex align-items-center justify-content-center">
            <div class="lds-ellipsis">
                <div></div>
                <div></div>
                <div></div>
                <div></div>
            </div>
        </div>
HTML;
    }

    public static function getMainCarousel(array $carouselElements = []): string
    {
        $main_carousel = [];
        foreach ($carouselElements as $elements) {
            $main_carousel[] = self::carousel_construct($elements);
        }
        return implode(PHP_EOL, $main_carousel);
    }

    public static function getFeaturesArea(array $featuresElements = []): string
    {
        $delay = 0;
        $single_features = [];
        foreach ($featuresElements as $k => $singlefeature) {
            $k=== 0 ? $delay = 100 : $delay += 200;
            $single_features[] = self::featuresAreaConstruct($singlefeature, $delay);
        }
        return implode(PHP_EOL, $single_features);
    }

    public static function getCTAContent(array $ctaElements = []): string
    {
        return self::ctaArea($ctaElements);
    }

    public static function getInTouch(array $contacts = []):string
    {
        $phrase = $contacts['title'] ?? null;
        $button = $contacts['button'] ?? null;
        $url = array_key_exists('url', $contacts) ? AppFunction::pageURL($contacts['url']) : "#";
        return <<<HTML
        <div class="col-12">
            <div class="cta-content d-flex flex-wrap align-items-center justify-content-between">
                <div class="cta-text">
                    <h4>{$phrase}</h4>
                </div>
                <div class="cta-btn">
                    <a href="{$url}" class="btn credit-btn box-shadow">{$button}</a>
                </div>
            </div>
        </div>
HTML;
    }

    public static function getServicesArea(array $services = []): string
    {
        $header_delay = 100;
        $header_main_title = AppFunction::langVerify() ? 'Take a look at our' : "Un coup d'oeil sur";
        $header_title = AppFunction::langVerify() ? 'Services' : 'Nos services';
        $servicesList = [];
        if (array_key_exists('services', $services)) {
            if (is_array($services['services'])) {
                foreach ($services['services'] as $service) {
                    $header_delay += 100;
                    $servicesList[] = self::services($service, $header_delay);
                }
            }
        }
        $serviceList = implode(PHP_EOL, $servicesList);
        return <<<HTML
        <div class="row">
            <div class="col-12">
                <div class="section-heading text-center mb-100 wow fadeInUp" data-wow-delay="{$header_delay}ms">
                    <div class="line"></div>
                    <p>{$header_main_title}</p>
                    <h2>{$header_title}</h2>
                </div>
            </div>
        </div>
        <div class="row">
            {$serviceList}
        </div>
HTML;

    }

    public static function getNewsArea(array $newsItems = []): string
    {
        $content = [];
        $delay = 100;
        $title = AppFunction::langVerify() ? 'Take a look at our' : "Un coup d'oeil sur nos";
        $main_title = AppFunction::langVerify() ? 'Breaking news' : 'Dernières actualités';
        if (array_key_exists('items', $newsItems) && is_array($newsItems['items'])) {
            foreach($newsItems['items'] as $newsItem) {
                $delay += 100;
                $content[] = self::newsAreaItem($newsItem, $delay);
            }
        }
        $items = implode(PHP_EOL, $content);
        return <<<HTML
        <div class="row align-items-end justify-content-center">
            <div class="row">
                <div class="col-12">
                    <div class="section-heading text-center mb-100 wow fadeInUp" data-wow-delay="100ms">
                        <div class="line"></div>
                        <p>{$title}</p>
                        <h2>{$main_title}</h2>
                    </div>
                </div>
                <div class="col-12">
                    <div id="carousel">
                        {$items}
                    </div>
                </div>
            </div>
        </div>
HTML;
    }

    public static function getNewsletterArea(array $newsletter = [], bool $modify, string $url = '#', ?string $data_pointer): string
    {
        $modify_button = null;
        $image = $newsletter['image'] ?? "../../webroot/img/bg-img/6.jpg";
        $title = array_key_exists('title', $newsletter) ? $newsletter['title'] : null;
        $description = array_key_exists('description', $newsletter) ? $newsletter['description'] : null;
        $button = AppFunction::langVerify() ? 'Subscribe' : "S'abonner";
        if($modify) $modify_button = Component::modify_anchor($data_pointer);
        return <<<HTML
        <section class="newsletter-area section-padding-100 bg-img jarallax" style="background-image: url({$image});">
            {$modify_button}
            <div class="container">
                <div class="row justify-content-center">
                    <div class="col-12 col-sm-10 col-lg-8">
                        <div class="nl-content text-center">
                            <h2>{$title}</h2>
                            <form action="{$url}" method="post">
                                <input type="email" name="nl-email" id="nlemail" placeholder="Your e-mail">
                                <button type="submit">{$button}</button>
                            </form>
                            <p>{$description}</p>
                        </div>
                    </div>
                </div>
            </div>
        </section>
HTML;
    }

    private static function carousel_construct(array $carouselElements = []): string
    {
        $image = $carouselElements['image'] ?? null;
        $title = $carouselElements['title'] ?? null;
        $description = $carouselElements['description'] ?? null;
        $url = null;
        if (array_key_exists('url', $carouselElements)) {
            $url = AppFunction::langVerify() ? '/en/page/'. $carouselElements['url'] : '/fr/page/' . $carouselElements['url'];
        } ;
        $button = AppFunction::langVerify() ? 'More' : "Découvrir";
        return <<<HTML
        <div class="single-slide bg-img">
            <div class="slide-bg-img bg-img bg-overlay" style="background-image: url({$image});"></div>
            <div class="container h-100">
                <div class="row h-100 align-items-center justify-content-center">
                    <div class="col-12 col-lg-9">
                        <div class="welcome-text text-center">
                            <h2 data-animation="fadeInUp" data-delay="100ms">{$title}</h2>
                            <p data-animation="fadeInUp" data-delay="300ms">{$description}</p>
                            <a href="{$url}" class="btn credit-btn mt-50" data-animation="fadeInUp" data-delay="500ms">{$button}</a>
                        </div>
                    </div>
                </div>
            </div>
            <div class="slide-du-indicator"></div>
        </div>
HTML;
    }

    private static function featuresAreaConstruct(array $headers,  int $delay = 100): string
    {
        $welcome = $headers['header'] ?? null;
        $header_messge = '<div class="section-heading">
                            <div class="line"></div>
                                <p> ' .$welcome .'</p>
                                 <h2>LCB Bank</h2>
                          </div>';
        $feature = self::features($headers);
        if (array_key_exists('header', $headers) && empty($headers['header'])) $header_messge = null;
        return <<<HTML
        <div class="col-12 col-sm-6 col-lg-3">
            <div class="single-features-area mb-100 wow fadeInUp" data-wow-delay="{$delay}ms">
                {$header_messge}
                {$feature}
            </div>
        </div>
HTML;
    }

    private static function features(array $features): ?string
    {
        $image = $features['image'] ?? null;
        $title = $features['title'] ?? null;
        $description = $features['description'] ?? null;
        $button = AppFunction::langVerify() ? 'More' : 'En Savoir Plus';
        $url = null;
        if (array_key_exists('url', $features)) {
            $url = AppFunction::langVerify() ? '/en/page/' . $features['url'] : '/fr/page/' . $features['url'];
        }
        $feature = '<img src="'.$image.'" alt="">
                    <h5>' .$title. '</h5>
                    <div class="feature-overload">
                        <h6>' .$description. '</h6>
                        <a href="'.$url.'" class="btn credit-btn mt-50">' .$button. '</a>
                    </div>';
        if (is_null($image) | is_null($title) | is_null($description)) $feature = null;
        if (empty($image) | empty($title) | empty($description)) $feature = null;
        return $feature;
    }

    private static function ctaArea (array $ctaAreaContents): string
    {
        $url_display = array_key_exists('url', $ctaAreaContents) && !empty($ctaAreaContents['url']);
        $url = $url_display ? AppFunction::pageURL($ctaAreaContents['url']) : '#';
        $image = array_key_exists('image', $ctaAreaContents) ? self::ctaAreaImage($ctaAreaContents['image']) : null;
        $header = array_key_exists('header', $ctaAreaContents) ? self::ctaContent($ctaAreaContents['header']) : null;
        $skills = array_key_exists('skills', $ctaAreaContents) && !empty($ctaAreaContents['skills']) ? $ctaAreaContents['skills'] : null;
        $button = AppFunction::langVerify() ? 'Read More' : "Découvrir";
        $multiple_skill = [];
        if (!is_null($skills) && is_array($skills)) {
            foreach ($skills as $key => $skill) {
                if ($key === 0) {
                    $key = null;
                }else {
                    $key = $key + 1;
                }
                $id = 'circle' . $key;
                $multiple_skill[] = self::ctaSkills($skill, $id);
            }
        }
        $multiple_skills = implode(PHP_EOL, $multiple_skill);
        $display_url = $url_display ? '<a href="'.$url.'" class="btn credit-btn box-shadow btn-2">'.$button.'</a>' : null;
        return <<<HTML
        {$image}
        <div class="cta-content">
            {$header}
            <div class="d-flex flex-wrap mt-50">
                {$multiple_skills}
            </div>
            {$display_url}
        </div> 
HTML;
    }

    private static function ctaAreaImage(?string $image = null): string
    {
        return '<div class="cta-thumbnail bg-img jarallax" style="background-image: url('.$image.');"></div>';
    }

    private static function ctaContent(array $contents): string
    {
        $main_title = $contents['main-title'] ?? null;
        $title = $contents['title'] ?? null;
        $text = $contents['content'] ?? null;

        return <<<HTML
        <div class="section-heading white">
            <div class="line"></div>
            <p>{$main_title}</p>
            <h2>{$title}</h2>
        </div>
        <h6>{$text}</h6>
HTML;
    }

    private static function ctaSkills(array $skills, string $id): string
    {
        $percent = $skills['percent'] ?? null;
        $description = $skills['description'] ?? null;
        $percent_view = $percent / 100;

        return <<<HTML
        <div class="single-skils-area mb-70 mr-5">
            <div id="{$id}" class="circle" data-value="{$percent_view}">
                <div class="skills-text">
                    <span>{$percent}%</span>
                </div>
            </div>
            <p>{$description}</p>
        </div>
HTML;
    }

    private static function services(array $services, int $delay): string
    {
        $icon = $services['icon'] ?? null;
        $title = $services['title'] ?? null;
        $description = $services['description'] ?? null;
        return <<<HTML
            <div class="col-12 col-md-6 col-lg-4">
            <div class="single-service-area d-flex mb-100 wow fadeInUp" data-wow-delay="{$delay}ms">
                <div class="icon">
                    <i class="{$icon}"></i>
                </div>
                <div class="text">
                    <h5>{$title}</h5>
                    <p>{$description}</p>
                </div>
            </div>
        </div>
HTML;

    }

    private static function newsAreaItem(array $itemElement, int $delay): ?string
    {
        $image = $itemElement['image'] ?? null;
        $title = AppFunction::excerpt($itemElement['title'], 75) ?? null;
        $date = $itemElement['date'] ?? null;
        $description = $itemElement['description'] ?? null;
        $display = array_key_exists('online', $itemElement) & $itemElement['online'] === 1 ? false : true;
        $url = AppFunction::pageURL($itemElement['url']) ?? null;
        return $display ? null : <<<HTML
        <div class="item wow fadeInUp" data-wow-delay="{$delay}ms" {$display}>
            <div class="item-img">
            <a href="{$url}"><img class="img-fluid" src="{$image}" alt=""></a>
            </div>
            <div class="item-body">
                <div class="item-title">
                    <a href="{$url}" title="{$title}">{$title}</a>
                </div>
                <div class="item-description">
                    <div class="meta-news">
                        <p><i class="fa fa-calendar"></i>{$date}</p>
                    </div>
                    <p>{$description}</p>
                </div>
            </div>
        </div>
HTML;
    }

}