<?php


namespace App\blog;

use App\AppModules;
use Web\App\Core\Router;
use Web\App\Core\PHPViewRender;

class BlogModule extends AppModules
{
    public function __construct(Router $router, PHPViewRender $renderer)
    {
        parent::__construct('blog', $router, $renderer);
        $router
            ->match('/blog/add_post', [$this, 'index'], 'blog.index')
            ->match('/modify/[*:slug]', [$this, 'modify'], 'blog.modify')
            ->match('/post_modify/[i:id]', [$this, 'post_modify'], 'post.modify')

            ->post('/online/[i:id]', [$this, 'online'], 'post.online')
            ->post('/offline/[i:id]', [$this, 'offline'], 'post.offline')
            ->post('/delete_post/[i:id]', [$this, 'remove'], 'post.remove')
            ;
    }

    public function index(array $params = []): void
    {
        $this->renderer->render('@blog/index', $params, 'admin');
    }

    public function modify(array $params = []): void
    {
        $this->renderer->render('@blog/modify', $params, 'admin');
    }

    public function post_modify(array $params = []): void
    {
        $this->renderer->render('@blog/post_modify', $params, 'admin');
    }

    public function online(array $params = []): void
    {
        $this->renderer->render('@blog/online', $params, 'admin');
    }

    public function offline(array $params = []): void
    {
        $this->renderer->render('@blog/offline', $params, 'admin');
    }

    public function remove(array $params = []): void
    {
        $this->renderer->render('@blog/remove', $params, 'admin');
    }
}