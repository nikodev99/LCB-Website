<?php

namespace Web\App\Core;

use Web\App\Core\Constant;
use Web\App\Core\Route\RenderInterface;

class PHPViewRender implements RenderInterface {

    private $paths = [];
    private $globals = [];

    public function addPath(string $namespace, ?string $path = null): void
    {
        !is_null($path) ? $this->paths[$namespace] = $path : $this->paths[Constant::DEFAULT_NAMESPACE] = $namespace;
    }

    public function render(string $view, array $params = [], string $defaultView = 'index'): void
    {
        if ($this->hasNamespace($view)) {
            $path = $this->replaceNamespace($view) . ".php";
        }else {
            $path = $this->paths[Constant::DEFAULT_NAMESPACE] . Constant::DS . $view . ".php";
        }
        ob_start();
        extract($this->globals);
        require($path);
        $content = ob_get_clean();
        require dirname(__DIR__, 2). Constant::DS . "views" . Constant::DS . $defaultView . '.php';
    }

    public function addGlobal(string $key, $value): void
    {
        $this->globals[$key] = $value;
    }

    private function hasNamespace(string $view): bool
    {
        return $view[0] === "@";
    }

    private function getNamespace(string $view): string
    {
        return substr($view, 1, strpos($view, "/") -1);
    }

    private function replaceNamespace(string $view): string
    {
        $namespace = $this->getNamespace($view);
        return str_replace("@" . $namespace, $this->paths[$namespace], $view);
    }

}