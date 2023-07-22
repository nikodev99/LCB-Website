<?php

namespace App\admin;

use App\AppModules;
use Web\App\Core\Router;
use Web\App\Core\PHPViewRender;

class AdminModule extends AppModules
{
    public function __construct(Router $router, PHPViewRender $renderer)
    {
        parent::__construct('admin', $router, $renderer);
        $router
            ->get('/lcb_bank_admin_panel', [$this, 'dashboard'], 'admin.dashboard')
            ->get('/deconnexion', [$this, 'logout'], 'admin.logout')
            ->get('/pages', [$this, 'page'], 'admin.page')
            ->get('/consultation_menu', [$this, 'menu'], 'admin.menu')
            ->get('/posts', [$this, 'posts'], 'admin.posts')
            ->get('/newsletter_subscribers', [$this, 'newsletter'], 'admin.newsletter')
            ->get('/messages', [$this, 'message'], 'admin.message')
            ->get('/message_view/[i:id]', [$this, 'message_view'], 'admin.message.view')
            
            ->match('/create_page/[*:slug]', [$this, 'page_constructor'], 'admin.page.constructor')
            ->match('/modify_page/[*:slug]', [$this, 'page_modificator'], 'admin.page.modificator')
            ->match('/manage_menu/[*:slug]/[i:id]', [$this, 'manage'], 'admin.menu.manage')
            ->match('/admin_login', [$this, 'index'], 'admin.index')
            ->match('/settings', [$this, 'setting'], 'admin.setting')
            ->match('/meta-data/[*:slug]_[i:id]', [$this, 'metadata'], 'admin.metadata')

            ->post('/edit_menu_item/[*:slug]/[i:id]', [$this, 'edit_menu_item'], 'admin.edit.menu.item')
            ->post('/adding_menu_item', [$this, 'adding_menu_item'], 'admin.adding.menu.item')
            ->post('/remove_item_from_menu/[*:slug]/[i:id]', [$this, 'remove_item_from_menu'], 'admin.remove.menu.item')
            ->post('/make_page_online/[*:slug]_[i:id]', [$this, 'online'], 'admin.page.online')
            ->post('/make_page_offline/[*:slug]_[i:id]', [$this, 'offline'], 'admin.page.offline')
            ->post('/page_creation/[*:slug]', [$this, 'create_page'], 'admin.page.creation')
            ->post('/page_modify/[*:slug]', [$this, 'modify_page'], 'admin.page.modify')
            ->post('/message_delete/[i:id]', [$this, 'message_delete'], 'admin.message.delete')
            ;
    }

    public function index(array $params = []): void
    {
        $this->renderer->render('@admin/index', $params, 'admin');
    }

    public function dashboard(array $params = []): void
    {
        $this->renderer->render('@admin/dashboard', $params, 'admin');
    }

    public function logout(array $params = []): void
    {
        $this->renderer->render('@admin/logout', $params, 'admin');
    }

    public function menu(array $params = []): void
    {
        $this->renderer->render('@admin/menu', $params, 'admin');
    }

    public function manage(array $params = []): void
    {
        $this->renderer->render('@admin/manage', $params, 'admin');
    }

    public function adding_menu_item(array $params = []): void
    {
        $this->renderer->render('@admin/adding_menu_item', $params, 'admin');
    }

    public function edit_menu_item(array $params = []): void
    {
        $this->renderer->render('@admin/edit_menu_item', $params, 'admin');
    }

    public function remove_item_from_menu(array $params = []): void
    {
        $this->renderer->render('@admin/remove_item_from_menu', $params, 'admin');
    }

    public function page(array $params = []): void
    {
        $this->renderer->render('@admin/page', $params, 'admin');
    }

    public function page_constructor(array $params = []): void
    {
        $this->renderer->render('@admin/page_constructor', $params, 'page');
    }

    public function page_modificator(array $params = []): void
    {
        $this->renderer->render('@admin/page_modificator', $params, 'page');
    }

    public function online(array $params = []): void
    {
        $this->renderer->render('@admin/online', $params, 'admin');
    }

    public function offline(array $params = []): void
    {
        $this->renderer->render('@admin/offline', $params, 'admin');
    }

    public function create_page(array $params = []): void
    {
        $this->renderer->render('@admin/create_page', $params, 'admin');
    }

    public function modify_page(array $params = []): void
    {
        $this->renderer->render('@admin/modify_page', $params, 'admin');
    }

    public function posts(array $params = []): void
    {
        $this->renderer->render('@admin/posts', $params, 'admin');
    }

    public function newsletter(array $params = []): void
    {
        $this->renderer->render('@admin/newsletter', $params, 'admin');
    }

    public function message(array $params = []): void
    {
        $this->renderer->render('@admin/message', $params, 'admin');
    }

    public function message_view(array $params = []): void
    {
        $this->renderer->render('@admin/message_view', $params, 'admin');
    }

    public function message_delete(array $params = []): void
    {
        $this->renderer->render('@admin/message_delete', $params, 'admin');
    }

    public function setting(array $params = []): void
    {
        $this->renderer->render('@admin/settings', $params, 'admin');
    }

    public function metadata(array $params = []): void
    {
        $this->renderer->render('@admin/metadata', $params, 'admin');
    }
}