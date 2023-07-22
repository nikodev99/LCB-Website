<?php

namespace Web\App\Components;

use Web\App\Components\Utilities\DashboardContent;
use Web\App\Components\Utilities\Footer;
use Web\App\Components\Utilities\HomepageContent;
use Web\App\Components\Utilities\Modal;
use Web\App\Components\Utilities\NavBar;
use Web\App\Components\Utilities\PageContent;
use Web\App\Core\AppFunction;
use Web\App\Core\Request;
use Web\App\Core\session\Session;
use Web\App\Render\Lang\en\EnRender;
use Web\App\Render\Lang\fr\FrRender;

class PageRender
{
    private $menu_items = [];
    private $carousel_items = [];
    private $features = [];

    private $multipleFeatures = [];
    private $reasons = [];
    private $works = [];
    private $title = null;

    private $ctaElements = [];
    private $contacts = [];
    private $contact_area = [];
    private $services = [];
    private $news = [];
    private $newsletter = [];
    private $footer = [];
    private $breadcrumb = [];
    private $third_url = [];
    private $about = [];
    private $callToAction = [];
    private $accordion = [];
    private $accordion_area = [];
    private $left_features = [];
    private $right_features = [];
    private $tabs = [];
    private $posts = [];
    private $single_posts = [];
    private $textes = [];
    private $presentations = [];
    private $metadata = [];

    private $s;

    private $request;
    private $connected;
    private $url;
    private $pages;

    public function __construct(?string $url = null)
    {
        $this->url = $url;
        $this->request = Request::getRequest();
        $this->connected = AppFunction::user_connected();
        $this->s = new Session();
        if (AppFunction::requestLang($this->request)) {
            $this->menu_items = EnRender::getMenuTable();
            $this->carousel_items = EnRender::getCarousel();
            $this->features = EnRender::getFeatures();
            $this->ctaElements = EnRender::getCTA();
            $this->contacts = EnRender::CONTACT;
            $this->contact_area = EnRender::getContactArea($this->url);
            $this->services = EnRender::getServices();
            $this->news = EnRender::getNewsArea();
            $this->newsletter = EnRender::getNewsletter();
            $this->footer = EnRender::getFooter();
            $this->breadcrumb = EnRender::getBreadcrumb($this->url);
            $this->third_url = EnRender::getThirdBreadcrumb($this->url);
            $this->about = EnRender::getAbout($this->url);
            $this->callToAction = EnRender::getExpose($this->url);
            $this->accordion = EnRender::getAccordion($this->url);
            //$this->left_features = EnRender::LEFT_FEATURES;
            $this->right_features = EnRender::getLeftExpose($this->url);
            $this->tabs = EnRender::getTabs($this->url);
            $this->posts = EnRender::getPosts();
            $this->single_posts = EnRender::getPosts($this->url, true);
            $this->textes = EnRender::getText($this->url);
            $this->presentations = EnRender::getPresentations($this->url);
            $this->reasons = EnRender::getReasons($this->url);
            $this->works = EnRender::getWork($this->url);
            $this->title = EnRender::getTitle($this->url);
            $this->metadata = EnRender::getMetadata($this->url);
            $this->pages = EnRender::getPages();
            $this->multipleFeatures = EnRender::getMultipleFeatures($this->url);
        }else {
            $this->menu_items = FrRender::getMenuTable();
            $this->carousel_items = FrRender::getCarousel();
            $this->features = FrRender::getFeatures();
            $this->ctaElements = FrRender::getCTA();
            $this->contacts = FrRender::CONTACT;
            $this->contact_area = FrRender::getContactArea($this->url);
            $this->services = FrRender::getServices();
            $this->news = FrRender::getNewsArea();
            $this->newsletter = FrRender::getNewsletter();
            $this->footer = FrRender::getFooter();
            $this->breadcrumb = FrRender::getBreadcrumb($this->url);
            $this->third_url = FrRender::getThirdBreadcrumb($this->url);
            $this->about = FrRender::getAbout($this->url);
            $this->callToAction = FrRender::getExpose($this->url);
            $this->accordion = FrRender::getAccordion($this->url);
            //$this->left_features = FrRender::LEFT_FEATURES;
            $this->right_features = FrRender::getLeftExpose($this->url);
            $this->tabs = FrRender::getTabs($this->url);
            $this->posts = FrRender::getPosts();
            $this->single_posts = FrRender::getPosts($this->url, true);
            $this->textes = FrRender::getText($this->url);
            $this->presentations = FrRender::getPresentations($this->url);
            $this->reasons = FrRender::getReasons($this->url);
            $this->works = FrRender::getWork($this->url);
            $this->title = FrRender::getTitle($this->url);
            $this->metadata = FrRender::getMetadata($this->url);
            $this->pages = FrRender::getPages();
            $this->multipleFeatures = FrRender::getMultipleFeatures($this->url);
        }

    }

