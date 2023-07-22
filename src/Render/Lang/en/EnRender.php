<?php


namespace Web\App\Render\Lang\en;

use Web\App\Core\Model;
use Web\App\Core\Constant;
use Web\App\Core\AppFunction;
use Web\App\Core\models\PaginationQuery;


class EnRender
{
    public const CONTACT = [
        'title' =>  'Do you need help ? Do you want to get in touch we us ?',
        'button'    =>  'Contact us',
        'url'       =>  'contact'
    ];

    public const CARDS = [
        'title' =>  'Bank cards',
        'items' =>  [
            "visa_first_card"     =>  'VISA First Card',
            "visa_green_card"   =>  'VISA Green Card',
            "visa_infinium_card"   =>  'VISA Infinium Card',
            "gimac_banking_card"   =>  'GIMAC banking Card',
        ]
    ];

    public static function getMenuTable(): array
    {
        $menu_items = [];
        $menu_items_url = [];
        $submenu_items = [];
        $menu = Model::getAllMenuItem();
        foreach($menu as $item) {
            $menu_items[] = $item->name_ang;
            $menu_items_url[] = $item->item_url_ang;
            $submenu_items[] = json_decode($item->submenu_items_ang, true);
        }
        $urls = array_combine($menu_items, $menu_items_url);
        $menu_array = array_combine($menu_items, $submenu_items);
        foreach($menu_array as $key => $value) {
            if(is_null($value)) {
                $value = $urls[$key];
                $menu_array[$key] = $value;
            }
        }
        return $menu_array;
    }

    public static function getFooter(): array
    {
        $footer_items = [];
        $footer_urls = [];
        $footer_array = [];
        $footer = Model::getAllMenuItem();
        foreach($footer as $f) {
            $footer_items[] = $f->name_ang;
            $footer_urls[] = json_decode($f->submenu_items_ang, true);
        }
        $array = array_combine($footer_items, $footer_urls);
        foreach($array as $title => $items) {
            if (!is_null($items) && count($items) >= 4) {
                $footer_array[] = [
                    'title' =>  $title,
                    'items' =>  $items
                ];
            } 
        }
        $footer_array[] = self::CARDS;
        return $footer_array;
    }

    public static function getCarousel(): array
    {
        $carousels = [];
        $slides = Model::getAllCarousel(null, [], ['id', 2]);
        foreach($slides as $key => $slide) {
            $carousels[$key] = [
                'image' =>   Constant::BG_PATH . $slide->image,
                'title' =>  $slide->title_ang,
                'description' =>  $slide->description_ang,
                'url'   =>  $slide->url_ang
            ];
        }
        return $carousels;
    }

    public static function getFeatures(): array
    {
        $features = [];
        $feats = Model::getAllFeature();
        foreach($feats as $key => $feat) {
            $features[$key] = [
                'header'    =>  $feat->header_ang,
                "image"     =>  Constant::BG_PATH . $feat->image,
                "title"     =>  $feat->title_ang,
                "description"   =>  $feat->description_ang,
                'url'       =>  $feat->url_ang
            ];
        }
        return $features;
    }

    public static function getCTA(): array
    {
        $ctas = [];
        $cta = Model::getCTA();
        foreach($cta as $v) {
            $ctas = [
                'image' =>  Constant::BG_PATH . $v->image,
                'header'    =>  [
                    "main-title"    =>  $v->main_title_ang,
                    "title" =>  $v->title_ang,
                    "content"   =>  $v->description_ang
                ],
                "skills"    =>  json_decode($v->skills_ang, true),
                'url'   =>  $v->url_ang
            ];
        }
        return $ctas;
    }

    public static function getPages(): PaginationQuery
    {
        return Model::getPostPaginated(['date', 2])[1];
    }

    public static function getNewsArea(): array
    {
        $posts = [];
        $posted = Model::getAllPosts(['online' => '1'], null, [], ['date', 2,
            'limit' =>  6
        ]);
        foreach($posted as $key => $post) {
            $posts['items'][$key] = [
                'image' =>  Constant::BG_PATH . $post->breadcrump,
                'title' =>  $post->title_ang,
                'date'  =>  AppFunction::stamp($post->date, 'd/m/Y à H:i'),
                'description'   =>  AppFunction::excerpt($post->excerpt_ang),
                'url'   =>  $post->slug_ang,
                'online'    =>  (int)$post->online
            ];
        }
        return $posts;
    }

    public static function getServices(): array
    {
        $services = [];
        $serts = Model::getAllServices();
        foreach($serts as $key => $service ) {
            $services['services'][$key] = [
                'icon'  => $service->icon,
                'title' =>  $service->title_ang,
                'description'   =>  $service->description_ang
            ];
        }
        return $services;
    }

