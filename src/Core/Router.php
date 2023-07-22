<?php


namespace Web\App\Core;


use AltoRouter;
use RuntimeException;
use Web\App\Core\Route\Route;

class Router
{

    /**
     * @var AltoRouter
     */
    private $router;

    public function __construct()
    {
        $this->router = new AltoRouter();
    }

    public function get(string $path, callable $callable, string $name = null): self
    {
        $this->router->map("GET", $path, $callable, $name);
        return $this;
    }

    public function post(string $path, callable $callable, ?string $name = null): self
    {
        $this->router->map("POST", $path, $callable, $name);
        return $this;
    }

    public function match(string $path, callable $callable, ?string $name): self
    {
        $this->router->map("GET|POST", $path, $callable, $name);
        return $this;
    }

    public function matcher (?string $request = null): ?Route
    {
        $result = $this->router->match($request);
        
        if (is_array($result)) {
            return new Route(
                $result['name'],
                $result['target'],
                $result['params']
            );
        }else {
            return null;
        }
    }

    public function url (string $name, array $params = []): ?string
    {
        try {
            return $this->router->generate($name, $params);
        }catch (RuntimeException $run) {
            die("<h1>Le module dans lequel vous naviguer n'existe pas.<br> Cette erreur à émerger: \"{$run->getMessage()}\"<br>Veuillez contacter le developpeur du site</h1>");
        }
        
    }

    public function getRoutes(): AltoRouter
    {
        return $this->router;
    }
}
