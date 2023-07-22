<?php

use Web\App\Components\PageRender;
use Web\App\Core\Flash;
use Web\App\Core\Request;

$page = new PageRender();
$session = new Flash();
$session_flash = $session->flash();

?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="content-type" content="text/html; charset=UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= $page_title ?? 'LCB Bank, Admin Panel' ?></title>
    <!-- <link href="https://fonts.googleapis.com/css2?family=Playfair+Display:wght@400;700&display=swap" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Playfair+Display:wght@400;700&family=Source+Sans+Pro:wght@400;700&display=swap" rel="stylesheet"> -->
    <link rel="stylesheet" href="../../webroot/css/semantic.min.css">
    <link rel="stylesheet" href="../../webroot/datatable/css/dataTables.semanticui.min.css">
    <link rel="stylesheet" href="../../webroot/css/credit-icon.css">
    <link rel="stylesheet" href="../../webroot/css/main_admin.css">
</head>
<body <?= Request::checkCurrentURL($router->url('admin.index')) ? 'class="login-body"' : null ?>>

    <?php if (!Request::checkCurrentURL($router->url('admin.index'))): ?>
        <aside class="ui sidebar vertical menu sidebar-menu" id="sidebar">
            <?= $page->getNavItems([
                'header'    =>  'Géneral',
                'items'  =>  [
                    0 =>    [
                        'url'   =>  $router->url('admin.dashboard'),
                        'icon'  =>  'dashboard',
                        'name'  =>  'Dashboard'
                    ],
                    1 =>    [
                        'url'   =>  $router->url('homepage.index'),
                        'icon'  =>  'home',
                        'name'  =>  'acceuil'
                    ]
                ]
            ]) ?>
            <?= $page->getNavItems([
                'header'    =>  'Administration',
                'items'  =>  [
                    0   =>  [
                        'url'   =>  '#',
                        'icon'  =>  'users',
                        'name'  =>  'Team'
                    ],
                    1   =>  [
                        'url'   =>  $router->url('admin.menu'),
                        'icon'  =>  'th',
                        'name'  =>  'Menu'
                    ],
                    2   =>  [
                        'url'   =>  '#',
                        'icon'  =>  'cogs',
                        'name'  =>  'Settings'
                    ]
                ]
            ]) ?>
            <?= $page->getNavItems([
                'items'  =>  [
                    0   =>  [
                        'url'   =>  $router->url('admin.page'),
                        'icon'  =>  'chart line',
                        'name'  =>  'Pages'
                    ],
                    1   =>  [
                        'url'   =>  $router->url('admin.posts'),
                        'icon'  =>  'lightbulb',
                        'name'  =>  'Articles'
                    ]
                ]
            ]) ?>
            <?= $page->getNavItems([
                'header'    =>  'Others',
                'items'  =>  [
                    0   =>  [
                        'url'   =>  $router->url('admin.message'),
                        'icon'  =>  'envelope',
                        'name'  =>  'Message'
                    ],
                    1   =>  [
                        'url'   =>  '#',
                        'icon'  =>  'calendar alternate',
                        'name'  =>  'Calendar'
                    ]
                ]
            ]) ?>
            <div class="item">
                <form action="" method="GET">
                    <div class="ui mini action input">
                        <input type="text" placeholder="Recherche">
                        <button type="submit" class="ui mini icon button">
                            <i class="icon search"></i>
                        </button>
                    </div>
                </form>
            </div>
            <?= $page->getProgressBar([
                0   =>  [
                    "width" =>  '54%',
                    'background'    =>  'red',
                    'label' =>  "monthly regard"
                ],
                1   =>  [
                    "width" =>  '78%',
                    'background'    =>  'green',
                    'label' =>  "monthly regard"
                ]
            ]) ?>
        </aside>

        <nav class="ui top fixed menu">
            <a href="#" class="sidebar-menu-toggler item" data-target="#sidebar">
                <i class="icon sidebar"></i>
            </a>
            <a href="<?= $router->url('admin.dashboard') ?>" class="header item">LCB Bank</a>
            <div class="right menu">
                <div class="ui dropdown item">
                    <i class="icon bell"></i><i class="note-full"></i>
                    <div class="menu">
                        <ul>
                            <li><a href="#" class="item">Notification 1</a></li>
                            <li><a href="#" class="item">Notification 2</a></li>
                            <li><a href="#" class="item"></i>Notification 3</a></li>
                        </ul>
                    </div>
                </div>
                <div class="ui dropdown item">
                    <i class="icon user circle"></i>
                    <div class="menu">
                        <a href="#" class="item"><i class="icon info circle"></i>Profil</a>
                        <a href="<?= $router->url('admin.setting') ?>" class="item"><i class="icon wrench"></i>Parametres</a>
                        <a href="<?= $router->url('admin.logout') ?>" class="item"><i class="icon sign-out"></i>Déconnexion</a>
                    </div>
                </div>
            </div>
        </nav>
    <?php endif ?>

    <div class="pusher">
        <div class="main-content">
            <?= $content ?? null ?>
        </div>
    </div>

    <script src="../../webroot/js/jquery-2.2.4.min.js"></script>
    <script src="../../webroot/js/bootstrap/semantic.min.js"></script>
    <script src="../../webroot/datatable/js/jquery.dataTables.min.js"></script>
    <script src="../../webroot/datatable/js/dataTables.semanticui.min.js"></script>
    <script src="../../webroot/js/ckeditor/ckeditor.js"></script>
    <script src="../../webroot/js/admin.js"></script>
    <script src="../../webroot/js/main.js"></script>
</body>
</html>

<?php if (!empty($session_flash)): 
    $icon = null;
    ?>
    <?php foreach ($session_flash as $type => $message): 
            $class = null; $color = null;
            if($type === 'success') {
                $icon = 'check circle';
                $class = 'ui green content';
                $color = 'green';
            }elseif($type === 'error') {
                $icon = 'exclamation triangle';
                $class = 'ui red content';
                $color = 'red';
            }else {
                $icon = 'info circle';
                $class = 'ui teal content';
                $color = 'teal';
            }
        ?>
        <div class="ui basic modal" id="flashMessage">
            <div class="ui icon <?= $color ?> header">
                <i class="<?= $icon ?> icon"></i>
                <?= ucwords($type . ' message') ?>
            </div>
            <div class="content">
                <?php if (strpos($session->get($type), '<ul>')): ?>
                    <?= str_replace('<ul>', '<ul class="flashMessage">', $session->get($type)) ?>
                <?php else: ?>
                <p class="flashMessage" style="color: <?= $color ?>;">
                    <?= $session->get($type) ?>
                </p>
                <?php endif ?>
            </div>
            <div class="actions">
                <div class="ui green basic ok inverted button">
                    <i class="checkmark icon"></i>
                    Ok
                </div>
            </div>
        </div>
    <?php endforeach ?>
    <?php $session->unset_flash() ?>
<?php endif ?>