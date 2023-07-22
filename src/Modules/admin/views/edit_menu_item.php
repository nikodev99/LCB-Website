<?php

use Web\App\Core\Flash;
use Web\App\Core\Model;
use Web\App\Core\AppFunction;
use Web\App\Core\Route\Route;
use Web\App\Core\session\Post;

AppFunction::user_not_connected($router->url('admin.index'));

$post = new Post();

$flash = new Flash();

$conditions = [
    'id'  =>  $params['id'],
];
$url = $router->url('admin.menu.manage', $params);
$timestamp = (new DateTime())->getTimestamp();

$errors = [];
$erreur = null;
$success = null;

if (!$post->isEmpty()) {
    $menu = Model::getMenuItem($conditions);
    $page = Model::getAllPages([
        'navbar_id' => $menu->id
    ], null, ['id', 'url', 'url_ang', 'navbar_id']);
    $name = trim($post->get('item'));
    $item_ang = trim($post->get('item-ang'));
    $last_insert_id = $menu->id;

    if (empty($name)) $errors['item'] = 'Le champ nom de l\'item ne peut pas être vide';
    if (empty($item_ang)) $errors['item-ang'] = 'Le champ nom de l\'item en anglais ne peut pas être vide';

    if(is_null($menu->type)) {
        $item_url = trim($post->get('item-url'));
        $item_url_ang = trim($post->get('item-url-ang'));
        if (empty($item_url)) $errors['item-url'] = 'Le champ url de l\'item ne peut pas être vide';
        if (empty($item_url_ang)) $errors['item-url-ang'] = 'Le champ url de l\'item en anglais ne peut pas être vide';
        if (empty($errors)) {
            $posted = [$name, $item_ang, $item_url, $item_url_ang];
            $present = [$menu->name, $menu->name_ang, $menu->item_url, $menu->item_url_ang];
            $change = AppFunction::array_compare($posted, $present);
            if (!$change) {
                Model::updateMenu($conditions, [
                'name'  =>  $name,
                'name_ang'  =>  $item_ang,
                'item_url'  =>  $item_url,
                'item_url_ang'  =>  $item_url_ang,
                'type'  =>  null
                ]);
                Model::updatePage(['navbar_id' => $last_insert_id], [
                    'url'   =>  $item_url,
                    'url_ang'   =>  $item_url_ang,
                    'updated'   =>  $timestamp,
                    'navbar_id' =>  $last_insert_id
                ]);
                $flash->success("Modification effectuée avec réussite");
                Route::redirect($url);
            }else {
                $flash->info('Aucune modification n\'a été apportée à l\'item "'. $menu->name .'" dans le menu');
                Route::redirect($url);
            }
        }else {
            $flash->error($errors);
            Route::redirect($url);
        }
    }else {
        if ($menu->type === 'dropdown') {
            $subItem = !$post->isEmpty('sub-item') && trim($post->get('sub-item'))[-1] === ';' ? substr(trim($post->get('sub-item')), 0, -1) : trim($post->get('sub-item'));
            $subItem_ang = !$post->isEmpty('sub-item-ang') && trim($post->get('sub-item-ang'))[-1] === ';'  ? substr(trim($post->get('sub-item-ang')), 0, -1) : trim($post->get('sub-item-ang'));
            $subItemURL = !$post->isEmpty('sub-item-url') && trim($post->get('sub-item-url'))[-1] === ';' ? substr(trim($post->get('sub-item-url')), 0, -1) : trim($post->get('sub-item-url'));
            $subItemURLang = !$post->isEmpty('sub-item-url-ang') && trim($post->get('sub-item-url-ang'))[-1] === ';' ? substr(trim($post->get('sub-item-url-ang')), 0, -1) : trim($post->get('sub-item-url-ang'));
            $subs = explode(';', $subItem);
            $subs_ang = explode(';', $subItem_ang);
            $subs_url = explode(';', $subItemURL);
            $subs_url_ang = explode(';', $subItemURLang);
            
            if (empty($subItem)) $errors['sub-item'] = 'Le champ sous-menu ne peut pas être vide';
            if (empty($subItem_ang)) $errors['sub-item-ang'] = 'Le champ sous-menu en anglais ne peut pas être vide';
            if (empty($subItemURL)) $errors['sub-item-url'] = 'Le champ URLs des sous-menu ne peut pas être vide';
            if (empty($subItemURLang)) $errors['sub-item-url-ang'] = 'Le champ URLs des sous-item en anglais ne peut pas être vide';
            if (count($subs) !== count($subs_url) | count($subs_ang) !== count($subs_url_ang)) {
                $errors[] = 'Erreur. Vérifier que le nombre de sous items soit egal au nombre des URLs';
            }
            if (empty($errors) && is_null($erreur)) {
                $subItems = array_combine($subs_url, $subs);
                $subItems_ang = array_combine($subs_url_ang, $subs_ang);
                $subItems = json_encode($subItems);
                $subItems_ang = json_encode($subItems_ang);
                $posted = [$name, $item_ang, $subItems, $subItems_ang];
                $present = [$menu->name, $menu->name_ang, $menu->submenu_items, $menu->submenu_items_ang];
                $change = AppFunction::array_compare($posted, $present);
                if (!$change) {
                    Model::updateMenu($conditions, [
                        'name'  =>  $name,
                        'name_ang'  =>  $item_ang,
                        'submenu_items' =>  $subItems,
                        'submenu_items_ang' =>  $subItems_ang,
                        'type'  =>  'dropdown'
                    ]);
                    if (count($page) === count($subs_url)) {
                        for($i = 0; $i < count($page); $i++) {
                            if ($subs_url[$i] !== $page[$i]->url | $subs_url_ang[$i] !== $page[$i]->url_ang)
                                Model::updatePage(['id' => $page[$i]->id, 'navbar_id' => $last_insert_id], [
                                    'url'   =>  $subs_url[$i],
                                    'url_ang'   =>  $subs_url_ang[$i],
                                    'updated'   =>  $timestamp,
                                    'navbar_id' =>  $last_insert_id
                                ]);
                        }
                    }else {
                        if (count($page) < count($subs_url)) {
                            for($i = 0; $i < count($subs_url); $i++) {
                                if (!array_key_exists($i, $page) && !in_array($subs_url[$i], $page)) {
                                    Model::insertPage([
                                        'url'   =>  $subs_url[$i],
                                        'url_ang'   =>  $subs_url_ang[$i],
                                        'content'   =>  null,
                                        'created'   =>  $timestamp,
                                        'updated'   =>  null,
                                        'online'    =>  0,
                                        'navbar_id' =>  $last_insert_id
                                    ]);
                                }else {
                                    if ($subs_url[$i] !== $page[$i]->url | $subs_url_ang[$i] !== $page[$i]->url_ang)
                                        Model::updatePage(['id' => $page[$i]->id, 'navbar_id' => $last_insert_id], [
                                            'url'   =>  $subs_url[$i],
                                            'url_ang'   =>  $subs_url_ang[$i],
                                            'updated'   =>  $timestamp,
                                            'navbar_id' =>  $last_insert_id
                                        ]);
                                }
                            }
                        }else {
                            $present_in_db = [];
                            for($i = 0; $i < count($page); $i++) {
                                $present_in_db[] = $page[$i]->url;
                                if (array_key_exists($i, $subs_url))
                                    if ($subs_url[$i] !== $page[$i]->url | $subs_url_ang[$i] !== $page[$i]->url_ang)
                                        Model::updatePage(['id' => $page[$i]->id, 'navbar_id' => $last_insert_id], [
                                            'url'   =>  $subs_url[$i],
                                            'url_ang'   =>  $subs_url_ang[$i],
                                            'updated'   =>  $timestamp,
                                            'navbar_id' =>  $last_insert_id
                                        ]);
                            }
                            $diff = array_diff($present_in_db, $subs_url);
                            if(!empty($diff)) {
                                foreach($diff as $value) {
                                    Model::deletePage(['url' => $value]);
                                }
                            }
                        }
                    }
                    
                   $flash->success("Modification effectuée avec réussite");
                    Route::redirect($url);
                }else {
                    $flash->info('Aucune modification n\'a été apportée à l\'item "'. $menu->name .'" dans le menu');
                    Route::redirect($url);
                }
            }else {
                $flash->error($errors);
                Route::redirect($url);
            }
        }elseif($menu->type === 'megamenu') {
            $allItems = [];
            $allItems_ang = [];
            $submenu = !$post->isEmpty('submenu') && trim($post->get('submenu'))[-1] === ';' ? substr(trim($post->get('submenu')), 0, -1) : trim($post->get('submenu'));
            $submenu_item = !$post->isEmpty('submenu-item') && trim($post->get('submenu-item'))[-1] === ';' ? substr(trim($post->get('submenu-item')), 0, -1) : trim($post->get('submenu-item'));
            $submenu_item_URL = !$post->isEmpty('submenu-item-url') && trim($post->get('submenu-item-url'))[-1] === ';' ? substr(trim($post->get('submenu-item-url')), 0, -1) : trim($post->get('submenu-item-url'));
            $submenu_ang = !$post->isEmpty('submenu-ang') && trim($post->get('submenu-ang'))[-1] === ';' ? substr(trim($post->get('submenu-ang')), 0, -1) : trim($post->get('submenu-ang'));
            $submenu_item_ang = !$post->isEmpty('submenu-item-ang') && trim($post->get('submenu-item-ang'))[-1] === ';' ? substr(trim($post->get('submenu-item-ang')), 0, -1): trim($post->get('submenu-item-ang'));
            $submenu_item_URL_ang = !$post->isEmpty('submenu-item-url-ang') && trim($post->get('submenu-item-url-ang'))[-1] === ';' ? substr(trim($post->get('submenu-item-url-ang')), 0, -1) : trim($post->get('submenu-item-url-ang'));
            $submenus = explode(';', $submenu);
            $allSubmenu_items = explode('|', $submenu_item);
            $submenu_item_urls = explode('|', $submenu_item_URL);
            $submenus_ang = explode(';', $submenu_ang);
            $allSubmenu_items_ang = explode('|', $submenu_item_ang);
            $submenu_item_urls_ang = explode('|', $submenu_item_URL_ang);
            if (empty($submenu)) $errors['submenu'] = 'Le champ sous menu ne peut pas être vide';
            if (empty($submenu_item)) $errors['submenu-item'] = 'Le champ sous-menu items ne peut pas être vide';
            if (empty($submenu_item_URL)) $errors['submenu-item-url'] = 'Le champ URLs des sous-menu item ne peut pas être vide';
            if (empty($submenu_ang)) $errors['submenu-ang'] = 'Le champ sous menu en anglais ne peut pas être vide';
            if (empty($submenu_item_ang)) $errors['submenu-item-ang'] = 'Le champ sous-menu items en anglais ne peut pas être vide';
            if (empty($submenu_item_URL_ang)) $errors['submenu-item-url-ang'] = 'Le champ URLs des sous-menu items en anglais ne peut pas être vide';
            for($i = 0; $i < count($allSubmenu_items); $i++) {
                if (count($allSubmenu_items) == count($submenu_item_urls)) {
                    $keys = explode(';', $submenu_item_urls[$i]);
                    $values = explode(';', $allSubmenu_items[$i]);
                    array_unshift($keys, '#');
                    array_unshift($values, $submenus[$i]);
                    count($keys) !== count($values) ? $errors[] = 'Erreur. Vérifier que le nombre de sous items soit egal au nombre des URLs'
                    :$allItems[$submenus[$i]] = array_combine($keys, $values);
                }else {
                    $errors[] = 'Erreur. Vérifier que le nombre de sous items soit egal au nombre des URLs';
                }
                if (count($allSubmenu_items_ang) === count($submenu_item_urls_ang)) {
                    $keyses = explode(';', $submenu_item_urls_ang[$i]);
                    $valueses = explode(';', $allSubmenu_items_ang[$i]);
                    array_unshift($keyses, '#');
                    array_unshift($valueses, $submenus_ang[$i]);
                    count($keyses) !== count($valueses) ? $errors[] = 'Erreur. Vérifier que le nombre de sous items en anglais soit egal au nombre des URLs en anglais'
                    :$allItems_ang[$submenus_ang[$i]] = array_combine($keyses, $valueses);
                }else {
                    $errors[] = 'Erreur. Vérifier que le nombre de sous items en anglais soit egal au nombre des URLs en anglais';
                }
            }
            if (empty($errors && is_null($erreur))) {
                $submenus = json_encode(['submenu' => $submenus]);
                $submenus_ang = json_encode(['submenu' => $submenus_ang]);
                $allItems = json_encode($allItems);
                $allItems_ang = json_encode($allItems_ang);
                $posted = [$name, $item_ang, $submenus, $submenus_ang, $allItems, $allItems_ang];
                $present = [$menu->name, $menu->name_ang, $menu->submenu, $menu->submenu_ang, $menu->submenu_items, $menu->submenu_items_ang];
                $change = AppFunction::array_compare($posted, $present);
                if (!$change) {
                    $pages = [];
                    $liens = [];
                    $liens_ang = [];
                    for($i = 0; $i < count($submenu_item_urls); $i++) {
                        $urls = explode(';', $submenu_item_urls[$i]);
                        unset($urls['#']);
                        $urls_ang = explode(';', $submenu_item_urls_ang[$i]);
                        unset($urls_ang['#']);
                        count($urls_ang) !== count($urls) ? $errors[] = 'Erreur. Vérifier que le nombre des URLs en français soit egal au nombre des URLs en anglais'
                        :$pages[] = array_combine($urls_ang, $urls);
                    }
                    foreach($pages as $page_urls) {
                        foreach($page_urls as $page_url_ang => $page_url) {
                            $liens[] = $page_url;
                            $liens_ang[] = $page_url_ang;
                        }
                    }
                    
                    Model::updateMenu($conditions, [
                        'name'  =>  $name,
                        'name_ang'  =>  $item_ang,
                        'submenu'   =>  $submenus,
                        'submenu_ang'   =>  $submenus_ang,
                        'submenu_items' =>  $allItems,
                        'submenu_items_ang' =>  $allItems_ang,
                        'type'  =>  'megamenu'
                    ]);

                    if (count($page) === count($liens)) {
                        for($i = 0; $i < count($page); $i++) {
                            if ($liens[$i] !== $page[$i]->url | $liens_ang[$i] !== $page[$i]->url_ang)
                                Model::updatePage(['id' => $page[$i]->id, 'navbar_id' => $last_insert_id], [
                                    'url'   =>  $liens[$i],
                                    'url_ang'   =>  $liens_ang[$i],
                                    'updated'   =>  $timestamp,
                                    'navbar_id' =>  $last_insert_id
                                ]);
                        }
                    }else {
                        if (count($page) < count($liens)) {
                            for($i = 0; $i < count($liens); $i++) {
                                if (!array_key_exists($i, $page) && !in_array($liens[$i], $page)) {
                                    Model::insertPage([
                                        'url'   =>  $liens[$i],
                                        'url_ang'   =>  $liens_ang[$i],
                                        'content'   =>  null,
                                        'created'   =>  $timestamp,
                                        'updated'   =>  null,
                                        'online'    =>  0,
                                        'navbar_id' =>  $last_insert_id
                                    ]);
                                }else {
                                    if ($liens[$i] !== $page[$i]->url | $liens_ang[$i] !== $page[$i]) {
                                        Model::updatePage(['id' => $page[$i]->id, 'navbar_id' => $last_insert_id], [
                                            'url'   =>  $liens[$i],
                                            'url_ang'   =>  $liens_ang[$i],
                                            'updated'   =>  $timestamp,
                                            'navbar_id' =>  $last_insert_id
                                        ]);
                                    }
                                }
                            }
                        }else {
                            $present_in_db = [];
                            for($i = 0; $i < count($page); $i++) {
                                $present_in_db[] = $page[$i]->url;
                                if (array_key_exists($i, $liens))
                                    if ($liens[$i] !== $page[$i]->url | $liens_ang[$i] !== $page[$i]->url_ang)
                                        Model::updatePage(['id' => $page[$i]->id, 'navbar_id' => $last_insert_id], [
                                            'url'   =>  $liens[$i],
                                            'url_ang'   =>  $liens_ang[$i],
                                            'updated'   =>  $timestamp,
                                            'navbar_id' =>  $last_insert_id
                                        ]);
                            }
                            $diff = array_diff($present_in_db, $liens);
                            if(!empty($diff)) {
                                foreach($diff as $value) {
                                    Model::deletePage(['url' => $value]);
                                }
                            }
                        }
                    }

                    $flash->success("Modification effectuée avec réussite");
                    Route::redirect($url);
                }else {
                    $flash->info('Aucune modification n\'a été apportée à l\'item "'. $menu->name .'" dans le menu');
                    Route::redirect($url);
                }
            }else {
                $flash->error($errors);
                Route::redirect($url);
            }
        }
    }
}
