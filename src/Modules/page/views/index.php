<?php

use Web\App\Components\PageRender;
use Web\App\Core\AppFunction;
use Web\App\Core\Model;

$render = new PageRender($params['slug']);

$page_title = !empty($render->metadata()['title']) ? $render->metadata()['title'] : "LCB Bank, BMCE Group";
$meta_description = !empty($render->metadata()['description']) ? $render->metadata()['description'] : "Welcome on the LCB Website";
$keywords = !empty($render->metadata()['keyword']) ? $render->metadata()['keyword'] : 'LCB BanK';

$page = Model::getPage([
    'url' => trim($params['slug']),
    'url_ang' => trim($params['slug'])
]);

if (is_null($page)) {
    die('Erreur. Vérifié votre url');
}else {
    $fr_lang_url = $router->url('page@index', ['slug' => trim($page->url)]);
    $en_lang_url = $router->url('page@indexed', ['slug' => trim($page->url_ang)]);
}

$display = is_null($page->content) ? [] : json_decode($page->content);
$online = (int) $page->online === 1 ? true : false;
$url = $router->url('page@indisponible');
$paginatedUri = AppFunction::multipleURL([ 
    $router->url('page@index', ['slug' => $params['slug']]),
    $router->url('page@indexed', ['slug' => $params['slug']])
], AppFunction::langVerify());

?>

<?php if(AppFunction::display($online, $display, 'banniere', $url)): ?>
<!-- ##### Breadcrumb Area Start ##### -->
<?= $render->getBreadcrumb($display, 'breadcrump_modal') ?>
<!-- ##### Breadcrumb Area End ##### -->
<?= $render->getModal($router->url('page.breadcrump.modify', ['slug' => $params['slug']]), 'breadcrump_modal', 'breadcrump') ?>
<?php endif ?>

<?php if(AppFunction::display($online, $display, 'about', $url)): ?> 
<!-- ##### About Area Start ###### -->
<?= $render->getAboutArea($display, $router->url('page.about.modify', ['slug' => $params['slug']])) ?>
<!-- ##### About Area End ###### -->
<?php endif ?>

<?php if(AppFunction::display($online, $display, 'reason', $url)): ?> 
<!-- ##### About Area Start ###### -->
<?= $render->getReasonArea($display, $router->url('page.reason.modify', ['slug' => $params['slug']])) ?>
<!-- ##### About Area End ###### -->
<?php endif ?>

<?php if(AppFunction::display($online, $display, "multiple-feature", $url)): ?>
<!-- ##### Multiple feature start ###### -->
<?= $render->getMultipleFeature($display, $router->url('page.motify.multipleFeature', ['slug' => $params['slug']])) ?>
<!-- ##### Multiple feature end ###### -->
<?php endif ?>

<?php if(AppFunction::display($online, $display, 'expose', $url)): ?> 
<!-- ##### Call To Action Start ###### -->
<?= $render->getCallToAction($display, $router->url('page.expose.modify', ['slug' => $params['slug']])) ?>
<!-- ##### Call To Action End ###### -->
<?php endif ?>

<?php if(AppFunction::display($online, $display, 'map', $url)): ?> 
<!-- ##### google map ###### -->
<?= $render->getMap($display) ?>
<!-- ##### google map ###### -->
<?php endif ?>

<?php if(AppFunction::display($online, $display, 'accordion_area', $url)): ?> 
<!-- ##### FAQ Area Start ###### -->
<?= $render->getAccordionArea($display) ?>
<!-- ##### FAQ Area End ###### -->
<?php endif ?>

<?php if(AppFunction::display($online, $display, 'accordion', $url)): ?> 
<!-- ##### FAQ Area Start ###### -->
<?= $render->getAccordion($display, $router->url('page.modify.accordion', ['slug' => $params['slug']])) ?>
<!-- ##### FAQ Area End ###### -->
<?php endif ?>

<?php if(AppFunction::display($online, $display, '', $url)): ?> 
<!-- ##### Special Feature Area Start ###### -->
<?= $render->getLeftFeatures($display) ?>
<!-- ##### Special Feature Area End ###### -->
<?php endif ?>

<?php if(AppFunction::display($online, $display, 'work', $url)): ?> 
<!-- ##### Special Feature Area Start ###### -->
<?= $render->getWorkArea($display, $router->url('page.modify.work', ['slug' => $params['slug']])) ?>
<!-- ##### Special Feature Area End ###### -->
<?php endif ?>

<?php if(AppFunction::display($online, $display, 'left_expose', $url)): ?> 
<!-- ##### Special Feature Area Start ###### -->
<?= $render->getRightFeatures($display, $router->url('page.lefexpose.modify', ['slug' => $params['slug']])) ?>
<!-- ##### Special Feature Area End ###### -->
<?php endif ?>

<?php if(AppFunction::display($online, $display, 'contact', $url)): ?> 
<!-- ##### Contact Area Start ##### -->
<?= $render->getContactArea($router->url('page.contact.submit', ['slug' => $params['slug']]), $display, 'contact_modal') ?>
<?= $render->getModal($router->url('page.contact.modify', ['slug' => $params['slug']]), 'contact_modal', 'contact') ?>
<!-- ##### Contact Area End ##### -->
<?php endif ?>

<?php if(AppFunction::display($online, $display, 'posts', $url)): ?> 
<!-- ##### News Area Start ##### -->
<?= $render->getNewsSection($paginatedUri, $router->url('page.search', ['slug' => $params['slug']]), $display, $router->url('admin.posts')) ?>
<!-- ##### News Area End ##### -->
<?php endif ?>

<?php if(AppFunction::display($online, $display, 'single-post', $url)): ?> 
<!-- ##### Post Details Area Start ##### -->
<?= $render->getSinglePost($router->url('page.search', ['slug' => $params['slug']]), $display, $router->url('blog.modify', ['slug' => $params['slug']])) ?>
<!-- ##### Post Details Area End ##### -->
<?php endif ?>

<?php if(AppFunction::display($online, $display, 'text', $url)): ?> 
<!-- ##### Post Details Area Start ##### -->
<?= $render->getTextArea($display, $router->url('page.text.modify', ['slug' => $params['slug']])) ?>
<!-- ##### Post Details Area End ##### -->
<?php endif ?>

<?php if(AppFunction::display($online, $display, 'tabs', $url)): ?> 
<!-- ##### Tabs ##### -->
<?= $render->getTabs($display, $router->url('page.modify.tabs', ['slug' => $params['slug']])) ?>
<!-- ##### Tabs end #####-->
<?php endif ?>

<?php if(AppFunction::display($online, $display, 'presentation', $url)): ?> 
<!-- ##### Left Right section ###### -->
<?= $render->getPresentation($display, $router->url('page.motify.presentation', ['slug' => $params['slug']])) ?>
<!-- ##### Left Right section End ###### -->
<?php endif ?>

<?php if(AppFunction::display($online, $display, 'career', $url)): ?> 
<!-- ##### Career Area Start ##### -->
<?= $render->getCareerArea($display, $router->url('career.message', ['slug' => $params['slug']])) ?>
<!-- ##### Caree Area End ##### -->
<?php endif ?>
<?php if(AppFunction::display($online, $display, 'values', $url)): ?> 
<!-- ##### Values Area Start ##### -->
<?= $render->getValues($display) ?>
<!-- ##### Values Area End ##### -->
<?php endif ?>