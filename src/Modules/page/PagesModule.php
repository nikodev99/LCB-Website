<?php

namespace App\page;

use App\AppModules;
use Web\App\Core\Router;
use Web\App\Core\PHPViewRender;

class PagesModule extends AppModules
{
    public function __construct(Router $router, PHPViewRender $renderer)
    {
        parent::__construct('page', $router, $renderer);
        $router
            ->get('/page/[*:slug]', [$this, 'index'], 'page.index')
            ->get('/en/page/[*:slug]', [$this, 'index'], 'page@indexed')
            ->get('/fr/page/[*:slug]', [$this, 'index'], 'page@index')
            ->get('/page_indisponible', [$this, 'page_indisponible'], 'page@indisponible')
            ->get('/icons', [$this, 'page_icon'], 'page.icon')
            ->get('/modify_accordion/[*:slug]', [$this, 'modify_accordion'], 'page.modify.accordion')
            ->get('/modify_tabs/[*:slug]', [$this, 'modify_tabs'], 'page.modify.tabs')
            ->get('/modify_work/[*:slug]', [$this, 'modify_work'], 'page.modify.work')
            ->get('/modify_multiple_feature/[*:slug]', [$this, 'modifyFeature_modify'], 'page.motify.multipleFeature')
            ->get('/presentation_modify/[*:slug]', [$this, 'presentation_modify'], 'page.motify.presentation')
            ->get('/[*:slug]/search', [$this, 'search'], 'page.search')

            ->match('/modify_about/[*:slug]', [$this, 'about_modify'], 'page.about.modify')
            ->match('/modify_expose/[*:slug]', [$this, 'expose_modify'], 'page.expose.modify')
            ->match('/accordion_modify/[*:slug]_[i:id]', [$this, 'accordion_modify'], 'page.accordion.modify')
            ->match('/tabs_modify/[*:slug]_[i:id]', [$this, 'tabs_modify'], 'page.tabs.modify')
            ->match('/add_accordion/[*:slug]', [$this, 'add_accordion'], 'page.accordion.add')
            ->match('/add_tab/[*:slug]', [$this, 'add_tab'], 'page.tab.add')
            ->match('/modify_left_expose/[*:slug]', [$this, 'left_expose_modify'], 'page.lefexpose.modify')
            ->match('/modify_text/[*:slug]', [$this, 'text_modify'], 'page.text.modify')
            ->match('/modify_reason/[*:slug]', [$this, 'modify_reason'], 'page.reason.modify')
            ->match('/work_item_add/[*:slug]', [$this, 'work_item_add'], 'page.work.add')
            ->match('/work_item_modify/[*:slug]_[i:id]', [$this, 'work_item_modify'], 'page.work.modify')
            ->match('/add_title/[*:slug]', [$this, 'add_multipleFeature_title'], 'page.add.title')
            ->match('/modify_title/[*:slug]', [$this, 'MultipleFeatures_modify_title'], 'page.motify.title')
            ->match('/add_multiple_feature/[*:slug]', [$this, 'add_multipleFeatures'], 'page.add.multipleFeature')
            ->match('/multipleFeature_item_modify/[*:slug]_[i:id]', [$this, 'modify_multipleFeatures'], 'page.multipleFeature.modify')
            ->match('/add_presentation/[*:slug]', [$this, 'add_presentation'], 'page.add.presentation')
            ->match('/modify_presentation/[*:slug]_[i:id]', [$this, 'modify_presentation'], 'page.modify.presentation')

            ->post('/modify_breadcrump/[*:slug]', [$this, 'breadcrump_modify'], 'page.breadcrump.modify')
            ->post('/modify_contact/[*:slug]', [$this, 'contact_modify'], 'page.contact.modify')
            ->post('/remove_accordion/[*:slug]_[i:id]', [$this, 'remove_accordion'], 'page.accordion.remove')
            ->post('/remove_tab/[*:slug]_[i:id]', [$this, 'remove_tab'], 'page.tab.remove')
            ->post('/contact_submit_[*:slug]', [$this, 'contact_submit'], 'page.contact.submit')
            ->post('/work_item_remove/[*:slug]_[i:id]', [$this, 'work_item_remove'], 'page.work.remove')
            ->post('/remove_multiple-feauture_item/[*:slug]_[i:id]', [$this, 'remove_multipleFeatures'], 'page.multipleFeauture.remove')
            ->post('/remove_presentation/[*:slug]_[i:id]', [$this, 'remove_presentation'], 'page.presentation.remove')
            ->post('/sending_message_[*:slug]', [$this, 'sendMessage'], 'career.message')
        ;
    }