    //NAVBAR CONCERN

    public function setMenu(): string
    {
        return '<ul>'.

            NavBar::getMenu($this->menu_items)
           
        .'</ul>';
    }

    public function getLang(array $allLang): string
    {
        return NavBar::multyLang($allLang, $this->request);
    }

    //FOOTER CONCERN

    public function getFooter(): string
    {
        return Footer::getFooter($this->footer);
    }

    //HOMEPAGE CONCERN

    public function getMainCarousel(): string
    {
        foreach ($this->carousel_items as $key => $value) {
            if (strpos($this->carousel_items[$key]['title'], '{') !== false) {
                $this->carousel_items[$key]['title'] = str_replace(['{%', '%}'], ['<span>', '</span>'], $this->carousel_items[$key]['title']);
            }
        }
        return HomepageContent::getMainCarousel($this->carousel_items);
    }

    public function getFeaturesArea(): string
    {
        return HomepageContent::getFeaturesArea($this->features);
    }

    public function getCTAArea(): string
    {
        return HomepageContent::getCTAContent($this->ctaElements);
    }

    public function gettingInTouch(): string
    {
        return HomepageContent::getInTouch($this->contacts);
    }

    public function getServicesArea(): string
    {
        return HomepageContent::getServicesArea($this->services);
    }

    public function getNewsArea(): string
    {
        return HomepageContent::getNewsArea($this->news);
    }

    public function getNewsletterArea(string $url = '#', ?string $data_pointer = null): string
    {
        return HomepageContent::getNewsletterArea($this->newsletter, $this->connected, $url, $data_pointer);
    }

    //ADMIN CONCERN

    public function getNavItems(array $navItems): string
    {
        return NavBar::adminNavItem($navItems);
    }

    public function getProgressBar(array $progress): string
    {
        return NavBar::getBarProgress($progress);
    }

    public function getGrid(array $cardElements): string
    {
        return DashboardContent::getGrid($cardElements);
    }

    //PAGES CONCERN

    public function getBreadcrumb(array $display = [], string $data_pointer = null): string
    {
        if($this->s->get('redirect') !== null) $this->s->remove('redirect');
        return PageContent::getBreadcrumb($this->breadcrumb, $this->connected, $display, $data_pointer, $this->third_url);
    }

    public function getAboutArea(array $display = [], ?string $data_pointer = null): string
    {
        if ($this->s->get('url_key') !== null) $this->s->remove('url_key');
        return PageContent::aboutArea($this->about, $this->connected, $display, $data_pointer);
    }

    public function getReasonArea(array $display= [], ?string $data_pointer = null): string
    {
        if ($this->s->get('url_key') !== null) $this->s->remove('url_key');
        return PageContent::getReasons($this->reasons, $this->connected, $display, $data_pointer);
    }

