<?php

namespace Web\App;

use Web\App\Core\Router;
use Web\App\Core\Request;
use Web\App\Core\Route\Route;

class WebApp
{

    private $modules = [];

    /**
     * @var Router
     */
    private $router;

    /**
     * WebApp constructor.
     * @param array $modules Modules to upload.
     * @param array $dependencies Dependencies to the app.
     */
    public function __construct(array $modules = [], array $dependencies = [])
    {
        $this->router = new Router();
        if (array_key_exists('renderer', $dependencies))
            $dependencies['renderer']->addGlobal('router', $this->router);
        foreach ($modules as $module) {
            $this->modules[] = new $module($this->router, $dependencies['renderer']);
        }
    }

    public function run (): void
    {
        $match = $this->router->matcher();

        $uri = Request::getRequest();

        if (is_null($match)) {
            if (!empty($uri) && $uri[-1] ==="/" && $uri !== '/') {
                $uri = substr($uri, 0, -1);
                $match = $this->router->matcher($uri);
                if (!is_null($match)) {
                    $name = $match->getName();
                    $routes = $this->router->getRoutes()->getNamedRoutes();
                    Route::redirect($routes[$name]);
                }else {
                    http_response_code(404);
                    echo "<h1>404 not found</h1>";
                    exit();
                }
            }else {
                http_response_code(404);
                echo "<h1>404 not found</h1>";
                exit();
            }
        }

        if (!empty($uri) && $uri[-1] ==="/" && $uri !== '/') {
            $uri = substr($uri, 0, -1);
            if (!is_null($match)) {
                Route::redirect($uri);
            }
        }
        

        $params = $match->getParams();
        $view = $match->getController();
        $router = $this->router;

        $content = call_user_func_array($view, [$params]);
        http_response_code(200);
    }
}