    public function index(array $params = []): void
    {
        $this->renderer->render('@page/index', $params);
    }

    public function page_indisponible(array $params = []): void
    {
        $this->renderer->render('@page/page_indisponible', $params);
    }

    public function breadcrump_modify(array $params = []): void
    {
        $this->renderer->render('@page/breadcrump_modify', $params);
    }

    public function about_modify(array $params = []): void
    {
        $this->renderer->render('@page/about_modify', $params, 'admin');
    }

    public function expose_modify(array $params = []): void
    {
        $this->renderer->render('@page/expose_modify', $params, 'admin');
    }

    public function page_icon(array $params = []): void
    {
        $this->renderer->render('@page/page_icon', $params, 'page');
    }

    public function accordion_modify(array $params = []): void
    {
        $this->renderer->render('@page/accordion_modify', $params, 'admin');
    }

    public function tabs_modify(array $params = []): void
    {
        $this->renderer->render('@page/tabs_modify', $params, 'admin');
    }

    public function modify_accordion(array $params = []): void
    {
        $this->renderer->render('@page/modify_accordion', $params, 'admin');
    }

    public function modify_tabs(array $params = []): void
    {
        $this->renderer->render('@page/modify_tabs', $params, 'admin');
    }

    public function add_accordion(array $params = []): void
    {
        $this->renderer->render('@page/add_accordion', $params, 'admin');
    }

    public function add_tab(array $params = []): void
    {
        $this->renderer->render('@page/add_tab', $params, 'admin');
    }

    public function remove_accordion(array $params = []): void
    {
        $this->renderer->render('@page/remove_accordion', $params, 'admin');
    }

    public function remove_tab(array $params = []): void
    {
        $this->renderer->render('@page/remove_tab', $params, 'admin');
    }

    public function left_expose_modify(array $params = []): void
    {
        $this->renderer->render('@page/left_expose_modify', $params, 'admin');
    }

    public function modify_reason(array $params = []): void
    {
        $this->renderer->render('@page/modify_reason', $params, 'admin');
    }

    public function modify_work(array $params = []): void
    {
        $this->renderer->render('@page/modify_work', $params, 'admin');
    }

    public function work_item_add(array $params = []): void
    {
        $this->renderer->render('@page/work_item_add', $params, 'admin');
    }
    
    public function work_item_modify(array $params = []): void
    {
        $this->renderer->render('@page/work_item_modify', $params, 'admin');
    }

    public function work_item_remove(array $params = []): void
    {
        $this->renderer->render('@page/work_item_remove', $params, 'admin');
    }

    public function contact_modify(array $params = []): void
    {
        $this->renderer->render('@page/contact_modify', $params, 'admin');
    }

    public function text_modify(array $params = []): void
    {
        $this->renderer->render('@page/text_modify', $params, 'admin');
    }

    public function contact_submit(array $params = []): void
    {
        $this->renderer->render('@page/contact_submit', $params, 'admin');
    }

    public function modifyFeature_modify(array $params = []): void
    {
        $this->renderer->render('@page/multipleFeature_modify', $params, 'admin');
    }

    public function add_multipleFeature_title(array $params = []): void
    {
        $this->renderer->render('@page/add_multipleFeature_title', $params, 'admin');
    }

    public function MultipleFeatures_modify_title(array $params = []): void
    {
        $this->renderer->render('@page/multipleFeatures_title', $params, 'admin');
    }

    public function add_multipleFeatures(array $params = []): void
    {
        $this->renderer->render('@page/add_multipleFeatures', $params, 'admin');
    }

    public function modify_multipleFeatures(array $params = []): void
    {
        $this->renderer->render('@page/modify_multipleFeatures', $params, 'admin');
    }
    
    public function remove_multipleFeatures(array $params = []): void
    {
        $this->renderer->render('@page/remove_multipleFeatures', $params, 'admin');
    }

    public function presentation_modify(array $params = []): void
    {
        $this->renderer->render('@page/presentation_modify', $params, 'admin');
    }

    public function add_presentation(array $params = []): void
    {
        $this->renderer->render('@page/add_presentation', $params, 'admin');
    }

    public function remove_presentation(array $params = []): void
    {
        $this->renderer->render('@page/remove_presentation', $params, 'admin');
    }

    public function modify_presentation(array $params = []): void
    {
        $this->renderer->render('@page/modify_presentation', $params, 'admin');
    }

    public function sendMessage(array $params = []): void
    {
        $this->renderer->render('@page/sendMessage', $params);
    }

    public function search(array $params): void
    {
        $this->renderer->render('@page/search', $params);
    }

}