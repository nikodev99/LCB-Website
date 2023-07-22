<?php 

use Web\App\Core\Flash;
use Web\App\Core\Model;
use Web\App\Core\AppFunction;
use Web\App\Core\Route\Route;
use Web\App\Core\session\Post;

AppFunction::user_not_connected($router->url('admin.index'));

$post = new Post();

$flash = new Flash();
$timestamp = (new DateTime())->getTimestamp();
$url = $router->url('admin.menu');
$errors = [];

if (!$post->isEmpty()) {
    $item = trim($post->get('item'));
    $item_ang = trim($post->get('item-ang'));

    if (empty($item)) $errors['item'] = 'Le champ nom de l\'item ne peut pas être vide';
    if (empty($item_ang)) $errors['item-ang'] = 'Le champ nom de l\'item en anglais ne peut pas être vide';
    if(empty($post->get('menu-type'))) {
        $item_url = trim($post->get('item-url'));
        $item_url_ang = trim($post->get('item-url-ang'));
        if (empty($item_url)) $errors['item-url'] = 'Le champ url de l\'item ne peut pas être vide';
        if (empty($item_url_ang)) $errors['item-url-ang'] = 'Le champ url de l\'item en anglais ne peut pas être vide';
        if (empty($errors)) {
            $last_insert_id = Model::insertMenu([
                'name'  =>  $item,
                'name_ang'  =>  $item_ang,
                'item_url'  =>  $item_url,
                'item_url_ang'  =>  $item_url_ang,
                'type'  =>  null
            ], true);
            Model::insertPage([
                'url'   =>  $item_url,
                'url_ang'   =>  $item_url_ang,
                'content'   =>  null,
                'created'   =>  $timestamp,
                'updated'   =>  null,
                'online'    =>  0,
                'navbar_id' =>  $last_insert_id
            ]);
            $flash->success("Ajout effectué avec réussite");
            Route::redirect($url);
        }else {
            dump($errors);
            $flash->error($errors);
            Route::redirect($url);
        }
    }else {
        if ($post->get('menu-type') === 'dropdown') {
            $subItem = !$post->isEmpty('sub-item') && trim($post->get('sub-item'))[-1] === ';' ? substr(trim($post->get('sub-item')), 0, -1) : trim($post->get('sub-item'));
            $subItem_ang = !$post->isEmpty('sub-item-ang') && trim($post->get('sub-item-ang'))[-1] === ';'  ? substr(trim($post->get('sub-item-ang')), 0, -1) : trim($post->get('sub-item-ang'));
            $subItemURL = !$post->isEmpty('sub-item-url') && trim($post->get('sub-item-url'))[-1] === ';' ? substr(trim($post->get('sub-item-url')), 0, -1) : trim($post->get('sub-item-url'));
            $subItemURLang = !$post->isEmpty('sub-item-url-ang') && trim($post->get('sub-item-url-ang'))[-1] === ';' ? substr(trim($post->get('sub-item-url-ang')), 0, -1) : trim($post->get('sub-item-url-ang'));
            $subs = AppFunction::array_trim(explode(';', $subItem));
            $subs_ang = AppFunction::array_trim(explode(';', $subItem_ang));
            $subs_url = AppFunction::array_trim(explode(';', $subItemURL));
            $subs_url_ang = AppFunction::array_trim(explode(';', $subItemURLang));
            if (empty($subItem)) $errors['sub-item'] = 'Le champ sous-menu ne peut pas être vide';
            if (empty($subItem_ang)) $errors['sub-item-ang'] = 'Le champ sous-menu en anglais ne peut pas être vide';
            if (empty($subItemURL)) $errors['sub-item-url'] = 'Le champ URLs des sous-menu ne peut pas être vide';
            if (empty($subItemURLang)) $errors['sub-item-url-ang'] = 'Le champ URLs des sous-item en anglais ne peut pas être vide';
            if (count($subs) !== count($subs_url) | count($subs_ang) !== count($subs_url_ang)) {
                $errors[] = 'Erreur. Vérifier que le nombre de sous items soit egal au nombre des URLs';
            }
            if (empty($errors)) {
                $subItems = array_combine($subs_url, $subs);
                $subItems_ang = array_combine($subs_url_ang, $subs_ang);
                $last_insert_id = Model::insertMenu([
                    'name'  =>  $item,
                    'name_ang'  =>  $item_ang,
                    'submenu_items' =>  json_encode($subItems),
                    'submenu_items_ang' =>  json_encode($subItems_ang),
                    'type'  =>  'dropdown'
                ], true);
                for($i = 0; $i < count($subs_url); $i++) {
                    Model::insertPage([
                        'url'   =>  $subs_url[$i],
                        'url_ang'   =>  $subs_url_ang[$i],
                        'content'   =>  null,
                        'created'   =>  $timestamp,
                        'updated'   =>  null,
                        'online'    =>  0,
                        'navbar_id' =>  $last_insert_id
                    ]);
                }
                $flash->success("Ajout effectué avec réussite");
                Route::redirect($url);
            }else {
                $flash->error($errors);
                Route::redirect($url);
            }
        }elseif($post->get('menu-type') === 'megamenu') {
            $items = [];
            $items_ang = [];
            $submenu = !$post->isEmpty('submenu') && trim($post->get('submenu'))[-1] === ';' ? substr(trim($post->get('submenu')), 0, -1) : trim($post->get('submenu'));
            $submenu_item = !$post->isEmpty('submenu-item') && trim($post->get('submenu-item'))[-1] === ';' ? substr(trim($post->get('submenu-item')), 0, -1) : trim($post->get('submenu-item'));
            $submenu_item_URL = !$post->isEmpty('submenu-item-url') && trim($post->get('submenu-item-url'))[-1] === ';' ? substr(trim($post->get('submenu-item-url')), 0, -1) : trim($post->get('submenu-item-url'));
            $submenu_ang = !$post->isEmpty('submenu-ang') && trim($post->get('submenu-ang'))[-1] === ';' ? substr(trim($post->get('submenu-ang')), 0, -1) : trim($post->get('submenu-ang'));
            $submenu_item_ang = !$post->isEmpty('submenu-item-ang') && trim($post->get('submenu-item-ang'))[-1] === ';' ? substr(trim($post->get('submenu-item-ang')), 0, -1): trim($post->get('submenu-item-ang'));
            $submenu_item_URL_ang = !$post->isEmpty('submenu-item-url-ang') && trim($post->get('submenu-item-url-ang'))[-1] === ';' ? substr(trim($post->get('submenu-item-url-ang')), 0, -1) : trim($post->get('submenu-item-url-ang'));
            $submenus = AppFunction::array_trim(explode(';', $submenu));
            $submenu_items = AppFunction::array_trim(explode('|', $submenu_item));
            $submenu_item_urls = AppFunction::array_trim(explode('|', $submenu_item_URL));
            $submenus_ang = AppFunction::array_trim(explode(';', $submenu_ang));
            $submenu_items_ang = AppFunction::array_trim(explode('|', $submenu_item_ang));
            $submenu_item_urls_ang = AppFunction::array_trim(explode('|', $submenu_item_URL_ang));
            if (empty($submenu)) $errors['submenu'] = 'Le champ sous menu ne peut pas être vide';
            if (empty($submenu_item)) $errors['submenu-item'] = 'Le champ sous-menu items ne peut pas être vide';
            if (empty($submenu_item_URL)) $errors['submenu-item-url'] = 'Le champ URLs des sous-menu item ne peut pas être vide';
            if (empty($submenu_ang)) $errors['submenu-ang'] = 'Le champ sous menu en anglais ne peut pas être vide';
            if (empty($submenu_item_ang)) $errors['submenu-item-ang'] = 'Le champ sous-menu items en anglais ne peut pas être vide';
            if (empty($submenu_item_URL_ang)) $errors['submenu-item-url-ang'] = 'Le champ URLs des sous-menu items en anglais ne peut pas être vide';
            for($i = 0; $i < count($submenu_items); $i++) {
                if (count($submenu_items) == count($submenu_item_urls)) {
                    $keys = AppFunction::array_trim(explode(';', $submenu_item_urls[$i]));
                    $values = AppFunction::array_trim(explode(';', $submenu_items[$i]));
                    array_unshift($keys, '#');
                    array_unshift($values, $submenus[$i]);
                    count($keys) !== count($values) ? $errors[] = 'Erreur. Vérifier que le nombre de sous items soit egal au nombre des URLs.'
                    :$Items[$submenus[$i]] = array_combine($keys, $values);
                }else {
                    $errors[] = 'Erreur. Vérifier que le nombre de sous items soit egal au nombre des URLs.';
                }
                if (count($submenu_items_ang) === count($submenu_item_urls_ang)) {
                    $keyses = AppFunction::array_trim(explode(';', $submenu_item_urls_ang[$i]));
                    $valueses = AppFunction::array_trim(explode(';', $submenu_items_ang[$i]));
                    array_unshift($keyses, '#');
                    array_unshift($valueses, $submenus_ang[$i]);
                    count($keyses) !== count($valueses) ? $errors[] = 'Erreur. Vérifier que le nombre de sous items en anglais soit egal au nombre des URLs en anglais.'
                    :$Items_ang[$submenus_ang[$i]] = array_combine($keyses, $valueses);
                }else {
                    $errors[] = 'Erreur. Vérifier que le nombre de sous items en anglais soit egal au nombre des URLs en anglais.';
                }
            }
            if (empty($errors)) {
                $pages = [];
                $submenus = json_encode(['submenu' => $submenus]);
                $submenus_ang = json_encode(['submenu' => $submenus_ang]);
                $items = json_encode($items);
                $items_ang = json_encode($items_ang);
                for($i = 0; $i < count($submenu_item_urls); $i++) {
                    $urls = AppFunction::array_trim(explode(';', $submenu_item_urls[$i]));
                    unset($urls['#']);
                    $urls_ang = AppFunction::array_trim(explode(';', $submenu_item_urls_ang[$i]));
                    unset($urls_ang['#']);
                    $pages[] = array_combine($urls_ang, $urls);
                }
                $last_insert_id = Model::insertMenu([
                    'name'  =>  $item,
                    'name_ang'  =>  $item_ang,
                    'submenu'   =>  $submenus,
                    'submenu_ang'   =>  $submenus_ang,
                    'submenu_items' =>  $items,
                    'submenu_items_ang' =>  $items_ang,
                    'type'  =>  'megamenu'
                ], true);
                foreach($pages as $page) {
                    foreach($page as $url_ang => $url_fr)
                        Model::insertPage([
                            'url'   =>  $url_fr,
                            'url_ang'   =>  $url_ang,
                            'content'   =>  null,
                            'created'   =>  $timestamp,
                            'updated'   =>  null,
                            'online'    =>  0,
                            'navbar_id' =>  $last_insert_id
                        ]);
                }
                $flash->success("Ajout effectué avec réussite");
                Route::redirect($url);
            }else {
                $flash->error($errors);
                Route::redirect($url);
            } 
        }
    }
}
