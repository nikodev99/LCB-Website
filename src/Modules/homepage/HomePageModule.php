<?php

namespace App\homepage;

use App\AppModules;
use Web\App\Core\PHPViewRender;
use Web\App\Core\Router;

class HomePageModule extends AppModules
{
    public function __construct(Router $router, PHPViewRender $renderer)
    {
        parent::__construct('homepage', $router, $renderer);
        $router
            ->get('/', [$this, 'index'], 'homepage.index')
            ->get('/acceuil', [$this, 'index'], 'home.index')
            ->get('/fr/home', [$this, 'index'], 'home@index')
            ->get('/en/home', [$this, 'index'], 'homepage@index')
            ->get('/modify/carousel', [$this, 'modify_carousel'], 'homepage.modify.carousel')
            ->get('/modify/feature', [$this, 'modify_feature'], 'homepage.modify.feature')
            ->get('/fr/newsletter', [$this, 'newsletter'], 'homepage.fr.newsletter')
            ->get('/en/newsletter', [$this, 'newsletter'], 'homepage.en.newsletter')
            ->get('/services', [$this, 'services'], 'homepage.service')
            ->get('/newsletter_modify', [$this, 'newsletter_modify'], 'homepage.modify.newsletter')

            ->match('/add_service', [$this, 'add_service'], 'homepage.add.service')
            ->match('/modify_service_[i:id]', [$this, 'modify_service'], 'homepage.modify.service')
            ->match('/modify/cta', [$this, 'modify_cta'], 'homepage.modify.cta')
            ->match('/carousel_modify/[i:id]', [$this, 'carousel_modify'], 'carousel.modify')
            ->match('/feature_modify/[i:id]', [$this, 'feature_modify'], 'feature.modify')

            ->post('/remove_carousel/[i:id]', [$this, 'remove_carousel'], 'homepage.remove.carousel')
            ->post('/remove_feature/[i:id]', [$this, 'remove_feature'], 'homepage.remove.feature')
            ->post('/add_carousel', [$this, 'add_carousel'], 'homepage.add.carousel')
            ->post('/add_feature', [$this, 'add_feature'], 'homepage.add.feature')
            ->post('/fr/add_newsletter_email', [$this, 'add_newsletter_email'], 'homepage.add.fr.newsletter')
            ->post('/en/add_newsletter_email', [$this, 'add_newsletter_email'], 'homepage.add.en.newsletter')
            ->post('/modify_newsletter', [$this, 'modify_newsletter'], 'homepage.newsletter.modify')
            ->post('/delete_service_[i:id]', [$this, 'delete_service'], 'homepage.delete.service')
            ;
    }

    public function index(array $params) {
        $this->renderer->render('@homepage/index', $params);
    }

    public function modify_carousel(array $params) {
        $this->renderer->render('@homepage/modify_carousel', $params, 'admin');
    }

    public function remove_carousel(array $params) {
        $this->renderer->render('@homepage/remove_carousel', $params, 'admin');
    }

    public function add_carousel(array $params) {
        $this->renderer->render('@homepage/add_carousel', $params, 'admin');
    }

    public function add_feature(array $params) {
        $this->renderer->render('@homepage/add_feature', $params, 'admin');
    }

    public function carousel_modify(array $params) {
        $this->renderer->render('@homepage/carousel_modify', $params, 'admin');
    }

    public function modify_feature(array $params) {
        $this->renderer->render('@homepage/modify_feature', $params, 'admin');
    }

    public function feature_modify(array $params) {
        $this->renderer->render('@homepage/feature_modify', $params, 'admin');
    }

    public function remove_feature(array $params) {
        $this->renderer->render('@homepage/remove_feature', $params, 'admin');
    }

    public function modify_cta(array $params) {
        $this->renderer->render('@homepage/modify_cta', $params, 'admin');
    }

    public function services(array $params) {
        $this->renderer->render('@homepage/services', $params, 'admin');
    }

    public function add_service(array $params) {
        $this->renderer->render('@homepage/add_service', $params, 'admin');
    }

    public function modify_service(array $params) {
        $this->renderer->render('@homepage/modify_service', $params, 'admin');
    }

    public function delete_service(array $params) {
        $this->renderer->render('@homepage/delete_service', $params, 'admin');
    }

    public function add_newsletter_email(array $params) {
        $this->renderer->render('@homepage/add_newsletter_email', $params, 'admin');
    }

    public function newsletter(array $params) {
        $this->renderer->render('@homepage/newsletter', $params, 'page');
    }

    public function newsletter_modify(array $params) {
        $this->renderer->render('@homepage/modify_newsletter', $params, 'admin');
    }

    public function modify_newsletter(array $params) {
        $this->renderer->render('@homepage/newsletter_modify', $params, 'admin');
    }
}