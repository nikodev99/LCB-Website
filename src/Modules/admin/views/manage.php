<?php

use Web\App\Core\Model;
use Web\App\Components\Form;
use Web\App\Core\AppFunction;
use Web\App\Components\Component;
use Web\App\Components\PageRender;

AppFunction::user_not_connected($router->url('admin.index'));

$conditions = [
    'id'  =>  $params['id']
];

$page = new PageRender();
$menu_item = Model::getMenuItem($conditions);

$tds = [];
$str_submenu = !is_null($menu_item->submenu) ? implode(";", json_decode($menu_item->submenu, true)['submenu']) : null;
$str_submenu_ang = !is_null($menu_item->submenu_ang) ? implode(";", json_decode($menu_item->submenu_ang, true)['submenu']) : null;
$str_submenu_items = [];
$str_submenu_urls = [];
$str_submenu_items_ang = [];
$str_submenu_urls_ang = [];
$menu_items = json_decode($menu_item->submenu_items);
$submenu_items = json_decode($menu_item->submenu_items, true);
$submenu_items_ang = json_decode($menu_item->submenu_items_ang, true);
if($menu_item->type === 'megamenu') {
    foreach($submenu_items_ang as $items_ang) {
        if (is_array($items_ang)) {
            unset($items_ang['#']);
            $str_submenu_items_ang[] = implode(';', $items_ang);
            $str_submenu_urls_ang[] = implode(';', array_keys($items_ang));
        }
    }
}elseif($menu_item->type === 'dropdown') {
    $str_submenu_items[] = implode(';', $submenu_items);
    $str_submenu_urls[] = implode(';', array_keys($submenu_items));
    $str_submenu_items_ang[] = implode(';', $submenu_items_ang);
    $str_submenu_urls_ang[] = implode(';', array_keys($submenu_items_ang));
}
    

?>

<div class="ui grid stackable padded">
    <h2 class="ui center aligned icon header padded">
        <i class="circular th large icon"></i><?= $menu_item->name ?>
        <div>
            <?= Component::modalLauncher([
                'data'    =>  'menu_modify',
                'icon'    =>  '',
                'color'   =>  'primary basic',
                'label'   =>  'Modifier'
            ]) ?>
            <?php if (is_null($menu_item->type)): ?>
                <?= Component::button(['Créer', 'Voir'], [
                    $router->url('admin.page.constructor', ['slug' => trim($menu_item->item_url)]),
                    $router->url('page.index', ['slug' => trim($menu_item->item_url)])
                ], !is_null(Model::getPage(['url' => trim($menu_item->item_url)], 'content')->content), ['primary', 'positive']) ?>
            <?php endif ?>
        </div>
        <div class="ui divider"></div>
    </h2>
</div>

<?php if(!is_null($submenu_items) && !is_null($submenu_items)) foreach($submenu_items as $keys => $items): ?>
<?php if($menu_item->type === 'megamenu'): 
    unset($items['#']);
    $str_submenu_items[] = implode(';', $items);
    $str_submenu_urls[] = implode(';', array_keys($items));
    ?>
<div class="ui grid stackable padded">
  <div class="column">
    <div class="ui fluid card">
      <div class="content">
          <div class="header"><?= $keys ?></div>
      </div>
        <table class="ui celled striped table">
        <thead>
            <tr>
                <th>Sous-menu</th>
                <th>URL</th>
                <th>Action</th>
            </tr> 
        </thead>
        <tbody>
        <?php foreach($items as $k => $item): 
            if ($k !== '#') {
                $button = Component::button(['Créer', 'Voir'], [
                    $router->url('admin.page.constructor', ['slug' => trim($k)]),
                    $router->url('page.index', ['slug' => trim($k)])
                ], !is_null(Model::getPage(['url' => trim($k)], 'content')->content), ['primary', 'positive']);
            }
            ?>
            <tr>
                <td><?= $item ?></td>
                <td><?= $k ?></td>
                <td><?= $button ?></td>
            </tr>   
            <?php endforeach ?>
        </tbody>
        </table>
    </div>
  </div>
</div>
<?php else: 
    $button = Component::button(['Créer', 'Voir'], [
        $router->url('admin.page.constructor', ['slug' => trim($keys)]),
        $router->url('page.index', ['slug' => trim($keys)])
    ], !is_null(Model::getPage(['url' => trim($keys)], 'content')->content), ['primary', 'positive']);
    $tds[] = [$items, $keys, $button]
    ?>