    public static function getNewsletter(): array
    {
        $newsletter = [];
        $news = Model::getNewsletterInfos(['id' => 1]);
        if (!empty($news)) {
            $newsletter = [
                'image' =>  Constant::BG_PATH . $news->image,
                'title' =>  $news->title_ang,
                'description'   =>  $news->description_ang
            ];
        }
        return $newsletter;
    }

    public static function getBreadcrumb(?string $url = null): array
    {
        $breadcrumbs = [];
        $breadcrumb = null;
        if (!is_null($url)) $breadcrumb = Model::getBreadcrumb(self::section_content_condition($url));
        if (!is_null($breadcrumb)) {
            $breadcrumbs = [
                'header'    =>  $breadcrumb->header_ang,
                'background'    =>  Constant::BG_PATH . $breadcrumb->background
            ];
        }
        return $breadcrumbs;
    }

    public static function getThirdBreadcrumb(?string $url = null): array
    {
        $third_url = [];
        $bread = !is_null($url) ? Model::getBreadcrumb(self::section_content_condition($url), 'breadcrumb_ang') : null;
        if (!is_null($bread)) {
            if (!is_null($bread->breadcrumb_ang)) {
                $third_url = json_decode($bread->breadcrumb_ang, true);
            }
        }
        return $third_url;
    }

    public static function getAbout(?string $url = null): array
    {
        $abouts = [];
        $about = null;
        if(!is_null($url)) $about = Model::getAbout(self::section_content_condition($url));
        if(!is_null($about)) {
            $abouts = [
                'header'    =>  $about->header_ang,
                'introduction'  =>  $about->introduction_ang,
                'excerpt'   =>  $about->excerpt_ang,
                'url'  =>  $about->redirect_url_ang,
                'image' =>  Constant::BG_PATH . $about->image,
            ];
        }
        return $abouts;
    }

    public static function getExpose(?string $url = null): array
    {
        $exposes = [];
        $expose = null;
        if(!is_null($url)) $expose = Model::getExpose(self::section_content_condition($url));
        if(!is_null($expose)) {
            $exposes = [
                'title'    =>  $expose->title_ang,
                'description'  =>  $expose->description_ang,
                'image' =>  Constant::BG_PATH . $expose->image,
                'single-facts'  =>  json_decode($expose->single_facts, true)
            ];
        }
        return $exposes;
    }

    public static function getAccordion(?string $url = null): array
    {
        $accordions = [];
        $accordion = null;
        if(!is_null($url)) $accordion = Model::getAllAccordion(self::section_content_condition($url));
        if (!empty($accordion)) {
            foreach($accordion as $key => $acc) {
                $accordions['contents'][$key] = [
                    'title' =>  $acc->title_ang,
                    'content' =>  $acc->content_ang,
                    'control' =>  $acc->control,
                ];
            }
        }
        return $accordions;
    }

    public static function getLeftExpose(?string $url = null): array
    {
        $left_exposes = [];
        $left_expose = null;
        if(!is_null($url)) $left_expose = Model::getLeftExpose(self::section_content_condition($url));
        if (!is_null($left_expose)) {
            $left_exposes = [
                'image' =>  Constant::BG_PATH . $left_expose->image,
                'title' =>  $left_expose->title_ang,
                'introduction'  =>  $left_expose->introduction_ang,
                'url'  =>  $left_expose->redirect_url_ang
            ];
        }
        return $left_exposes;
    }

    public static function getContactArea(?string $url = null): array
    {
        $contacts = [];
        $contact = null;
        if(!is_null($url)) $contact = Model::getContact(self::section_content_condition($url));
        if (!is_null($contact)) {
            $contacts = [
                'description'   =>  $contact->description_ang,
                'address'   =>  $contact->address,
                'tel'   => $contact->tel,
                'email' =>  $contact->email,
            ];
        }
        return $contacts;
    }

    public static function getTabs(?string $url = null): array
    {
        $tabs = [];
        $tab = [];
        if(!is_null($url)) $tab = Model::getAllTabs(self::section_content_condition($url));
        if (!empty($tab)) {
            foreach($tab as $tb) {
                $tabs[] = [$tb->tab_title_ang => $tb->tab_content_ang];
            }
        }
        return $tabs;
    }

    public static function getText(?string $url = null): array
    {
        $textes = [];
        $text = null;
        if(!is_null($url)) $text = Model::getText(self::section_content_condition($url));
        if(!is_null($text)) {
            $textes = [
                'title' =>  $text->title_ang,
                'description'   =>  $text->description_ang,
                'content'   =>  $text->content_ang
            ];
        }
        return $textes;
    }

