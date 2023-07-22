<?php

use Web\App\Core\Model;
use Web\App\Core\AppFunction;
use Web\App\Core\session\Post;

AppFunction::user_not_connected($router->url('admin.index'));
$post = new Post();

$redirection_url = $router->url('admin.page.constructor', ['slug' => $params['slug']]);

$condition = ['url' => trim($params['slug']), 'url_ang' => trim($params['slug'])];

$page = [];

if (!$post->isEmpty()) {

    $page = !is_null($post->get('page_content')) ? $post->get('page_content') : [];

    if (!empty($page) && is_array($page)) {
        $url = Model::getPage(['url' => trim($params['slug'])]);
        $lien = '<a href="'. $router->url('page.index', ['slug' => trim($params['slug'])]) .'" style="text-decoration:underline; color:red">suivant ce lien</a>';

        if(!$post->isEmpty('description') && !$post->isEmpty('description_ang')) {
            $meta = Model::getMetadata($condition);
            if(is_null($meta)) {
                Model::insertMetadata([
                    'title' =>  !$post->isEmpty('title') ? $post->get('title') : 'LCB Bank Website | BMCE Group',
                    'title_ang' =>  !$post->isEmpty('title_ang') ? $post->get('title_ang') : 'LCB Bank Website | BMCE Group',
                    'description'   =>  $post->get('description'),
                    'description_ang'   =>  $post->get('description_ang'),
                    'url'       =>  $url->url,
                    'url_ang'   =>  $url->url_ang,
                    'url_id'    =>  $url->id
                ]);
            }
        }else {
            AppFunction::errorMessage('Les champs description et/ou description en anglais ne peuvent pas être vide', $redirection_url);
        }

        if (in_array('banniere', $page)) {
            $banniere = Model::getBreadcrumb($condition);
            if (is_null($banniere))
                Model::insertBreadcrumb([
                    'header'    =>  'LCB Bank',
                    'header_ang'=>  'LCB Bank',
                    'background' =>  '13.jpg',
                    'url'       =>  $url->url,
                    'url_ang'   =>  $url->url_ang,
                    'url_id'    =>  $url->id
                ]);
        }
        if (in_array('about', $page)) {
            $about = Model::getAbout($condition);
            if (is_null($about)) {
                Model::insertAbout([
                    'header'    =>  'LCB Bank',
                    'header_ang'    =>  'LCB Bank',
                    'introduction'  =>  'In vitae nisi aliquam, scelerisque leo a, volutpat sem. Viva mus rutrum dui fermentum eros hendrerit, id lobortis leo volutpat.',
                    'introduction_ang'  =>  'In vitae nisi aliquam, scelerisque leo a, volutpat sem. Viva mus rutrum dui fermentum eros hendrerit, id lobortis leo volutpat.',
                    'excerpt'   =>  'Sed ut iaculis elit, quis varius mauris. Integer ut ultricies orci, lobortis egestas sem. Duis non volutpat arcu. Sed ut iaculis elit, quis varius mauris. Integer ut ultricies orci, lobortis egestas sem. Duis non volutpat arcu. Pellentesque lobortis neque non sem dapibus, non rutrum dolor pretium.',
                    'excerpt_ang'   =>  'Sed ut iaculis elit, quis varius mauris. Integer ut ultricies orci, lobortis egestas sem. Duis non volutpat arcu. Sed ut iaculis elit, quis varius mauris. Integer ut ultricies orci, lobortis egestas sem. Duis non volutpat arcu. Pellentesque lobortis neque non sem dapibus, non rutrum dolor pretium.',
                    'content'   =>  '',
                    'content_ang'   =>  '',
                    'redirect_url'  =>  '',
                    'redirect_url_ang'  =>  '',
                    'image' =>  '14.jpg',
                    'url'       =>  $url->url,
                    'url_ang'   =>  $url->url_ang,
                    'url_id'    =>  $url->id
                ]);
                Model::insertBreadcrumb([
                    'header'    =>  'LCB Bank',
                    'header_ang'=>  'LCB Bank',
                    'background' =>  '13.jpg',
                    'url'       =>  $url->url,
                    'url_ang'   =>  $url->url_ang,
                    'url_id'    =>  $url->id
                ]);
            }   
        }
        if (in_array('expose', $page)) {
            $expose = Model::getExpose($condition);
            if(is_null($expose)) {
                Model::insertExpose([
                    'title' =>  'LCB Bank',
                    'title_ang' =>  'LCB Bank',
                    'description'   =>  'Morbi ut dapibus dui. Sed ut iaculis elit, quis varius mauris. Integer ut ultricies orci, lobortis egestas sem. Duis non volutpat arcu, eu mollis tellus. Sed finibus aliquam neque sit amet sod ales. Maecenas sed magna tempor, efficitur maur is in, sollicitudin augue.',
                    'description_ang'   =>  'Morbi ut dapibus dui. Sed ut iaculis elit, quis varius mauris. Integer ut ultricies orci, lobortis egestas sem. Duis non volutpat arcu, eu mollis tellus. Sed finibus aliquam neque sit amet sod ales. Maecenas sed magna tempor, efficitur maur is in, sollicitudin augue.',
                    'image'   =>  '19.jpg',
                    'single_facts'  =>  json_encode([
                        ['icon' =>  '', 'counter' => '', 'label' => ''],
                        ['icon' =>  '', 'counter' => '', 'label' => ''],
                        ['icon' =>  '', 'counter' => '', 'label' => '']
                    ], JSON_THROW_ON_ERROR),
                    'url'       =>  $url->url,
                    'url_ang'   =>  $url->url_ang,
                    'url_id'    =>  $url->id
                ]);
            }
        }   
        if (in_array('accordion', $page)) {
            $acc = Model::getAccordion($condition);
            if (is_null($acc)) {
                $default_accorddion = [
                    0   =>  [
                        'title' =>  'LCB Bank',
                        'title_ang' =>  'LCB Bank',
                        'content'   =>  'Lorem ipsum dolor sit amet consectetur adipisicing elit. Velit aliquid error natus expedita quibusdam maiores veniam nostrum ipsam magni? Inventore culpa dicta, porro ipsam assumenda placeat architecto obcaecati blanditiis optio.',
                        'content_ang'   =>  'Lorem ipsum dolor sit amet consectetur adipisicing elit. Velit aliquid error natus expedita quibusdam maiores veniam nostrum ipsam magni? Inventore culpa dicta, porro ipsam assumenda placeat architecto obcaecati blanditiis optio.',
                        'control'   =>  'bank_1',
                        'url'       =>  $url->url,
                        'url_ang'   =>  $url->url_ang,
                        'url_id'    =>  $url->id
                    ],
                    1   =>  [
                        'title' =>  'LCB Bank',
                        'title_ang' =>  'LCB Bank',
                        'content'   =>  'Lorem ipsum dolor sit amet consectetur adipisicing elit. Velit aliquid error natus expedita quibusdam maiores veniam nostrum ipsam magni? Inventore culpa dicta, porro ipsam assumenda placeat architecto obcaecati blanditiis optio.',
                        'content_ang'   =>  'Lorem ipsum dolor sit amet consectetur adipisicing elit. Velit aliquid error natus expedita quibusdam maiores veniam nostrum ipsam magni? Inventore culpa dicta, porro ipsam assumenda placeat architecto obcaecati blanditiis optio.',
                        'control'   =>  'bank_2',
                        'url'       =>  $url->url,
                        'url_ang'   =>  $url->url_ang,
                        'url_id'    =>  $url->id
                    ],
                    2   =>  [
                        'title' =>  'LCB Bank',
                        'title_ang' =>  'LCB Bank',
                        'content'   =>  'Lorem ipsum dolor sit amet consectetur adipisicing elit. Velit aliquid error natus expedita quibusdam maiores veniam nostrum ipsam magni? Inventore culpa dicta, porro ipsam assumenda placeat architecto obcaecati blanditiis optio.',
                        'content_ang'   =>  'Lorem ipsum dolor sit amet consectetur adipisicing elit. Velit aliquid error natus expedita quibusdam maiores veniam nostrum ipsam magni? Inventore culpa dicta, porro ipsam assumenda placeat architecto obcaecati blanditiis optio.',
                        'control'   =>  'bank_3',
                        'url'       =>  $url->url,
                        'url_ang'   =>  $url->url_ang,
                        'url_id'    =>  $url->id
                    ]
                ];
                foreach($default_accorddion as $accordion):
                    Model::insertAccordion($accordion);
                endforeach;
            }
        }   
        if (in_array('left_expose', $page)) {
            $left_expose = Model::getLeftExpose($condition);
            if (is_null($left_expose)):
                Model::insertLeftExpose([
                    'image' =>  '21.jpg',
                    'title' =>  'LCB Bank',
                    'title_ang' =>  'LCB Bank',
                    'introduction'  =>  'Morbi ut dapibus dui. Sed ut iaculis elit, quis varius mauris. Integer ut ultricies orci, lobortis egestas sem. Duis non volutpat arcu, eu mollis tellus. Sed finibus aliquam neque sit amet sod ales. Maecenas sed magna tempor, efficitur maur is in, sollicitudin augue. Praesent pretium finibus quam.',
                    'introduction_ang'  =>  'Morbi ut dapibus dui. Sed ut iaculis elit, quis varius mauris. Integer ut ultricies orci, lobortis egestas sem. Duis non volutpat arcu, eu mollis tellus. Sed finibus aliquam neque sit amet sod ales. Maecenas sed magna tempor, efficitur maur is in, sollicitudin augue. Praesent pretium finibus quam.',
                    'redirect_url'  =>  '',
                    'redirect_url_ang'  =>  '',
                    'url'       =>  $url->url,
                    'url_ang'   =>  $url->url_ang,
                    'url_id'    =>  $url->id
                ]);
            endif;
        }
        if (in_array('tabs', $page)) {
            $tabs = Model::getTabs($condition);
            if (is_null($tabs)) {
                $tabs = [
                    0   =>  [
                        'tab_title' =>  'LCB Bank',
                        'tab_title_ang' =>  'LCB Bank',
                        'tab_content' =>  'Morbi ut dapibus dui. Sed ut iaculis elit, quis varius mauris. Integer ut ultricies orci, lobortis egestas sem. Duis non volutpat arcu, eu mollis tellus. Sed finibus aliquam neque sit amet sod ales. Maecenas sed magna tempor, efficitur maur is in, sollicitudin augue. Praesent pretium finibus quam.',
                        'tab_content_ang' =>  'Morbi ut dapibus dui. Sed ut iaculis elit, quis varius mauris. Integer ut ultricies orci, lobortis egestas sem. Duis non volutpat arcu, eu mollis tellus. Sed finibus aliquam neque sit amet sod ales. Maecenas sed magna tempor, efficitur maur is in, sollicitudin augue. Praesent pretium finibus quam.',
                        'url'       =>  $url->url,
                        'url_ang'   =>  $url->url_ang,
                        'url_id'    =>  $url->id
                    ],
                    1   =>  [
                        'tab_title' =>  'LCB Bank',
                        'tab_title_ang' =>  'LCB Bank',
                        'tab_content' =>  'Morbi ut dapibus dui. Sed ut iaculis elit, quis varius mauris. Integer ut ultricies orci, lobortis egestas sem. Duis non volutpat arcu, eu mollis tellus. Sed finibus aliquam neque sit amet sod ales. Maecenas sed magna tempor, efficitur maur is in, sollicitudin augue. Praesent pretium finibus quam.',
                        'tab_content_ang' =>  'Morbi ut dapibus dui. Sed ut iaculis elit, quis varius mauris. Integer ut ultricies orci, lobortis egestas sem. Duis non volutpat arcu, eu mollis tellus. Sed finibus aliquam neque sit amet sod ales. Maecenas sed magna tempor, efficitur maur is in, sollicitudin augue. Praesent pretium finibus quam.',
                        'url'       =>  $url->url,
                        'url_ang'   =>  $url->url_ang,
                        'url_id'    =>  $url->id
                    ],
                    2   =>  [
                        'tab_title' =>  'LCB Bank',
                        'tab_title_ang' =>  'LCB Bank',
                        'tab_content' =>  'Morbi ut dapibus dui. Sed ut iaculis elit, quis varius mauris. Integer ut ultricies orci, lobortis egestas sem. Duis non volutpat arcu, eu mollis tellus. Sed finibus aliquam neque sit amet sod ales. Maecenas sed magna tempor, efficitur maur is in, sollicitudin augue. Praesent pretium finibus quam.',
                        'tab_content_ang' =>  'Morbi ut dapibus dui. Sed ut iaculis elit, quis varius mauris. Integer ut ultricies orci, lobortis egestas sem. Duis non volutpat arcu, eu mollis tellus. Sed finibus aliquam neque sit amet sod ales. Maecenas sed magna tempor, efficitur maur is in, sollicitudin augue. Praesent pretium finibus quam.',
                        'url'       =>  $url->url,
                        'url_ang'   =>  $url->url_ang,
                        'url_id'    =>  $url->id
                    ]
                ];
                foreach($tabs as $tab):
                    Model::insertTabs($tab);
                endforeach;
            }
        }
        if (in_array('text', $page)) {
            $textes = Model::getText($condition);
            if (is_null($textes)):
                Model::insertNewText([
                    'title' =>  'Un Titre',
                    'title_ang' => 'A Title',
                    'description'   =>  null,
                    'description_ang'   => null,
                    'content'   => null,
                    'content_ang'   => null,
                    'url'       =>  $url->url,
                    'url_ang'   =>  $url->url_ang,
                    'url_id'    =>  $url->id
                ]);
            endif;
        }
        if(in_array('reason', $page)) {
            $r = Model::getReason($condition);
            if (is_null($r)) {
                Model::insertReason([
                    'image' =>  '14.jpg',
                    'title' =>  'un titre',
                    'title_ang' => 'A title',
                    'description'   =>  'Une description',
                    'description_ang'   =>  'A description',
                    'items'  =>  json_encode(["première item", "deuxieme item"]),
                    'items_ang' =>  json_encode(["the firts item", "the second item"]),
                    'url'       =>  $url->url,
                    'url_ang'   =>  $url->url_ang,
                    'url_id'    =>  $url->id
                ]);
            }
        }
        if(in_array('work', $page)) {
            $w = Model::getWork($condition);
            if (is_null($w)) {
                Model::insertWork([
                    'title' =>  'un titre',
                    'title_ang' => 'A title',
                    'description'   =>  'Une description',
                    'description_ang'   =>  'A description',
                    'icon'  =>  null,
                    'url'       =>  $url->url,
                    'url_ang'   =>  $url->url_ang,
                    'url_id'    =>  $url->id
                ]);
            }
        }
        if(in_array('multiple-feature', $page)) {
            $m = Model::getMultipleFeature($condition);
            if (is_null($m)) {
                Model::insertMultipleFeature([
                    'title' =>  'un titre',
                    'title_ang' => 'A title',
                    'description'   =>  'Une description',
                    'description_ang'   =>  'A description',
                    'image' =>  'bg-img600ec0f5c633a0ldc161157963704783690.jpg',
                    'redirect'  =>  null,
                    'redirect_ang'       =>  null,
                    'url'       =>  $url->url,
                    'url_ang'   =>  $url->url_ang,
                    'url_id'    =>  $url->id
                ]);
            }
        }
        if(in_array('presentation', $page)) {
            $p = Model::getPresentation($condition);
            if (is_null($p)) {
                Model::insertPresentation([
                    'image' =>  'businessman.svg',
                    'title' =>  'un titre',
                    'title_ang' => 'A title',
                    'content'   =>  'Une description',
                    'content_ang'   =>  'A description',
                    'url'       =>  $url->url,
                    'url_ang'   =>  $url->url_ang,
                    'url_id'    =>  $url->id
                ]);
            }
        }
        Model::updatePage(['url' => trim($params['slug'])], [
            'content'   =>  json_encode($page),
            'updated'   =>  time()
        ]);
        AppFunction::successMessage("La nouvelle page a bien été enregistré. Pensez à la modifier en $lien et de mettre la page en ligne au terme de la modification", $redirection_url);
    }else {
        AppFunction::errorMessage('Nouvelle page non enregistré. Erreur rencontrer lors de la création de la page', $redirection_url);
    }
}