<?php endif ?>
<?php endforeach ?>
<?php if($menu_item->type === 'dropdown'): ?>
<div class="ui grid stackable padded">
  <div class="column">
    <div class="ui fluid card">
        <?= Component::table([
          'th'    =>  [
              0   =>  ['Sous-menu', 'url', 'action']
          ],
          'td'    =>  $tds
        ]) ?>
    </div>
  </div>
</div>
<?php endif ?>

<div class="ui modal" data-modal="menu_modify">
    <div class="header">
        Ajout une item au menu
    </div>
    <div class=" content">
        <form class="ui fluid form" action="<?= $router->url('admin.edit.menu.item', $params) ?>" method="POST">
            <div class="two fields">
                <?= Form::text([
                    'label' =>  "Le nom de l'item",
                    'name'  =>  'item',
                    'value' =>  $menu_item->name
                ]) ?>
                <?= Form::text([
                    'label' =>  "Le nom de l'item (en anglais)",
                    'name'  =>  'item-ang',
                    'value' =>  $menu_item->name_ang
                ]) ?>
            </div>
            <?php if(is_null($menu_item->type)): ?>
            <div class="two fields">
                <?= Form::text([
                    'label' =>  "L'URL de l'item",
                    'name'  =>  'item-url',
                    'value' =>  $menu_item->item_url
                ]) ?>
                <?= Form::text([
                    'label' =>  "L'URL de l'item (en anglais)",
                    'name'  =>  'item-url-ang',
                    'value' =>  $menu_item->item_url_ang 
                ]) ?>
            </div>
           <?php endif ?>

           <?php if(!is_null($menu_item->type) && $menu_item->type === 'dropdown'): ?>
            <div class="ui secondary segment">
                <div class="two fields">
                    <?= Form::text([
                        'label' =>  "Nom des sous items déroulant séparés par les points virgules",
                        'name'  =>  'sub-item',
                        'value' =>  implode(';', $str_submenu_items)
                    ]) ?>
                    <?= Form::text([
                        'label' =>  "Nom des sous items déroulant séparés par les points virgules (en anglais)",
                        'name'  =>  'sub-item-ang',
                        'value' =>  implode(';', $str_submenu_items_ang)
                    ]) ?>
                </div>
                <div class="two fields">
                    <?= Form::text([
                        'label' =>  "Les URLs des sous items déroulant séparés par les points virgules",
                        'name'  =>  'sub-item-url',
                        'value' =>  implode(';', $str_submenu_urls)
                    ])?>
                    <?= Form::text([
                        'label' =>  "Les URLs des sous items déroulant séparés par les points virgules (en anglais)",
                        'name'  =>  'sub-item-url-ang',
                        'value' =>  implode(';', $str_submenu_urls_ang)
                    ])?>
                </div>
            </div>
                <?php elseif(!is_null($menu_item->type) && $menu_item->type === 'megamenu'): ?>
            <div class="ui secondary segment">
                <div class="two fields">
                    <?= Form::text([
                        'label' =>  "Sous-menu, séparés par les points virgules",
                        'name'  =>  'submenu',
                        'value' =>  $str_submenu
                    ]) ?>
                    <?= Form::text([
                        'label' =>  "Sous-menu, séparés par les points virgules (en anglais)",
                        'name'  =>  'submenu-ang',
                        'value' =>  $str_submenu_ang
                    ]) ?>
                </div>
                <div class="two fields">
                    <?= Form::text([
                        'label' =>  "Liste de chacun des sous items séparés par des (;) et les (|)",
                        'name'  =>  'submenu-item',
                        'value' =>  implode('|', $str_submenu_items)
                    ])?>
                    <?= Form::text([
                        'label' =>  "Liste de chacun des sous items séparés par des (;) et les (|) (en anglais)",
                        'name'  =>  'submenu-item-ang',
                        'value' =>  implode('|', $str_submenu_items_ang)
                    ])?>
                </div>
                <div class="two fields">
                    <?= Form::text([
                        'label' =>  "URLs des sous item déroulant séparés par des (;) et les (|)",
                        'name'  =>  'submenu-item-url',
                        'value' =>  implode('|', $str_submenu_urls)
                    ])?>
                    <?= Form::text([
                        'label' =>  "URLs des sous item déroulant séparés par des (;) et les (|) (en anglais)",
                        'name'  =>  'submenu-item-url-ang',
                        'value' =>  implode('|', $str_submenu_urls_ang)
                    ])?>
                </div>
            </div>
            <?php endif ?>
            <button type="submit" class="ui button primary" style="margin-top: 5px;">Modifier</button>
        </form>
    </div>
</div>