    public static function getPosts(?string $url = null, bool $single_post = false): array
    {
        $posts = [];
        $posted = null;
        if (!$single_post) {
            $posted = Model::getPostPaginated(['date', 2])[0];
            foreach($posted as $key => $post) {
                $posts[$key] = [
                    'image' =>  Constant::BG_PATH . $post->image,
                    'date'  =>  AppFunction::stamp($post->date, 'd/m/Y'),
                    'updated'   =>  !is_null($post->updated) ? AppFunction::stamp($post->updated, 'd/m/Y') : null,
                    'title' =>  $post->title_ang,
                    'excerpt'   =>  AppFunction::excerpt($post->excerpt_ang),
                    'slug'  =>  $post->slug_ang,
                    'categories'    =>  null,
                    'online'    =>  (int)$post->online === 1
                ];
            }
        }else {
            if(!is_null($url)) $posted = Model::getPost(['slug' => $url, 'slug_ang' => $url]);
            if(!is_null($posted)) {
                $excerpt = '<p><strong>' . $posted->excerpt_ang . '</strong></p>';
                $posts = [
                    'image' =>  Constant::BG_PATH . $posted->image,
                    'title' =>  $posted->title_ang,
                    'excerpt'   =>  $excerpt,
                    'content'   =>  $posted->content_ang
                ];
            }
        }
        return $posts;
    }

    public static function getReasons(?string $url = null): array
    {
        $reasons = [];
        $reason = !is_null($url) ? Model::getReason(self::section_content_condition($url)) : null;
        if (!is_null($reason)) {
            $reasons = [
                'image' =>  is_null($reason->image) ? null : Constant::BG_PATH . $reason->image,
                'title' =>  $reason->title_ang,
                'description' =>  $reason->description_ang,
                'items' =>  AppFunction::array_trim(json_decode($reason->items_ang, true)),
            ];
        }
        return $reasons;
    }

    public static function getWork(?string $url = null): array
    {
        $work_content = [];
        $works = !is_null($url) ? Model::getAllWork(self::section_content_condition($url)) : null;
        if (!is_null($works) && !empty($works)) {
            foreach($works as $work) {
                $work_content[] = [
                    'title' =>  $work->title_ang,
                    'description'   =>  $work->description_ang,
                    'icon' =>  $work->icon
                ];
            }
        }
        return $work_content;
    }

    public static function getTitle(?string $url = null): array
    {
        $title = !is_null($url) ? Model::getTitle(self::section_content_condition($url), ['title_ang', 'description_ang']) : null;
        return [
            'title' =>  !is_null($title) && !is_null($title->title_ang) ? $title->title_ang: '',
            'description'   =>  !is_null($title) && !is_null($title->description_ang) ? $title->description_ang: ''
        ];
    }

    public static function getMetadata(?string $url = null): array
    {
        $metadata = [];
        $meta = null;
        if(!is_null($url)) $meta = Model::getMetadata(self::section_content_condition($url));
        if(!is_null($meta)) {
            $metadata = [
                'title' =>  $meta->title_ang,
                'description'   =>  $meta->description_ang,
                'keyword'   =>  !is_null($meta->keywords) ? implode(', ', json_decode($meta->keywords, true)) : 'LCB Bank'
            ];
        }
        return $metadata;
    }

    public static function getMultipleFeatures(?string $url = null): array
    {
        $multipleFeatures = [];
        $m = !is_null($url) ? Model::getMultipleFeatures(self::section_content_condition($url)) : null;
        $t = !is_null($url) ? Model::getTitle(self::section_content_condition($url)) : null;
        if (!is_null($t)) {
            $multipleFeatures['title'] = $t->title_ang; 
            $multipleFeatures['description'] = $t->description_ang; 
        }
        if (!is_null($m) && !empty($m)) {
            foreach($m as $v) {
                $multipleFeatures['features'][] = [
                    'title' =>  $v->title_ang,
                    'description'   =>  $v->description_ang,
                    'redirect_url'  =>  $v->redirect_ang,
                    'image' =>  Constant::BG_PATH . $v->image
                ];
            }
        }
        return $multipleFeatures;
    }

    public static function getPresentations(?string $url = null): array
    {
        $presentations = [];
        $p = !is_null($url) ? Model::getAllPresentation(self::section_content_condition($url)) : null;
        if (!is_null($p)) {
            foreach($p as $v) {
                $presentations[] = [
                    'image' =>  $v->image,
                    'title' =>  $v->title_ang,
                    'content'   =>  $v->content_ang,
                ];
            }
        }
        return $presentations;
    }

    private static function section_content_condition(?string $url = null): ?array
    {
        return !is_null($url) ? ['url' => $url, 'url_ang' => $url] : null;
    }
}