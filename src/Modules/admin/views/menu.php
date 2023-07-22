<?php

use Web\App\Core\Model;
use Web\App\Components\Form;
use Web\App\Core\AppFunction;
use Web\App\Components\Component;
use Web\App\Components\PageRender;
use Web\App\Components\Utilities\NavBar;

AppFunction::user_not_connected($router->url('admin.index'));

$page = new PageRender();

$menu_items= Model::getAllMenuItem();

?>
<div class="ui grid stackable padded">
    <div class="column">
        <div class="ui relaxed divided list">
            <div class="item">
                <div class="content">
                    <?= Component::modalLauncher(['data'  => 'menu']) ?>
                    <div class="description">Nouveau menu item</div>
                </div>
            </div>
        </div>
        <div class="ui divider"></div>
        <?php foreach($menu_items as $item): 
            $submenu = [];
            if (!is_null($item->submenu_items))$submenu = json_decode($item->submenu_items, true);
            ?>
        <div class="ui text menu">
            <div class="item">
                <i class="icon th large"></i>
            </div>
            <a href="<?= is_null($item->type) ? $router->url('page.index', ['slug' => $item->item_url]) : '#' ?>" class="browse item">
                <?= trim($item->name) ?>
                <i class="dropdown icon"></i>
            </a>
            <div class="ui right dropdown item">
                Plus
                <i class="dropdown icon"></i>
                <div class="menu">
                    <div class="item">
                        <?php $slug_replace = str_replace('/', '_', strtolower($item->name)) ?>
                        <a href="<?= $router->url('admin.menu.manage', [
                            'slug' => $slug_replace,
                            'id'    =>  $item->id
                        ]) ?>" class="ui positive fluid button">Consulter</a>
                        <div class="ui divider"></div>
                        <?= NavBar::removal($router->url('admin.remove.menu.item', [
                            'slug' => $slug_replace, 'id'    =>  $item->id
                        ]), 'cet item du menu') ?>
                    </div>
                </div>
            </div>
        </div>
        <div class="ui flowing basic admission popup">
            <div class="ui four column relaxed divided grid">
                <?php if (!empty($submenu)): foreach ($submenu as $key => $value): ?>
                <div class="column">
                    <?php if (is_array($value)): ?>
                        <?php foreach($value as $k => $v): ?>
                            <?php if ($k === '#'): ?>
                            <h4 class="ui header"><?= $v ?></h4>
                            <?php else: ?>
                            <div class="ui link list">
                            <a href="<?= $router->url('page.index', ['slug' => trim($k)]) ?>" class="item"><?= trim($v) ?></a> 
                            </div>
                            <?php endif ?>
                        <?php endforeach ?>
                    <?php else: ?>
                        <h4 class="ui header"><?= null ?></h4>
                        <div class="ui link list">
                        <a href="<?= $router->url('page.index', ['slug' => trim($key)]) ?>" class="item"><?= trim($value) ?></a> 
                        </div>
                    <?php endif ?>
                </div>
                <?php endforeach; endif ?>
            </div>
        </div>
        <?php endforeach ?>
    </div>
</div>
<div class="ui modal" data-modal="menu">
    <div class="header">
        Ajout une item au menu
    </div>
    <div class=" content">
        <form class="ui fluid form" action="<?= $router->url('admin.adding.menu.item') ?>" method="POST">
            <div class="two fields">
                <?= Form::text([
                    'label' =>  "Entrer le nom de l'item a ajouter",
                    'name'  =>  'item',
                    'placeholder'   =>  'Nouveau menu item'
                ]) ?>
                <?= Form::text([
                    'label' =>  "Entrer le nom de l'item a ajouter (en anglais)",
                    'name'  =>  'item-ang',
                    'placeholder'   =>  'New item name'
                ]) ?>
            </div>
           <div class="could go">
               <div class="two fields">
                    <?= Form::text([
                        'label' =>  "Entrer l'URL de l'item a ajouter",
                        'name'  =>  'item-url',
                        'placeholder'   =>  'URL de l\'item'
                    ]) ?>
                    <?= Form::text([
                        'label' =>  "Entrer l'URL de l'item a ajouter (en anglais)",
                        'name'  =>  'item-url-ang',
                        'placeholder'   =>  'item\'s URI'
                    ]) ?>
               </div>
           </div>
            
           <?= Form::select([
               'name'   =>  'menu-type',
               'options'    =>  [
                   0    =>  ['' =>  'Genre du l\'item'],
                   1    =>  ['dropdown' =>  'Item déroulant'],
                   2    =>  ['megamenu' =>  'Megamenu']
                ]
           ]) ?>
            <div class="create-dropdown-menu ui secondary segment">
                <div class="two fields">
                    <?= Form::text([
                        'label' =>  "Entrer le nom des sous items déroulant en les séparant par les points virgules",
                        'name'  =>  'sub-item',
                        'placeholder'   =>  'Sous items déroulant'
                    ]) ?>
                    <?= Form::text([
                        'label' =>  "Entrer le nom des sous items déroulant en les séparant par les points virgules (en anglais)",
                        'name'  =>  'sub-item-ang',
                        'placeholder'   =>  'Dropdown sub items'
                    ]) ?>
                </div>
                <div class="two fields">
                    <?= Form::text([
                        'label' =>  "Entrer les URLs des sous items déroulant en les séparant par les points virgules",
                        'name'  =>  'sub-item-url',
                        'placeholder'   =>  'URLs des sous items déroulant'
                    ])?>
                    <?= Form::text([
                        'label' =>  "Entrer les URLs des sous items déroulant en les séparant par les points virgules (en anglais)",
                        'name'  =>  'sub-item-url-ang',
                        'placeholder'   =>  'Dropdown sub items URIs'
                    ])?>
                </div>
            </div>
            <div class="create-megamenu ui secondary segment">
                <div class="two fields">
                    <?= Form::text([
                        'label' =>  "Entrer le nom des sous-menu, les séparer par les points virgules",
                        'name'  =>  'submenu',
                        'placeholder'   =>  'Sous items'
                    ]) ?>
                    <?= Form::text([
                        'label' =>  "Entrer le nom des sous-menu, les séparer par les points virgules (en anglais)",
                        'name'  =>  'submenu-ang',
                        'placeholder'   =>  'Sub items'
                    ]) ?>
                </div>
                <div class="two fields">
                    <?= Form::text([
                        'label' =>  "Entrer la liste de chacune des sous items en les séparant par des (;) et les (|)",
                        'name'  =>  'submenu-item',
                        'placeholder'   =>  'liste de chacune des sous item'
                    ])?>
                    <?= Form::text([
                        'label' =>  "Entrer la liste de chacune des sous items en les séparant par des (;) et les (|) (en anglais)",
                        'name'  =>  'submenu-item-ang',
                        'placeholder'   =>  'Each sub items list'
                    ])?>
                </div>
                <div class="two fields">
                    <?= Form::text([
                        'label' =>  "Entrer le URLs des sous item déroulant en les séparant par des (;) et les (|)",
                        'name'  =>  'submenu-item-url',
                        'placeholder'   =>  'URLs de chacune des items de la liste'
                    ])?>
                    <?= Form::text([
                        'label' =>  "Entrer le URLs des sous item déroulant en les séparant par des (;) et les (|) (en anglais)",
                        'name'  =>  'submenu-item-url-ang',
                        'placeholder'   =>  'Each item URI of the sub items list'
                    ])?>
                </div>
            </div>
            <button type="submit" class="ui button primary" style="margin-top: 5px;">Créer</button>
        </form>
    </div>
</div>