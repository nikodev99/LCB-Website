<?php


namespace Web\App\Core\Route;


interface RenderInterface
{
    /**
     * Add a path to the view to return
     * @param string $namespace
     * @param string|null $path
     */
    public function addPath(string $namespace, ?string $path = null): void;

    /**
     * Return a client view
     * @param string $view
     * @param array $params
     * @return string
     */
    public function render(string $view, array $params = []): void;

    /**
     * Create global variables for all the view
     * @param string $key
     * @param mixed $value
     */
    public function addGlobal(string $key, $value): void;

}