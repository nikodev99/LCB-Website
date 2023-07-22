<?php


namespace App;


use Web\App\Core\PHPViewRender;
use Web\App\Core\Router;

abstract class AppModules
{
    protected $renderer;

    private const PATH = __DIR__;
    private const DS = DIRECTORY_SEPARATOR;

    public function __construct(string $namespace, Router $router, PHPViewRender $renderer)
    {
        $this->renderer = $renderer;
        $this->renderer->addPath($namespace, self::PATH . self::DS . $namespace . self::DS . 'views');
    }
}