<?php


namespace Web\App\Render\Lang\fr;

use Web\App\Core\Model;
use Web\App\Core\AppFunction;
use Web\App\Core\models\PaginationQuery;

class FrRender
{
    public const CONTACT = [
        'title' =>  'Vous avez besoin d\'aide ? Souhaitez-vous nous contacter ?',
        'button'    =>  'Contacter nous',
        'url'       =>  'contact'
    ];

    public const CARDS = [
        'title' =>  'Cartes Bancaire',
        'items' =>  [
            "carte_visa_first"     =>  'Carte VISA First',
            "carte_visa_green"   =>  'Carte VISA Green',
            "carte_visa_infinium"   =>  'Carte VISA Infinium',
            "carte_visa_gimac"   =>  'Carte bancaire GIMAC',
        ]
    ];

    public static function getMenuTable(): array
    {
        $menu_items = [];
        $menu_items_url = [];
        $submenu_items = [];
        $menu = Model::getAllMenuItem();
        foreach($menu as $item) {
            $menu_items[] = $item->name;
            $menu_items_url[] = $item->item_url;
            $submenu_items[] = json_decode($item->submenu_items, true); 
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
            $footer_items[] = $f->name;
            $footer_urls[] = json_decode($f->submenu_items, true);
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
                'image' =>  AppFunction::getImgRoute($slide->image),
                'title' =>  $slide->title,
                'description' =>  $slide->description,
                'url'   =>  $slide->url
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
                'header'    =>  $feat->header,
                "image"     =>  !is_null($feat->image) ? AppFunction::getImgRoute($feat->image) : null,
                "title"     =>  $feat->title,
                "description"   =>  $feat->description,
                'url'       =>  $feat->url
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
                'image' =>  AppFunction::getImgRoute($v->image),
                'header'    =>  [
                    "main-title"    =>  $v->main_title,
                    "title" =>  $v->title,
                    "content"   =>  $v->description
                ],
                "skills"    =>  json_decode($v->skills, true),
                'url'    =>  $v->url
            ];
        }
        return $ctas;
    }

    public static function getNewsArea(): array
    {
        $posts = [];
        $posted = Model::getAllPosts(['online' => '1'], null, [], ['date', 2,
            'limit' =>  6
        ]);
        foreach($posted as $key => $post) {
            $posts['items'][$key] = [
                'image' =>  AppFunction::getImgRoute($post->breadcrump),
                'title' =>  $post->title,
                'date'  =>  AppFunction::stamp($post->date, 'd/m/Y à H:i'),
                'description'   =>  AppFunction::excerpt($post->excerpt),
                'url'   =>  $post->slug,
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
                'icon'  =>  $service->icon,
                'title' =>  $service->title,
                'description'   =>  $service->description
            ];
        }
        return $services;
    }

    public static function getNewsletter(): array
    {
        $newsletter = [];
        $news = Model::getNewsletterInfos(['id' => 1]);
        if (!empty($news))
            $newsletter = [
                'image' =>  AppFunction::getImgRoute($news->image),
                'title' =>  $news->title,
                'description'   =>  $news->description
            ];
        return $newsletter;
    }

    public static function getBreadcrumb(?string $url = null): array
    {
        $breadcrumbs = [];
        $breadcrumb = !is_null($url) ? Model::getBreadcrumb(self::section_content_condition($url)) : null;
        if (!is_null($breadcrumb)) {
            $breadcrumbs = [
                'header'    =>  $breadcrumb->header,
                'background'    =>  AppFunction::getImgRoute($breadcrumb->background)
            ];
        }
        return $breadcrumbs;
    }

    public static function getThirdBreadcrumb(?string $url = null): array
    {
        $third_url = [];
        $bread = !is_null($url) ? Model::getBreadcrumb(self::section_content_condition($url), 'breadcrumb') : null;
        if (!is_null($bread)) {
            if (!is_null($bread->breadcrumb)) {
                $third_url = json_decode($bread->breadcrumb, true);
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
                'header'    =>  $about->header,
                'introduction'  =>  $about->introduction,
                'excerpt'   =>  $about->excerpt,
                'url'  =>  $about->redirect_url,
                'image' =>  AppFunction::getImgRoute($about->image),
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
                'title'    =>  $expose->title,
                'description'  =>  $expose->description,
                'image' =>  AppFunction::getImgRoute($expose->image),
                'single-facts'  =>  json_decode($expose->single_facts, true)
            ];
        }
        return $exposes;
    }

    public static function getAccordion(?string $url = null): array
    {
        $accordions = [];
        $accordion = [];
        if(!is_null($url)) $accordion = Model::getAllAccordion(self::section_content_condition($url));
        if (!empty($accordion)) {
            foreach($accordion as $key => $acc) {
                $accordions['contents'][$key] = [
                    'title' =>  $acc->title,
                    'content' =>  $acc->content,
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
                'image' =>  AppFunction::getImgRoute($left_expose->image),
                'title' =>  $left_expose->title,
                'introduction'  =>  $left_expose->introduction,
                'url'  =>  $left_expose->redirect_url
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
                'description'   =>  $contact->description,
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
                $tabs[] = [$tb->tab_title => $tb->tab_content];
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
                'title' =>  $text->title,
                'description'   =>  $text->description,
                'content'   =>  $text->content
            ];
        }
        return $textes;
    }

    public static function getPages(): PaginationQuery
    {
        return Model::getPostPaginated(['date', 2])[1];
    }

    public static function getPosts(?string $url = null, bool $single_post = false): array
    {
        $posts = [];
        $posted = null;
        if (!$single_post) {
            $posted = Model::getPostPaginated(['date', 2])[0];
            foreach($posted as $key => $post) {
                $posts[$key] = [
                    'image' =>  AppFunction::getImgRoute($post->image),
                    'date'  =>  AppFunction::stamp($post->date, 'd/m/Y à H:i'),
                    'updated'   =>  !is_null($post->updated) ? AppFunction::stamp($post->updated, 'd/m/Y à H:i') : null,
                    'title' =>  $post->title,
                    'excerpt'   =>  AppFunction::excerpt($post->excerpt),
                    'slug'  =>  $post->slug,
                    'categories'    =>  null,
                    'online'    =>  (int)$post->online === 1 
                ];
            }
        }else {
            if(!is_null($url)) $posted = Model::getPost(['slug' => $url, 'slug_ang' => $url]);
            if(!is_null($posted)) {
                $excerpt = '<p><strong>' . $posted->excerpt . '</strong></p>';
                $posts = [
                    'image' =>  AppFunction::getImgRoute($posted->image),
                    'title' =>  $posted->title,
                    'excerpt'   =>  $excerpt,
                    'content'   =>  $posted->content
                ];
            }
        }
        return $posts;
    }

    public static function getReasons(string $url = null): array
    {
        $reasons = [];
        $reason = !is_null($url) ? Model::getReason(self::section_content_condition($url)) : null;
        if (!is_null($reason)) {
            $reasons = [
                'image' =>  is_null($reason->image) ? null : AppFunction::getImgRoute($reason->image),
                'title' =>  $reason->title,
                'description' =>  $reason->description,
                'items' =>  AppFunction::array_trim(json_decode($reason->items, true)),
            ];
        }
        return $reasons;
    }

    public static function getWork(string $url = null): array
    {
        $work_content = [];
        $works = !is_null($url) ? Model::getAllWork(self::section_content_condition($url)) : null;
        if (!is_null($works) && !empty($works)) {
            foreach($works as $work) {
                $work_content[] = [
                    'title' =>  $work->title,
                    'description'   =>  $work->description,
                    'icon' =>  $work->icon
                ];
            }
        }
        return $work_content;
    }

    public static function getTitle(string $url = null): array
    {
        $title = !is_null($url) ? Model::getTitle(self::section_content_condition($url), ['title', 'description']) : null;
        return [
            'title' =>  !is_null($title) && !is_null($title->title) ? $title->title: '',
            'description'   =>  !is_null($title) && !is_null($title->description) ? $title->description: ''
        ];
    }

    public static function getMetadata(?string $url = null): array
    {
        $metadata = [];
        $meta = !is_null($url) ? Model::getMetadata(self::section_content_condition($url)) : null;
        if(!is_null($meta)) {
            $metadata = [
                'title' =>  $meta->title,
                'description'   =>  $meta->description,
                'keyword'  =>  !is_null($meta->keywords) ? implode(', ', json_decode($meta->keywords, true)) : 'LCB Bank'
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
            $multipleFeatures['title'] = $t->title; 
            $multipleFeatures['description'] = $t->description; 
        }
        if (!is_null($m) && !empty($m)) {
            foreach($m as $v) {
                $multipleFeatures['features'][] = [
                    'title' =>  $v->title,
                    'description'   =>  $v->description,
                    'redirect_url'  =>  $v->redirect,
                    'image' =>  AppFunction::getImgRoute($v->image)
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
                    'title' =>  $v->title,
                    'content'   =>  $v->content,
                ];
            }
        }
        return $presentations;
    }

    private static function section_content_condition(string $url): array
    {
        return ['url' => trim($url), 'url_ang' => trim($url)];
    }
}