    public function getMultipleFeature(array $display, ?string $data_pointer = null): string
    {
        if ($this->s->get('url_key') !== null) $this->s->remove('url_key');
        return PageContent::multipleFeature($this->multipleFeatures, $this->connected, $display, $data_pointer);
    }

    public function getCallToAction(array $display = [], ?string $data_pointer = null): string
    {
        if ($this->s->get('url_key') !== null) $this->s->remove('url_key');
        return PageContent::callToAction($this->callToAction, $this->connected, $display, $data_pointer);
    }

    public function getAccordionArea(array $display = [], ?string $data_pointer = null): string
    {
        if ($this->s->get('url_key') !== null) $this->s->remove('url_key');
        return PageContent::accordion_area($this->accordion_area, $this->connected, $display, $data_pointer);
    }

    public function getAccordion(array $display = [], ?string $data_pointer = null): string
    {
        if ($this->s->get('url_key') !== null) $this->s->remove('url_key');
        return PageContent::accordion($this->accordion, $this->connected, $display, $data_pointer);
    }

    public function getLeftFeatures(array $display = [], ?string $data_pointer = null): string
    {
        if ($this->s->get('url_key') !== null) $this->s->remove('url_key');
        return PageContent::feature_left($this->left_features, $this->connected, $display, $data_pointer);
    }

    public function getWorkArea(array $display = [], ?string $data_pointer = null): string
    {
        if ($this->s->get('url_key') !== null) $this->s->remove('url_key');
        return PageContent::works_area($this->works, $this->title, $this->connected, $display, $data_pointer);
    }

    public function getRightFeatures(array $display = [], ?string $data_pointer = null): string
    {
        if ($this->s->get('url_key') !== null) $this->s->remove('url_key');
        return PageContent::feature_right($this->right_features, $this->connected, $display, $data_pointer);
    }

    public function getContactArea(string $url, array $display = [], ?string $data_pointer = null): string
    {
        if ($this->s->get('url_key') !== null) $this->s->remove('url_key');
        return PageContent::contact_area($this->contact_area, $url, $this->connected, $display, $data_pointer);
    }

    public function getTabs(array $display = [], ?string $data_pointer = null): string
    {
        if ($this->s->get('url_key') !== null) $this->s->remove('url_key');
        return PageContent::tabs($this->tabs, $this->connected, $display, $data_pointer);
    }

    public function getNewsSection(string $url, string $search_url = '#', array $display = [], ?string $data_pointer = null): string
    {
        return PageContent::getNewsArea($url, $this->posts, [], $search_url, $this->connected, $display, $data_pointer, $this->pages);
    }

    public function getSinglePost(string $search_url = '#', array $display = [], ?string $data_pointer = null): string
    {
        return PageContent::singlePost($this->single_posts, [], $search_url, $this->connected, $display, $data_pointer);
    }

    public function getTextArea(array $display = [], ?string $data_pointer = null): string
    {
        return PageContent::text_area($this->textes, $this->connected, $display, $data_pointer);
    }

    public function getMap(array $display = []): string
    {
        return PageContent::map_loaded($display);
    }

    public function getCareerArea(array $display, string $url = '/'): string
    {
        if ($this->s->get('url_key') !== null) $this->s->remove('url_key');
        return PageContent::carreer_area($display, $url);
    }

    public function getValues(array $display): string
    {
        if ($this->s->get('url_key') !== null) $this->s->remove('url_key');
        return PageContent::our_values($display);
    }

    public function getPresentation(array $display, ?string $data_pointer = null): string
    {
        if ($this->s->get('url_key') !== null) $this->s->remove('url_key');
        return PageContent::presentations($this->presentations, $this->connected, $display, $data_pointer);
    }

    public function metadata(): array
    {
        return $this->metadata;
    }

    //MODALS CONCERN

    public function getModal(string $url, string $pointer, string $name): ?string
    {
        return Modal::modal($this->connected, $url, $pointer, $name);
    }
}
